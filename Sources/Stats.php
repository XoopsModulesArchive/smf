<?php

/**********************************************************************************
 * Stats.php                                                                       *
 * **********************************************************************************
 * SMF: Simple Machines Forum                                                      *
 * Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                    *
 * =============================================================================== *
 * Software Version:           SMF 1.1                                             *
 * Software by:                Simple Machines (http://www.simplemachines.org)     *
 * Copyright 2006 by:          Simple Machines LLC (http://www.simplemachines.org) *
 *           2001-2006 by:     Lewis Media (http://www.lewismedia.com)             *
 * Support, News, Updates at:  http://www.simplemachines.org                       *
 * **********************************************************************************
 * This program is free software; you may redistribute it and/or modify it under   *
 * the terms of the provided license as published by Simple Machines LLC.          *
 *                                                                                 *
 * This program is distributed in the hope that it is and will be useful, but      *
 * WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
 * or FITNESS FOR A PARTICULAR PURPOSE.                                            *
 *                                                                                 *
 * See the "license.txt" file for details of the Simple Machines license.          *
 * The latest version can always be found at http://www.simplemachines.org.        *
 **********************************************************************************/

use Xmf\Request;

if (!defined('SMF')) {
    die('Hacking attempt...');
}

/*	This function has only one job: providing a display for forum statistics.
    As such, it has only one function:

    void DisplayStats()
        - gets all the statistics in order and puts them in.
        - uses the Stats template and language file. (and main sub template.)
        - requires the view_stats permission.
        - accessed from ?action=stats.

    void getDailyStats(string $condition)
        - called by DisplayStats().
        - loads the statistics on a daily basis in $context.

    void SMStats()
        - called by simplemachines.org.
        - only returns anything if stats was enabled during installation.
        - can also be accessed by the admin, to show what stats sm.org collects.
        - does not return any data directly to sm.org, instead starts a new request for security.

*/

// Display some useful/interesting board statistics.
function DisplayStats()
{
    global $txt, $scripturl, $db_prefix, $modSettings, $user_info, $context;

    if (!empty($_REQUEST['expand'])) {
        $month = (int)mb_substr($_REQUEST['expand'], 4);

        $year = (int)mb_substr($_REQUEST['expand'], 0, 4);

        if ($year > 1900 && $year < 2200 && $month >= 1 && $month <= 12) {
            $_SESSION['expanded_stats'][$year][] = $month;
        }
    } elseif (!empty($_REQUEST['collapse'])) {
        $month = (int)mb_substr($_REQUEST['collapse'], 4);

        $year = (int)mb_substr($_REQUEST['collapse'], 0, 4);

        if (!empty($_SESSION['expanded_stats'][$year])) {
            $_SESSION['expanded_stats'][$year] = array_diff($_SESSION['expanded_stats'][$year], [$month]);
        }
    }

    // Handle the XMLHttpRequest.

    if (isset($_REQUEST['xml'])) {
        // Collapsing stats only needs adjustments of the session variables.

        if (!empty($_REQUEST['collapse'])) {
            obExit(false);
        }

        $context['sub_template'] = 'stats';

        getDailyStats("YEAR(date) = $year AND MONTH(date) = $month");

        $context['monthly'][$year . sprintf('%02d', $month)]['date'] = [
            'month' => sprintf('%02d', $month),
            'year' => $year,
        ];

        return;
    }

    loadLanguage('Stats');

    loadTemplate('Stats');

    isAllowedTo('view_stats');

    // Build the link tree......

    $context['linktree'][] = [
        'url' => $scripturl . '?action=stats',
        'name' => $txt['smf_stats_1'],
    ];

    $context['page_title'] = $context['forum_name'] . ' - ' . $txt['smf_stats_1'];

    $context['show_member_list'] = allowedTo('view_mlist');

    // Get averages...

    $result = db_query(
        "
		SELECT
			SUM(posts) AS posts, SUM(topics) AS topics, SUM(registers) AS registers,
			SUM(mostOn) AS mostOn, MIN(date) AS date, SUM(hits) AS hits
		FROM {$db_prefix}log_activity",
        __FILE__,
        __LINE__
    );

    $row = $GLOBALS['xoopsDB']->fetchArray($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    // This would be the amount of time the forum has been up... in days...

    $total_days_up = ceil((time() - strtotime($row['date'])) / (60 * 60 * 24));

    $context['average_posts'] = round($row['posts'] / $total_days_up, 2);

    $context['average_topics'] = round($row['topics'] / $total_days_up, 2);

    $context['average_members'] = round($row['registers'] / $total_days_up, 2);

    $context['average_online'] = round($row['mostOn'] / $total_days_up, 2);

    $context['average_hits'] = round($row['hits'] / $total_days_up, 2);

    $context['num_hits'] = $row['hits'];

    // How many users are online now.

    $result = db_query(
        "
		SELECT COUNT(*)
		FROM {$db_prefix}log_online",
        __FILE__,
        __LINE__
    );

    [$context['users_online']] = $GLOBALS['xoopsDB']->fetchRow($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    // Statistics such as number of boards, categories, etc.

    $result = db_query(
        "
		SELECT COUNT(*)
		FROM {$db_prefix}boards AS b",
        __FILE__,
        __LINE__
    );

    [$context['num_boards']] = $GLOBALS['xoopsDB']->fetchRow($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $result = db_query(
        "
		SELECT COUNT(*)
		FROM {$db_prefix}categories AS c",
        __FILE__,
        __LINE__
    );

    [$context['num_categories']] = $GLOBALS['xoopsDB']->fetchRow($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $context['num_members'] = &$modSettings['totalMembers'];

    $context['num_posts'] = &$modSettings['totalMessages'];

    $context['num_topics'] = &$modSettings['totalTopics'];

    $context['most_members_online'] = [
        'number' => &$modSettings['mostOnline'],
        'date' => timeformat($modSettings['mostDate']),
    ];

    $context['latest_member'] = &$context['common_stats']['latest_member'];

    // Male vs. female ratio - let's calculate this only every four minutes.

    if (($context['gender'] = cache_get_data('stats_gender', 240)) === null) {
        $result = db_query(
            "
			SELECT COUNT(*) AS totalMembers, gender
			FROM {$db_prefix}members
			GROUP BY gender",
            __FILE__,
            __LINE__
        );

        $context['gender'] = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($result))) {
            // Assuming we're telling... male or female?

            if (!empty($row['gender'])) {
                $context['gender'][2 == $row['gender'] ? 'females' : 'males'] = $row['totalMembers'];
            }
        }

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        // Set these two zero if the didn't get set at all.

        if (empty($context['gender']['males'])) {
            $context['gender']['males'] = 0;
        }

        if (empty($context['gender']['females'])) {
            $context['gender']['females'] = 0;
        }

        // Try and come up with some "sensible" default states in case of a non-mixed board.

        if ($context['gender']['males'] == $context['gender']['females']) {
            $context['gender']['ratio'] = '1:1';
        } elseif (0 == $context['gender']['males']) {
            $context['gender']['ratio'] = '0:1';
        } elseif (0 == $context['gender']['females']) {
            $context['gender']['ratio'] = '1:0';
        } elseif ($context['gender']['males'] > $context['gender']['females']) {
            $context['gender']['ratio'] = round($context['gender']['males'] / $context['gender']['females'], 1) . ':1';
        } elseif ($context['gender']['females'] > $context['gender']['males']) {
            $context['gender']['ratio'] = '1:' . round($context['gender']['females'] / $context['gender']['males'], 1);
        }

        cache_put_data('stats_gender', $context['gender'], 240);
    }

    $date = strftime('%Y%m%d', forum_time(false));

    // Members online so far today.

    $result = db_query(
        "
		SELECT mostOn
		FROM {$db_prefix}log_activity
		WHERE date = $date
		LIMIT 1",
        __FILE__,
        __LINE__
    );

    [$context['online_today']] = $GLOBALS['xoopsDB']->fetchRow($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $context['online_today'] = (int)$context['online_today'];

    // Poster top 10.

    $members_result = db_query(
        "
		SELECT ID_MEMBER, realName, posts
		FROM {$db_prefix}members
		WHERE posts > 0
		ORDER BY posts DESC
		LIMIT 10",
        __FILE__,
        __LINE__
    );

    $context['top_posters'] = [];

    $max_num_posts = 1;

    while (false !== ($row_members = $GLOBALS['xoopsDB']->fetchArray($members_result))) {
        $context['top_posters'][] = [
            'name' => $row_members['realName'],
            'id' => $row_members['ID_MEMBER'],
            'num_posts' => $row_members['posts'],
            'href' => $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'],
            'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'] . '">' . $row_members['realName'] . '</a>',
        ];

        if ($max_num_posts < $row_members['posts']) {
            $max_num_posts = $row_members['posts'];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($members_result);

    foreach ($context['top_posters'] as $i => $poster) {
        $context['top_posters'][$i]['post_percent'] = round(($poster['num_posts'] * 100) / $max_num_posts);
    }

    // Board top 10.

    $boards_result = db_query(
        "
		SELECT ID_BOARD, name, numPosts
		FROM {$db_prefix}boards AS b
		WHERE $user_info[query_see_board]" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . '
		ORDER BY numPosts DESC
		LIMIT 10',
        __FILE__,
        __LINE__
    );

    $context['top_boards'] = [];

    $max_num_posts = 1;

    while (false !== ($row_board = $GLOBALS['xoopsDB']->fetchArray($boards_result))) {
        $context['top_boards'][] = [
            'id' => $row_board['ID_BOARD'],
            'name' => $row_board['name'],
            'num_posts' => $row_board['numPosts'],
            'href' => $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0',
            'link' => '<a href="' . $scripturl . '?board=' . $row_board['ID_BOARD'] . '.0">' . $row_board['name'] . '</a>',
        ];

        if ($max_num_posts < $row_board['numPosts']) {
            $max_num_posts = $row_board['numPosts'];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($boards_result);

    foreach ($context['top_boards'] as $i => $board) {
        $context['top_boards'][$i]['post_percent'] = round(($board['num_posts'] * 100) / $max_num_posts);
    }

    // Are you on a larger forum?  If so, let's try to limit the number of topics we search through.

    if ($modSettings['totalMessages'] > 100000) {
        $request = db_query(
            "
			SELECT ID_TOPIC
			FROM {$db_prefix}topics
			WHERE numReplies != 0
			ORDER BY numReplies DESC
			LIMIT 100",
            __FILE__,
            __LINE__
        );

        $topic_ids = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $topic_ids[] = $row['ID_TOPIC'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    } else {
        $topic_ids = [];
    }

    // Topic replies top 10.

    $topic_reply_result = db_query(
        "
		SELECT m.subject, t.numReplies, t.ID_BOARD, t.ID_TOPIC, b.name
		FROM ({$db_prefix}topics AS t, {$db_prefix}messages AS m, {$db_prefix}boards AS b)
		WHERE m.ID_MSG = t.ID_FIRST_MSG
			AND $user_info[query_see_board]" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . '
			AND t.ID_BOARD = b.ID_BOARD' . (!empty($topic_ids) ? '
			AND t.ID_TOPIC IN (' . implode(', ', $topic_ids) . ')' : '') . '
		ORDER BY t.numReplies DESC
		LIMIT 10',
        __FILE__,
        __LINE__
    );

    $context['top_topics_replies'] = [];

    $max_num_replies = 1;

    while (false !== ($row_topic_reply = $GLOBALS['xoopsDB']->fetchArray($topic_reply_result))) {
        censorText($row_topic_reply['subject']);

        $context['top_topics_replies'][] = [
            'id' => $row_topic_reply['ID_TOPIC'],
            'board' => [
                'id' => $row_topic_reply['ID_BOARD'],
                'name' => $row_topic_reply['name'],
                'href' => $scripturl . '?board=' . $row_topic_reply['ID_BOARD'] . '.0',
                'link' => '<a href="' . $scripturl . '?board=' . $row_topic_reply['ID_BOARD'] . '.0">' . $row_topic_reply['name'] . '</a>',
            ],
            'subject' => $row_topic_reply['subject'],
            'num_replies' => $row_topic_reply['numReplies'],
            'href' => $scripturl . '?topic=' . $row_topic_reply['ID_TOPIC'] . '.0',
            'link' => '<a href="' . $scripturl . '?topic=' . $row_topic_reply['ID_TOPIC'] . '.0">' . $row_topic_reply['subject'] . '</a>',
        ];

        if ($max_num_replies < $row_topic_reply['numReplies']) {
            $max_num_replies = $row_topic_reply['numReplies'];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($topic_reply_result);

    foreach ($context['top_topics_replies'] as $i => $topic) {
        $context['top_topics_replies'][$i]['post_percent'] = round(($topic['num_replies'] * 100) / $max_num_replies);
    }

    // Large forums may need a bit more prodding...

    if ($modSettings['totalMessages'] > 100000) {
        $request = db_query(
            "
			SELECT ID_TOPIC
			FROM {$db_prefix}topics
			WHERE numViews != 0
			ORDER BY numViews DESC
			LIMIT 100",
            __FILE__,
            __LINE__
        );

        $topic_ids = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $topic_ids[] = $row['ID_TOPIC'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    } else {
        $topic_ids = [];
    }

    // Topic views top 10.

    $topic_view_result = db_query(
        "
		SELECT m.subject, t.numViews, t.ID_BOARD, t.ID_TOPIC, b.name
		FROM ({$db_prefix}topics AS t, {$db_prefix}messages AS m, {$db_prefix}boards AS b)
		WHERE m.ID_MSG = t.ID_FIRST_MSG
			AND $user_info[query_see_board]" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			AND b.ID_BOARD != $modSettings[recycle_board]" : '') . '
			AND t.ID_BOARD = b.ID_BOARD' . (!empty($topic_ids) ? '
			AND t.ID_TOPIC IN (' . implode(', ', $topic_ids) . ')' : '') . '
		ORDER BY t.numViews DESC
		LIMIT 10',
        __FILE__,
        __LINE__
    );

    $context['top_topics_views'] = [];

    $max_num_views = 1;

    while (false !== ($row_topic_views = $GLOBALS['xoopsDB']->fetchArray($topic_view_result))) {
        censorText($row_topic_views['subject']);

        $context['top_topics_views'][] = [
            'id' => $row_topic_views['ID_TOPIC'],
            'board' => [
                'id' => $row_topic_views['ID_BOARD'],
                'name' => $row_topic_views['name'],
                'href' => $scripturl . '?board=' . $row_topic_views['ID_BOARD'] . '.0',
                'link' => '<a href="' . $scripturl . '?board=' . $row_topic_views['ID_BOARD'] . '.0">' . $row_topic_views['name'] . '</a>',
            ],
            'subject' => $row_topic_views['subject'],
            'num_views' => $row_topic_views['numViews'],
            'href' => $scripturl . '?topic=' . $row_topic_views['ID_TOPIC'] . '.0',
            'link' => '<a href="' . $scripturl . '?topic=' . $row_topic_views['ID_TOPIC'] . '.0">' . $row_topic_views['subject'] . '</a>',
        ];

        if ($max_num_views < $row_topic_views['numViews']) {
            $max_num_views = $row_topic_views['numViews'];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($topic_view_result);

    foreach ($context['top_topics_views'] as $i => $topic) {
        $context['top_topics_views'][$i]['post_percent'] = round(($topic['num_views'] * 100) / $max_num_views);
    }

    // Try to cache this when possible, because it's a little unavoidably slow.

    if (null === ($members = cache_get_data('stats_top_starters', 360))) {
        $request = db_query(
            "
			SELECT ID_MEMBER_STARTED, COUNT(*) AS hits
			FROM {$db_prefix}topics" . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? "
			WHERE ID_BOARD != $modSettings[recycle_board]" : '') . '
			GROUP BY ID_MEMBER_STARTED
			ORDER BY hits DESC
			LIMIT 20',
            __FILE__,
            __LINE__
        );

        $members = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $members[$row['ID_MEMBER_STARTED']] = $row['hits'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        cache_put_data('stats_top_starters', $members, 360);
    }

    if (empty($members)) {
        $members = [0 => 0];
    }

    // Topic poster top 10.

    $members_result = db_query(
        "
		SELECT ID_MEMBER, realName
		FROM {$db_prefix}members
		WHERE ID_MEMBER IN (" . implode(', ', array_keys($members)) . ")
		GROUP BY ID_MEMBER
		ORDER BY FIND_IN_SET(ID_MEMBER, '" . implode(',', array_keys($members)) . "')
		LIMIT 10",
        __FILE__,
        __LINE__
    );

    $context['top_starters'] = [];

    $max_num_topics = 1;

    while (false !== ($row_members = $GLOBALS['xoopsDB']->fetchArray($members_result))) {
        $context['top_starters'][] = [
            'name' => $row_members['realName'],
            'id' => $row_members['ID_MEMBER'],
            'num_topics' => $members[$row_members['ID_MEMBER']],
            'href' => $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'],
            'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'] . '">' . $row_members['realName'] . '</a>',
        ];

        if ($max_num_topics < $members[$row_members['ID_MEMBER']]) {
            $max_num_topics = $members[$row_members['ID_MEMBER']];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($members_result);

    foreach ($context['top_starters'] as $i => $topic) {
        $context['top_starters'][$i]['post_percent'] = round(($topic['num_topics'] * 100) / $max_num_topics);
    }

    // Time online top 10.

    // !!!SLOW This query is sorta slow.  Should we just add a key? (or would that be bad in the long run?)

    $temp = cache_get_data('stats_total_time_members', 600);

    $members_result = db_query(
        "
		SELECT ID_MEMBER, realName, totalTimeLoggedIn
		FROM {$db_prefix}members" . (!empty($temp) ? '
		WHERE ID_MEMBER IN (' . implode(', ', $temp) . ')' : '') . '
		ORDER BY totalTimeLoggedIn DESC
		LIMIT 20',
        __FILE__,
        __LINE__
    );

    $context['top_time_online'] = [];

    $temp2 = [];

    $max_time_online = 1;

    while (false !== ($row_members = $GLOBALS['xoopsDB']->fetchArray($members_result))) {
        $temp2[] = (int)$row_members['ID_MEMBER'];

        if (count($context['top_time_online']) >= 10) {
            continue;
        }

        // Figure out the days, hours and minutes.

        $timeDays = floor($row_members['totalTimeLoggedIn'] / 86400);

        $timeHours = floor(($row_members['totalTimeLoggedIn'] % 86400) / 3600);

        // Figure out which things to show... (days, hours, minutes, etc.)

        $timelogged = '';

        if ($timeDays > 0) {
            $timelogged .= $timeDays . $txt['totalTimeLogged5'];
        }

        if ($timeHours > 0) {
            $timelogged .= $timeHours . $txt['totalTimeLogged6'];
        }

        $timelogged .= floor(($row_members['totalTimeLoggedIn'] % 3600) / 60) . $txt['totalTimeLogged7'];

        $context['top_time_online'][] = [
            'id' => $row_members['ID_MEMBER'],
            'name' => $row_members['realName'],
            'time_online' => $timelogged,
            'seconds_online' => $row_members['totalTimeLoggedIn'],
            'href' => $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'],
            'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row_members['ID_MEMBER'] . '">' . $row_members['realName'] . '</a>',
        ];

        if ($max_time_online < $row_members['totalTimeLoggedIn']) {
            $max_time_online = $row_members['totalTimeLoggedIn'];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($members_result);

    foreach ($context['top_time_online'] as $i => $member) {
        $context['top_time_online'][$i]['time_percent'] = round(($member['seconds_online'] * 100) / $max_time_online);
    }

    // Cache the ones we found for a bit, just so we don't have to look again.

    if ($temp !== $temp2) {
        cache_put_data('stats_total_time_members', $temp2, 480);
    }

    // Activity by month.

    $months_result = db_query(
        "
		SELECT
			YEAR(date) AS stats_year, MONTH(date) AS stats_month, SUM(hits) AS hits, SUM(registers) AS registers, SUM(topics) AS topics, SUM(posts) AS posts, MAX(mostOn) AS mostOn, COUNT(*) AS numDays
		FROM {$db_prefix}log_activity
		GROUP BY stats_year, stats_month",
        __FILE__,
        __LINE__
    );

    $context['monthly'] = [];

    while (false !== ($row_months = $GLOBALS['xoopsDB']->fetchArray($months_result))) {
        $ID_MONTH = $row_months['stats_year'] . sprintf('%02d', $row_months['stats_month']);

        $expanded = !empty($_SESSION['expanded_stats'][$row_months['stats_year']]) && in_array($row_months['stats_month'], $_SESSION['expanded_stats'][$row_months['stats_year']], true);

        $context['monthly'][$ID_MONTH] = [
            'id' => $ID_MONTH,
            'date' => [
                'month' => sprintf('%02d', $row_months['stats_month']),
                'year' => $row_months['stats_year'],
            ],
            'href' => $scripturl . '?action=stats;' . ($expanded ? 'collapse' : 'expand') . '=' . $ID_MONTH . '#' . $ID_MONTH,
            'link' => '<a href="' . $scripturl . '?action=stats;' . ($expanded ? 'collapse' : 'expand') . '=' . $ID_MONTH . '#' . $ID_MONTH . '">' . $txt['months'][$row_months['stats_month']] . ' ' . $row_months['stats_year'] . '</a>',
            'month' => $txt['months'][$row_months['stats_month']],
            'year' => $row_months['stats_year'],
            'new_topics' => $row_months['topics'],
            'new_posts' => $row_months['posts'],
            'new_members' => $row_months['registers'],
            'most_members_online' => $row_months['mostOn'],
            'hits' => $row_months['hits'],
            'num_days' => $row_months['numDays'],
            'days' => [],
            'expanded' => $expanded,
        ];
    }

    // This gets rid of the filesort on the query ;).

    krsort($context['monthly']);

    if (empty($_SESSION['expanded_stats'])) {
        return;
    }

    $condition = [];

    foreach ($_SESSION['expanded_stats'] as $year => $months) {
        if (!empty($months)) {
            $condition[] = "YEAR(date) = $year AND MONTH(date) IN (" . implode(', ', $months) . ')';
        }
    }

    // No daily stats to even look at?

    if (empty($condition)) {
        return;
    }

    getDailyStats(implode(' OR ', $condition));
}

function getDailyStats($condition)
{
    global $context, $db_prefix;

    // Activity by day.

    $days_result = db_query(
        "
		SELECT YEAR(date) AS stats_year, MONTH(date) AS stats_month, DAYOFMONTH(date) AS stats_day, topics, posts, registers, mostOn, hits
		FROM {$db_prefix}log_activity
		WHERE $condition
		ORDER BY stats_day ASC",
        __FILE__,
        __LINE__
    );

    while (false !== ($row_days = $GLOBALS['xoopsDB']->fetchArray($days_result))) {
        $context['monthly'][$row_days['stats_year'] . sprintf('%02d', $row_days['stats_month'])]['days'][] = [
            'day' => sprintf('%02d', $row_days['stats_day']),
            'month' => sprintf('%02d', $row_days['stats_month']),
            'year' => $row_days['stats_year'],
            'new_topics' => $row_days['topics'],
            'new_posts' => $row_days['posts'],
            'new_members' => $row_days['registers'],
            'most_members_online' => $row_days['mostOn'],
            'hits' => $row_days['hits'],
        ];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($days_result);
}

// This is the function which returns stats to simple machines.org IF enabled!
// See http://www.simplemachines.org/about/stats.php for more info.
function SMStats()
{
    global $modSettings, $user_info, $forum_version;

    // First, is it disabled?

    if (empty($modSettings['allow_sm_stats'])) {
        die();
    }

    // Are we saying who we are, and are we right? (OR an admin)

    if (!$user_info['is_admin'] && (!isset($_GET['sid']) || $_GET['sid'] != $modSettings['allow_sm_stats'])) {
        die();
    }

    // Verify the referer...

    if (!$user_info['is_admin'] && (!isset(Request::getString('HTTP_REFERER', '', 'SERVER')) || '746cb59a1a0d5cf4bd240e5a67c73085' != md5(Request::getString('HTTP_REFERER', '', 'SERVER')))) {
        die();
    }

    // Get the actual stats.

    $stats_to_send = [
        'UID' => $modSettings['allow_sm_stats'],
        'time_added' => time(),
        'members' => $modSettings['totalMembers'],
        'messages' => $modSettings['totalMessages'],
        'topics' => $modSettings['totalTopics'],
        'boards' => 0,
        'php_version' => PHP_VERSION,
        'mysql_version' => '',
        'smf_version' => $forum_version,
        'smfd_version' => $modSettings['smfVersion'],
    ];

    $request = db_query(
        '
		SELECT VERSION()',
        __FILE__,
        __LINE__
    );

    [$stats_to_send['mysql_version']] = $GLOBALS['xoopsDB']->fetchRow($request);

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    // Encode all the data, for security.

    foreach ($stats_to_send as $k => $v) {
        $stats_to_send[$k] = urlencode($k) . '=' . urlencode($v);
    }

    // Turn this into the query string!

    $stats_to_send = implode('&', $stats_to_send);

    // If we're an admin, just plonk them out.

    if ($user_info['is_admin']) {
        echo $stats_to_send;
    } else {
        // Connect to the collection script.

        $fp = @fsockopen('www.simplemachines.org', 80, $errno, $errstr);

        if ($fp) {
            $length = mb_strlen($stats_to_send);

            $out = "POST /smf/stats/collect_stats.php HTTP/1.1\r\n";

            $out .= "Host: www.simplemachines.org\r\n";

            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";

            $out .= "Content-Length: $length\r\n\r\n";

            $out .= "$stats_to_send\r\n";

            $out .= "Connection: Close\r\n\r\n";

            fwrite($fp, $out);

            fclose($fp);
        }
    }

    // Die.

    die('OK');
}