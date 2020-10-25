<?php

/**********************************************************************************
 * Memberlist.php                                                                  *
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
if (!defined('SMF')) {
    die('Hacking attempt...');
}

/*	This file contains the functions for displaying and searching in the
    members list.  It does so with these functions:

    void MemberList()
        - shows a list of registered members.
        - if a subaction is not specified, lists all registered members.
        - allows searching for members with the 'search' sub action.
        - calls MLAll or MLSearch depending on the sub action.
        - uses the Memberlist template with the main sub template.
        - requires the view_mlist permission.
        - is accessed via ?action=mlist.

    void MLAll()
        - used to display all members on a page by page basis with sorting.
        - called from MemberList().
        - can be passed a sort parameter, to order the display of members.
        - calls printMemberListRows to retrieve the results of the query.

    void MLSearch()
        - used to search for members or display search results.
        - called by MemberList().
        - if variable 'search' is empty displays search dialog box, using the
          search sub template.
        - calls printMemberListRows to retrieve the results of the query.

    void printMemberListRows(resource request)
        - retrieves results of the request passed to it
        - puts results of request into the context for the sub template.
*/

// Show a listing of the registered members.
function Memberlist()
{
    global $scripturl, $txt, $modSettings, $context, $settings;

    // Make sure they can view the memberlist.

    isAllowedTo('view_mlist');

    loadTemplate('Memberlist');

    $context['listing_by'] = !empty($_GET['sa']) ? $_GET['sa'] : 'all';

    // $subActions array format:

    // 'subaction' => array('label', 'function', 'is_selected')

    $subActions = [
        'all' => [&$txt[303], 'MLAll', 'all' == $context['listing_by']],
        'search' => [&$txt['mlist_search'], 'MLSearch', 'search' == $context['listing_by']],
    ];

    // Set up the sort links.

    $context['sort_links'] = [];

    foreach ($subActions as $act => $text) {
        $context['sort_links'][] = [
            'label' => $text[0],
            'action' => $act,
            'selected' => $text[2],
        ];
    }

    $context['num_members'] = $modSettings['totalMembers'];

    // Set up the columns...

    $context['columns'] = [
        'isOnline' => [
            'label' => $txt['online8'],
            'width' => '20',
        ],
        'realName' => [
            'label' => $txt[35],
        ],
        'emailAddress' => [
            'label' => $txt[307],
            'width' => '25',
        ],
        'websiteUrl' => [
            'label' => $txt[96],
            'width' => '25',
        ],
        'ICQ' => [
            'label' => $txt[513],
            'width' => '25',
        ],
        'AIM' => [
            'label' => $txt[603],
            'width' => '25',
        ],
        'YIM' => [
            'label' => $txt[604],
            'width' => '25',
        ],
        'MSN' => [
            'label' => $txt['MSN'],
            'width' => '25',
        ],
        'ID_GROUP' => [
            'label' => $txt[87],
        ],
        'registered' => [
            'label' => $txt[233],
        ],
        'posts' => [
            'label' => $txt[21],
            'width' => '115',
            'colspan' => '2',
        ],
    ];

    $context['linktree'][] = [
        'url' => $scripturl . '?action=mlist',
        'name' => &$txt[332],
    ];

    $context['can_send_pm'] = allowedTo('pm_send');

    // Jump to the sub action.

    if (isset($subActions[$context['listing_by']])) {
        $subActions[$context['listing_by']][1]();
    } else {
        $subActions['all'][1]();
    }
}

// List all members, page by page.
function MLAll()
{
    global $txt, $scripturl, $db_prefix, $user_info;

    global $modSettings, $context, $func;

    // The chunk size for the cached index.

    $cache_step_size = 500;

    // Only use caching if:

    // 1. there are at least 2k members,

    // 2. the default sorting method (realName) is being used,

    // 3. the page shown is high enough to make a DB filesort unprofitable.

    $use_cache = $modSettings['totalMembers'] > 2000 && (!isset($_REQUEST['sort']) || 'realName' === $_REQUEST['sort']) && isset($_REQUEST['start']) && $_REQUEST['start'] > $cache_step_size;

    if ($use_cache) {
        // Maybe there's something cached already.

        if (!empty($modSettings['memberlist_cache'])) {
            $memberlist_cache = @unserialize($modSettings['memberlist_cache']);
        }

        // Only update the cache if something changed or no cache existed yet.

        if (empty($memberlist_cache) || empty($modSettings['memberlist_updated']) || $memberlist_cache['last_update'] < $modSettings['memberlist_updated']) {
            $request = db_query(
                "
				SELECT realName
				FROM {$db_prefix}members
				WHERE is_activated = 1
				ORDER BY realName",
                __FILE__,
                __LINE__
            );

            $memberlist_cache = [
                'last_update' => time(),
                'num_members' => $GLOBALS['xoopsDB']->getRowsNum($request),
                'index' => [],
            ];

            for ($i = 0, $n = $GLOBALS['xoopsDB']->getRowsNum($request); $i < $n; $i += $cache_step_size) {
                mysql_data_seek($request, $i);

                [$memberlist_cache['index'][$i]] = $GLOBALS['xoopsDB']->fetchRow($request);
            }

            mysql_data_seek($request, $memberlist_cache['num_members'] - 1);

            [$memberlist_cache['index'][$i]] = $GLOBALS['xoopsDB']->fetchRow($request);

            $GLOBALS['xoopsDB']->freeRecordSet($request);

            // Now we've got the cache...store it.

            updateSettings(['memberlist_cache' => addslashes(serialize($memberlist_cache))]);
        }

        $context['num_members'] = $memberlist_cache['num_members'];
    } // Without cache we need an extra query to get the amount of members.

    else {
        $request = db_query(
            "
			SELECT COUNT(*)
			FROM {$db_prefix}members
			WHERE is_activated = 1",
            __FILE__,
            __LINE__
        );

        [$context['num_members']] = $GLOBALS['xoopsDB']->fetchRow($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }

    // Set defaults for sort (realName) and start. (0)

    if (!isset($_REQUEST['sort']) || !isset($context['columns'][$_REQUEST['sort']])) {
        $_REQUEST['sort'] = 'realName';
    }

    if (!is_numeric($_REQUEST['start'])) {
        if (0 === preg_match('~^[^\'\\\\/]~' . ($context['utf8'] ? 'u' : ''), $func['strtolower']($_REQUEST['start']), $match)) {
            fatal_error('Hacker?', false);
        }

        $_REQUEST['start'] = $match[0];

        $request = db_query(
            "
			SELECT COUNT(*)
			FROM {$db_prefix}members
			WHERE LOWER(SUBSTRING(realName, 1, 1)) < '$_REQUEST[start]'
				AND is_activated = 1",
            __FILE__,
            __LINE__
        );

        [$_REQUEST['start']] = $GLOBALS['xoopsDB']->fetchRow($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }

    $context['letter_links'] = '';

    for ($i = 97; $i < 123; $i++) {
        $context['letter_links'] .= '<a href="' . $scripturl . '?action=mlist;sa=all;start=' . chr($i) . '#letter' . chr($i) . '">' . mb_strtoupper(chr($i)) . '</a> ';
    }

    // Sort out the column information.

    foreach ($context['columns'] as $col => $dummy) {
        $context['columns'][$col]['href'] = $scripturl . '?action=mlist;sort=' . $col . ';start=0';

        if (!isset($_REQUEST['desc']) && $col == $_REQUEST['sort']) {
            $context['columns'][$col]['href'] .= ';desc';
        }

        $context['columns'][$col]['link'] = '<a href="' . $context['columns'][$col]['href'] . '">' . $context['columns'][$col]['label'] . '</a>';

        $context['columns'][$col]['selected'] = $_REQUEST['sort'] == $col;
    }

    $context['sort_by'] = $_REQUEST['sort'];

    $context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

    // Construct the page index.

    $context['page_index'] = constructPageIndex($scripturl . '?action=mlist;sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $_REQUEST['start'], $context['num_members'], $modSettings['defaultMaxMembers']);

    // Send the data to the template.

    $context['start'] = $_REQUEST['start'] + 1;

    $context['end'] = min($_REQUEST['start'] + $modSettings['defaultMaxMembers'], $context['num_members']);

    $context['page_title'] = $txt[308] . ' ' . $context['start'] . ' ' . $txt[311] . ' ' . $context['end'];

    $context['linktree'][] = [
        'url' => $scripturl . '?action=mlist;sort=' . $_REQUEST['sort'] . ';start=' . $_REQUEST['start'],
        'name' => &$context['page_title'],
        'extra_after' => ' (' . $txt[309] . ' ' . $context['num_members'] . ' ' . $txt[310] . ')',
    ];

    // List out the different sorting methods...

    $sort_methods = [
        'isOnline' => [
            'down' => '(ISNULL(lo.logTime)' . (!allowedTo('moderate_forum') ? ' OR NOT mem.showOnline' : '') . ') ASC, realName ASC',
            'up' => '(ISNULL(lo.logTime)' . (!allowedTo('moderate_forum') ? ' OR NOT mem.showOnline' : '') . ') DESC, realName DESC',
        ],
        'realName' => [
            'down' => 'mem.realName ASC',
            'up' => 'mem.realName DESC',
        ],
        'emailAddress' => [
            'down' => (allowedTo('moderate_forum') || empty($modSettings['allow_hideEmail'])) ? 'mem.emailAddress ASC' : 'mem.hideEmail ASC, mem.emailAddress ASC',
            'up' => (allowedTo('moderate_forum') || empty($modSettings['allow_hideEmail'])) ? 'mem.emailAddress DESC' : 'mem.hideEmail DESC, mem.emailAddress DESC',
        ],
        'websiteUrl' => [
            'down' => 'LENGTH(mem.websiteURL) > 0 DESC, ISNULL(mem.websiteURL) ASC, mem.websiteURL ASC',
            'up' => 'LENGTH(mem.websiteURL) > 0 ASC, ISNULL(mem.websiteURL) DESC, mem.websiteURL DESC',
        ],
        'ICQ' => [
            'down' => 'LENGTH(mem.ICQ) > 0 DESC, ISNULL(mem.ICQ) OR mem.ICQ = 0 ASC, mem.ICQ ASC',
            'up' => 'LENGTH(mem.ICQ) > 0 ASC, ISNULL(mem.ICQ) OR mem.ICQ = 0 DESC, mem.ICQ DESC',
        ],
        'AIM' => [
            'down' => 'LENGTH(mem.AIM) > 0 DESC, ISNULL(mem.AIM) ASC, mem.AIM ASC',
            'up' => 'LENGTH(mem.AIM) > 0 ASC, ISNULL(mem.AIM) DESC, mem.AIM DESC',
        ],
        'YIM' => [
            'down' => 'LENGTH(mem.YIM) > 0 DESC, ISNULL(mem.YIM) ASC, mem.YIM ASC',
            'up' => 'LENGTH(mem.YIM) > 0 ASC, ISNULL(mem.YIM) DESC, mem.YIM DESC',
        ],
        'MSN' => [
            'down' => 'LENGTH(mem.MSN) > 0 DESC, ISNULL(mem.MSN) ASC, mem.MSN ASC',
            'up' => 'LENGTH(mem.MSN) > 0 ASC, ISNULL(mem.MSN) DESC, mem.MSN DESC',
        ],
        'registered' => [
            'down' => 'mem.dateRegistered ASC',
            'up' => 'mem.dateRegistered DESC',
        ],
        'ID_GROUP' => [
            'down' => 'ISNULL(mg.groupName) ASC, mg.groupName ASC',
            'up' => 'ISNULL(mg.groupName) DESC, mg.groupName DESC',
        ],
        'posts' => [
            'down' => 'mem.posts DESC',
            'up' => 'mem.posts ASC',
        ],
    ];

    $limit = $_REQUEST['start'];

    // Using cache allows to narrow down the list to be retrieved.

    if ($use_cache && 'realName' === $_REQUEST['sort'] && !isset($_REQUEST['desc'])) {
        $first_offset = $_REQUEST['start'] - ($_REQUEST['start'] % $cache_step_size);

        $second_offset = ceil(($_REQUEST['start'] + $modSettings['defaultMaxMembers']) / $cache_step_size) * $cache_step_size;

        $where = "mem.realName BETWEEN '" . addslashes($memberlist_cache['index'][$first_offset]) . "' AND '" . addslashes($memberlist_cache['index'][$second_offset]) . "'";

        $limit -= $first_offset;
    } // Reverse sorting is a bit more complicated...

    elseif ($use_cache && 'realName' === $_REQUEST['sort']) {
        $first_offset = floor(($memberlist_cache['num_members'] - $modSettings['defaultMaxMembers'] - $_REQUEST['start']) / $cache_step_size) * $cache_step_size;

        if ($first_offset < 0) {
            $first_offset = 0;
        }

        $second_offset = ceil(($memberlist_cache['num_members'] - $_REQUEST['start']) / $cache_step_size) * $cache_step_size;

        $where = "mem.realName BETWEEN '" . addslashes($memberlist_cache['index'][$first_offset]) . "' AND '" . addslashes($memberlist_cache['index'][$second_offset]) . "'";

        $limit = $second_offset - ($memberlist_cache['num_members'] - $_REQUEST['start']) - ($second_offset > $memberlist_cache['num_members'] ? $cache_step_size - ($memberlist_cache['num_members'] % $cache_step_size) : 0);
    }

    // Select the members from the database.

    $request = db_query(
        "
		SELECT mem.ID_MEMBER
		FROM {$db_prefix}members AS mem" . ('isOnline' === $_REQUEST['sort'] ? "
			LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)" : '') . ('ID_GROUP' === $_REQUEST['sort'] ? "
			LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))" : '') . '
		WHERE mem.is_activated = 1' . (empty($where) ? '' : "
			AND $where") . '
		ORDER BY ' . $sort_methods[$_REQUEST['sort']][$context['sort_direction']] . "
		LIMIT $limit, $modSettings[defaultMaxMembers]",
        __FILE__,
        __LINE__
    );

    printMemberListRows($request);

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    // Add anchors at the start of each letter.

    if ('realName' == $_REQUEST['sort']) {
        $last_letter = '';

        foreach ($context['members'] as $i => $dummy) {
            $this_letter = $func['strtolower']($func['substr']($context['members'][$i]['name'], 0, 1));

            if ($this_letter != $last_letter && 1 === preg_match('~[a-z]~', $this_letter)) {
                $context['members'][$i]['sort_letter'] = htmlspecialchars($this_letter, ENT_QUOTES | ENT_HTML5);

                $last_letter = $this_letter;
            }
        }
    }
}

// Search for members...
function MLSearch()
{
    global $txt, $scripturl, $db_prefix, $context, $user_info, $modSettings;

    $context['page_title'] = $txt['mlist_search'];

    // They're searching..

    if (isset($_REQUEST['search']) && isset($_REQUEST['fields'])) {
        $_POST['search'] = trim($_GET['search'] ?? $_POST['search']);

        $_POST['fields'] = isset($_GET['fields']) ? explode(',', $_GET['fields']) : $_POST['fields'];

        $context['old_search'] = $_REQUEST['search'];

        $context['old_search_value'] = urlencode($_REQUEST['search']);

        // No fields?  Use default...

        if (empty($_POST['fields'])) {
            $_POST['fields'] = ['name'];
        }

        // Search for a name?

        if (in_array('name', $_POST['fields'], true)) {
            $fields = ['memberName', 'realName'];
        } else {
            $fields = [];
        }

        // Search for messengers...

        if (in_array('messenger', $_POST['fields'], true) && (!$user_info['is_guest'] || empty($modSettings['guest_hideContacts']))) {
            $fields += [3 => 'MSN', 'AIM', 'ICQ', 'YIM'];
        }

        // Search for websites.

        if (in_array('website', $_POST['fields'], true)) {
            $fields += [7 => 'websiteTitle', 'websiteUrl'];
        }

        // Search for groups.

        if (in_array('group', $_POST['fields'], true)) {
            $fields += [9 => 'IFNULL(groupName, \'\')'];
        }

        // Search for an email address?

        if (in_array('email', $_POST['fields'], true)) {
            $fields += [2 => allowedTo('moderate_forum') ? 'emailAddress' : '(hideEmail = 0 AND emailAddress'];

            $condition = allowedTo('moderate_forum') ? '' : ')';
        } else {
            $condition = '';
        }

        $query = '' == $_POST['search'] ? "= ''" : "LIKE '%" . strtr($_POST['search'], ['_' => '\\_', '%' => '\\%', '*' => '%']) . "%'";

        $request = db_query(
            "
			SELECT COUNT(*)
			FROM {$db_prefix}members AS mem
				LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))
			WHERE " . implode(" $query OR ", $fields) . " $query$condition
				AND is_activated = 1",
            __FILE__,
            __LINE__
        );

        [$numResults] = $GLOBALS['xoopsDB']->fetchRow($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        $context['page_index'] = constructPageIndex($scripturl . '?action=mlist;sa=search;search=' . $_POST['search'] . ';fields=' . implode(',', $_POST['fields']), $_REQUEST['start'], $numResults, $modSettings['defaultMaxMembers']);

        // Find the members from the database.

        // !!!SLOW This query is slow.

        $request = db_query(
            "
			SELECT mem.ID_MEMBER
			FROM {$db_prefix}members AS mem
				LEFT JOIN {$db_prefix}log_online AS lo ON (lo.ID_MEMBER = mem.ID_MEMBER)
				LEFT JOIN {$db_prefix}membergroups AS mg ON (mg.ID_GROUP = IF(mem.ID_GROUP = 0, mem.ID_POST_GROUP, mem.ID_GROUP))
			WHERE " . implode(" $query OR ", $fields) . " $query$condition
				AND is_activated = 1
			LIMIT $_REQUEST[start], $modSettings[defaultMaxMembers]",
            __FILE__,
            __LINE__
        );

        printMemberListRows($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    } else {
        $context['sub_template'] = 'search';

        $context['old_search'] = isset($_REQUEST['search']) ? htmlspecialchars($_REQUEST['search'], ENT_QUOTES | ENT_HTML5) : '';
    }

    $context['linktree'][] = [
        'url' => $scripturl . '?action=mlist;sa=search',
        'name' => &$context['page_title'],
    ];
}

function printMemberListRows($request)
{
    global $scripturl, $txt, $db_prefix, $user_info, $modSettings;

    global $context, $settings, $memberContext;

    // Get the most posts.

    $result = db_query(
        "
		SELECT MAX(posts)
		FROM {$db_prefix}members",
        __FILE__,
        __LINE__
    );

    [$MOST_POSTS] = $GLOBALS['xoopsDB']->fetchRow($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    // Avoid division by zero...

    if (0 == $MOST_POSTS) {
        $MOST_POSTS = 1;
    }

    $members = [];

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        $members[] = $row['ID_MEMBER'];
    }

    // Load all the members for display.

    loadMemberData($members);

    $context['members'] = [];

    foreach ($members as $member) {
        if (!loadMemberContext($member)) {
            continue;
        }

        $context['members'][$member] = $memberContext[$member];

        $context['members'][$member]['post_percent'] = round(($context['members'][$member]['real_posts'] * 100) / $MOST_POSTS);

        $context['members'][$member]['registered_date'] = strftime('%Y-%m-%d', $context['members'][$member]['registered_timestamp']);
    }
}
