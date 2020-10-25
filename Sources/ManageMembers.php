<?php

/**********************************************************************************
 * ManageMembers.php                                                               *
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

/* Show a list of members or a selection of members.

    void ViewMembers()
        - the main entrance point for the Manage Members screen.
        - called by ?action=viewmembers.
        - requires the moderate_forum permission.
        - loads the ManageMembers template and ManageMembers language file.
        - calls a function based on the given sub-action.

    void ViewMemberlist()
        - shows a list of members.
        - called by ?action=viewmembers;sa=all or ?action=viewmembers;sa=query.
        - requires the moderate_forum permission.
        - uses the view_members sub template of the ManageMembers template.
        - allows sorting on several columns.
        - handles deletion of selected members.
        - handles the search query sent by ?action=viewmembers;sa=search.

    void SearchMembers()
        - search the member list, using one or more criteria.
        - called by ?action=viewmembers;sa=search.
        - requires the moderate_forum permission.
        - uses the search_members sub template of the ManageMembers template.
        - form is submitted to action=viewmembers;sa=query.

    void MembersAwaitingActivation()
        - show a list of members awaiting approval or activation.
        - called by ?action=viewmembers;sa=browse;type=approve or
          ?action=viewmembers;sa=browse;type=activate.
        - requires the moderate_forum permission.
        - uses the admin_browse sub template of the ManageMembers template.
        - allows instant approval or activation of (a selection of) members.
        - list can be sorted on different columns.
        - form submits to ?action=viewmembers;sa=approve.

    void AdminApprove()
        - handles the approval, rejection, activation or deletion of members.
        - called by ?action=viewmembers;sa=approve.
        - requires the moderate_forum permission.
        - redirects to ?action=viewmembers;sa=browse with the same parameters
          as the calling page.

    int jeffsdatediff(int old)
        - nifty function to calculate the number of days ago a given date was.
        - requires a unix timestamp as input, returns an integer.
        - in honour of Jeff Lewis, the original creator of...this function.
        - the returned number of days is based on the forum time.
*/

function ViewMembers()
{
    global $txt, $scripturl, $context, $modSettings, $db_prefix;

    $subActions = [
        'all' => ['ViewMemberlist', 'moderate_forum'],
        'approve' => ['AdminApprove', 'moderate_forum'],
        'browse' => ['MembersAwaitingActivation', 'moderate_forum'],
        'search' => ['SearchMembers', 'moderate_forum'],
        'query' => ['ViewMemberlist', 'moderate_forum'],
    ];

    // Default to sub action 'index' or 'settings' depending on permissions.

    $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'all';

    // We know the sub action, now we know what you're allowed to do.

    isAllowedTo($subActions[$_REQUEST['sa']][1]);

    // Administration bar, I choose you!

    adminIndex('view_members');

    // Load the essentials.

    loadLanguage('ManageMembers');

    loadTemplate('ManageMembers');

    // Get counts on every type of activation - for sections and filtering alike.

    $request = db_query(
        "
		SELECT COUNT(*) AS totalMembers, is_activated
		FROM {$db_prefix}members
		WHERE is_activated != 1
		GROUP BY is_activated",
        __FILE__,
        __LINE__
    );

    $context['activation_numbers'] = [];

    $context['awaiting_activation'] = 0;

    $context['awaiting_approval'] = 0;

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        $context['activation_numbers'][$row['is_activated']] = $row['totalMembers'];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    foreach ($context['activation_numbers'] as $activation_type => $total_members) {
        if (in_array($activation_type, [0, 2], true)) {
            $context['awaiting_activation'] += $total_members;
        } elseif (in_array($activation_type, [3, 4, 5], true)) {
            $context['awaiting_approval'] += $total_members;
        }
    }

    // For the page header... do we show activation?

    $context['show_activate'] = (!empty($modSettings['registration_method']) && 1 == $modSettings['registration_method']) || !empty($context['awaiting_activation']);

    // What about approval?

    $context['show_approve'] = (!empty($modSettings['registration_method']) && 2 == $modSettings['registration_method']) || !empty($context['awaiting_approval']);

    // Setup the admin tabs.

    $context['admin_tabs'] = [
        'title' => $txt[9],
        'help' => 'view_members',
        'description' => $txt[11],
        'tabs' => [],
    ];

    if (allowedTo('moderate_forum')) {
        $context['admin_tabs']['tabs'] = [
            'viewmembers' => [
                'title' => $txt[303],
                'description' => $txt[11],
                'href' => $scripturl . '?action=viewmembers;sa=all',
                'is_selected' => 'all' == $_REQUEST['sa'],
            ],
            'search' => [
                'title' => $txt['mlist_search'],
                'description' => $txt[11],
                'href' => $scripturl . '?action=viewmembers;sa=search',
                'is_selected' => 'search' == $_REQUEST['sa'] || 'query' == $_REQUEST['sa'],
            ],
            'approve' => [
                'title' => sprintf($txt['admin_browse_awaiting_approval'], $context['awaiting_approval']),
                'description' => $txt['admin_browse_approve_desc'],
                'href' => $scripturl . '?action=viewmembers;sa=browse;type=approve',
                'is_selected' => false,
            ],
            'activate' => [
                'title' => sprintf($txt['admin_browse_awaiting_activate'], $context['awaiting_activation']),
                'description' => $txt['admin_browse_activate_desc'],
                'href' => $scripturl . '?action=viewmembers;sa=browse;type=activate',
                'is_selected' => false,
                'is_last' => true,
            ],
        ];
    }

    // Sort out the tabs for the ones which may not exist!

    if (!$context['show_activate']) {
        $context['admin_tabs']['tabs']['approve']['is_last'] = true;

        unset($context['admin_tabs']['tabs']['activate']);
    }

    if (!$context['show_approve']) {
        if (!$context['show_activate']) {
            $context['admin_tabs']['tabs']['search']['is_last'] = true;
        }

        unset($context['admin_tabs']['tabs']['approve']);
    }

    $subActions[$_REQUEST['sa']][0]();
}

// View all members.
function ViewMemberlist()
{
    global $txt, $scripturl, $db_prefix, $context, $modSettings, $sourcedir;

    // Set the current sub action.

    $context['sub_action'] = $_REQUEST['sa'];

    // Are we performing a delete?

    if (isset($_POST['delete_members']) && !empty($_POST['delete']) && allowedTo('profile_remove_any')) {
        checkSession();

        // Clean the input.

        foreach ($_POST['delete'] as $key => $value) {
            $_POST['delete'][$key] = (int)$value;
        }

        // Delete all the selected members.

        require_once $sourcedir . '/Subs-Members.php';

        deleteMembers($_POST['delete']);
    }

    // Check input after a member search has been submitted.

    if ('query' == $context['sub_action'] && empty($_REQUEST['params'])) {
        // Retrieving the membergroups and postgroups.

        $context['membergroups'] = [
            [
                'id' => 0,
                'name' => $txt['membergroups_members'],
                'can_be_additional' => false,
            ],
        ];

        $context['postgroups'] = [];

        $request = db_query(
            "
			SELECT ID_GROUP, groupName, minPosts
			FROM {$db_prefix}membergroups
			WHERE ID_GROUP != 3
			ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName",
            __FILE__,
            __LINE__
        );

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            if (-1 == $row['minPosts']) {
                $context['membergroups'][] = [
                    'id' => $row['ID_GROUP'],
                    'name' => $row['groupName'],
                    'can_be_additional' => true,
                ];
            } else {
                $context['postgroups'][] = [
                    'id' => $row['ID_GROUP'],
                    'name' => $row['groupName'],
                ];
            }
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        // Some data about the form fields and how they are linked to the database.

        $params = [
            'mem_id' => [
                'db_fields' => ['ID_MEMBER'],
                'type' => 'int',
                'range' => true,
            ],
            'age' => [
                'db_fields' => ['birthdate'],
                'type' => 'age',
                'range' => true,
            ],
            'posts' => [
                'db_fields' => ['posts'],
                'type' => 'int',
                'range' => true,
            ],
            'reg_date' => [
                'db_fields' => ['dateRegistered'],
                'type' => 'date',
                'range' => true,
            ],
            'last_online' => [
                'db_fields' => ['lastLogin'],
                'type' => 'date',
                'range' => true,
            ],
            'gender' => [
                'db_fields' => ['gender'],
                'type' => 'checkbox',
                'values' => ['0', '1', '2'],
            ],
            'activated' => [
                'db_fields' => ['IF(is_activated IN (1, 11), 1, 0)'],
                'type' => 'checkbox',
                'values' => ['0', '1'],
            ],
            'membername' => [
                'db_fields' => ['memberName', 'realName'],
                'type' => 'string',
            ],
            'email' => [
                'db_fields' => ['emailAddress'],
                'type' => 'string',
            ],
            'website' => [
                'db_fields' => ['websiteTitle', 'websiteUrl'],
                'type' => 'string',
            ],
            'location' => [
                'db_fields' => ['location'],
                'type' => 'string',
            ],
            'ip' => [
                'db_fields' => ['memberIP'],
                'type' => 'string',
            ],
            'messenger' => [
                'db_fields' => ['ICQ', 'AIM', 'YIM', 'MSN'],
                'type' => 'string',
            ],
        ];

        $range_trans = [
            '--' => '<',
            '-' => '<=',
            '=' => '=',
            '+' => '>=',
            '++' => '>',
        ];

        // !!! Validate a little more.

        // Loop through every field of the form.

        $query_parts = [];

        foreach ($params as $param_name => $param_info) {
            // Not filled in?

            if (!isset($_POST[$param_name]) || '' == $_POST[$param_name]) {
                continue;
            }

            // Make sure numeric values are really numeric.

            if (in_array($param_info['type'], ['int', 'age'], true)) {
                $_POST[$param_name] = (int)$_POST[$param_name];
            } // Date values have to match the specified format.

            elseif ('date' == $param_info['type']) {
                // Check if this date format is valid.

                if (0 == preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $_POST[$param_name])) {
                    continue;
                }

                $_POST[$param_name] = strtotime($_POST[$param_name]);
            }

            // Those values that are in some kind of range (<, <=, =, >=, >).

            if (!empty($param_info['range'])) {
                // Default to '=', just in case...

                if (empty($range_trans[$_POST['types'][$param_name]])) {
                    $_POST['types'][$param_name] = '=';
                }

                // Handle special case 'age'.

                if ('age' == $param_info['type']) {
                    // All people that were born between $lowerlimit and $upperlimit are currently the specified age.

                    $datearray = getdate(forum_time());

                    $upperlimit = sprintf('%04d-%02d-%02d', $datearray['year'] - $_POST[$param_name], $datearray['mon'], $datearray['mday']);

                    $lowerlimit = sprintf('%04d-%02d-%02d', $datearray['year'] - $_POST[$param_name] - 1, $datearray['mon'], $datearray['mday']);

                    if (in_array($_POST['types'][$param_name], ['-', '--', '='], true)) {
                        $query_parts[] = "{$param_info['db_fields'][0]} > '" . ('--' == $_POST['types'][$param_name] ? $upperlimit : $lowerlimit) . "'";
                    }

                    if (in_array($_POST['types'][$param_name], ['+', '++', '='], true)) {
                        $query_parts[] = "{$param_info['db_fields'][0]} <= '" . ('++' == $_POST['types'][$param_name] ? $lowerlimit : $upperlimit) . "'";

                        // Make sure that members that didn't set their birth year are not queried.

                        $query_parts[] = "{$param_info['db_fields'][0]} > '0000-12-31'";
                    }
                } elseif ('date' == $param_info['type'] && '=' == $_POST['types'][$param_name]) {
                    $query_parts[] = $param_info['db_fields'][0] . ' > ' . $_POST[$param_name] . ' AND ' . $param_info['db_fields'][0] . ' < ' . ($_POST[$param_name] + 86400);
                } else {
                    $query_parts[] = $param_info['db_fields'][0] . ' ' . $range_trans[$_POST['types'][$param_name]] . ' ' . $_POST[$param_name];
                }
            } // Checkboxes.

            elseif ('checkbox' == $param_info['type']) {
                // Each checkbox or no checkbox at all is checked -> ignore.

                if (!is_array($_POST[$param_name]) || 0 == count($_POST[$param_name]) || count($_POST[$param_name]) == count($param_info['values'])) {
                    continue;
                }

                $query_parts[] = "{$param_info['db_fields'][0]} IN ('" . implode("', '", $_POST[$param_name]) . "')";
            } else {
                // Replace the wildcard characters ('*' and '?') into MySQL ones.

                $_POST[$param_name] = mb_strtolower(addslashes(strtr($_POST[$param_name], ['%' => '\%', '_' => '\_', '*' => '%', '?' => '_'])));

                $query_parts[] = '(' . implode(" LIKE '%{$_POST[$param_name]}%' OR ", $param_info['db_fields']) . " LIKE '%{$_POST[$param_name]}%')";
            }
        }

        // Set up the membergroup query part.

        $mg_query_parts = [];

        // Primary membergroups, but only if at least was was not selected.

        if (!empty($_POST['membergroups'][1]) && count($context['membergroups']) != count($_POST['membergroups'][1])) {
            $mg_query_parts[] = 'ID_GROUP IN (' . implode(', ', $_POST['membergroups'][1]) . ')';
        }

        // Additional membergroups (these are only relevant if not all primary groups where selected!).

        if (!empty($_POST['membergroups'][2]) && (empty($_POST['membergroups'][1]) || count($context['membergroups']) != count($_POST['membergroups'][1]))) {
            foreach ($_POST['membergroups'][2] as $mg) {
                $mg_query_parts[] = 'FIND_IN_SET(' . (int)$mg . ', additionalGroups)';
            }
        }

        // Combine the one or two membergroup parts into one query part linked with an OR.

        if (!empty($mg_query_parts)) {
            $query_parts[] = '(' . implode(' OR ', $mg_query_parts) . ')';
        }

        // Get all selected post count related membergroups.

        if (!empty($_POST['postgroups']) && count($_POST['postgroups']) != count($context['postgroups'])) {
            $query_parts[] = 'ID_POST_GROUP IN (' . implode(', ', $_POST['postgroups']) . ')';
        }

        // Construct the where part of the query.

        $where = empty($query_parts) ? '1' : implode(
            '
			AND ',
            $query_parts
        );
    }

    // If the query information was already packed in the URL, decode it.

    // !!! Change this.

    elseif ('query' == $context['sub_action']) {
        $where = base64_decode(strtr($_REQUEST['params'], [' ' => '+']), true);
    }

    // Construct the additional URL part with the query info in it.

    $context['params_url'] = 'query' == $context['sub_action'] ? ';sa=query;params=' . base64_encode($where) : '';

    // Get the title and sub template ready..

    $context['page_title'] = $txt[9];

    $context['sub_template'] = 'view_members';

    // Determine whether to show the 'delete members' checkboxes.

    $context['can_delete_members'] = allowedTo('profile_remove_any');

    // All the columns they have to pick from...

    $context['columns'] = [
        'ID_MEMBER' => ['label' => $txt['member_id']],
        'memberName' => ['label' => $txt[35]],
        'realName' => ['label' => $txt['display_name']],
        'emailAddress' => ['label' => $txt['email_address']],
        'memberIP' => ['label' => $txt['ip_address']],
        'lastLogin' => ['label' => $txt['viewmembers_online']],
        'posts' => ['label' => $txt[26]],
    ];

    // Default sort column to 'memberName' if the current one is unknown or not set.

    if (!isset($_REQUEST['sort']) || !isset($context['columns'][$_REQUEST['sort']])) {
        $_REQUEST['sort'] = 'memberName';
    }

    // Provide extra information about each column - the link, whether it's selected, etc.

    foreach ($context['columns'] as $col => $dummy) {
        $context['columns'][$col]['href'] = $scripturl . '?action=viewmembers' . $context['params_url'] . ';sort=' . $col . ';start=0';

        if (!isset($_REQUEST['desc']) && $col == $_REQUEST['sort']) {
            $context['columns'][$col]['href'] .= ';desc';
        }

        $context['columns'][$col]['link'] = '<a href="' . $context['columns'][$col]['href'] . '">' . $context['columns'][$col]['label'] . '</a>';

        $context['columns'][$col]['selected'] = $_REQUEST['sort'] == $col;
    }

    $context['sort_by'] = $_REQUEST['sort'];

    $context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

    // Calculate the number of results.

    if (empty($where) or '1' == $where) {
        $num_members = $modSettings['totalMembers'];
    } else {
        $request = db_query(
            "
			SELECT COUNT(*)
			FROM {$db_prefix}members
			WHERE $where",
            __FILE__,
            __LINE__
        );

        [$num_members] = $GLOBALS['xoopsDB']->fetchRow($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }

    // Construct the page links.

    $context['page_index'] = constructPageIndex($scripturl . '?action=viewmembers' . $context['params_url'] . ';sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $_REQUEST['start'], $num_members, $modSettings['defaultMaxMembers']);

    $context['start'] = (int)$_REQUEST['start'];

    $request = db_query(
        "
		SELECT ID_MEMBER, memberName, realName, emailAddress, memberIP, lastLogin, posts, is_activated
		FROM {$db_prefix}members" . ('query' == $context['sub_action'] && !empty($where) ? "
		WHERE $where" : '') . "
		ORDER BY $_REQUEST[sort]" . (!isset($_REQUEST['desc']) ? '' : ' DESC') . "
		LIMIT $context[start], $modSettings[defaultMaxMembers]",
        __FILE__,
        __LINE__
    );

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        // Calculate number of days since last online.

        if (empty($row['lastLogin'])) {
            $difference = $txt['never'];
        } else {
            // Today or some time ago?

            $difference = jeffsdatediff($row['lastLogin']);

            if (empty($difference)) {
                $difference = $txt['viewmembers_today'];
            } elseif (1 == $difference) {
                $difference .= ' ' . $txt['viewmembers_day_ago'];
            } else {
                $difference .= ' ' . $txt['viewmembers_days_ago'];
            }
        }

        // Show it in italics if they're not activated...

        if ($row['is_activated'] % 10 != 1) {
            $difference = '<i title="' . $txt['not_activated'] . '">' . $difference . '</i>';
        }

        $context['members'][] = [
            'id' => $row['ID_MEMBER'],
            'username' => $row['memberName'],
            'name' => $row['realName'],
            'email' => $row['emailAddress'],
            'ip' => $row['memberIP'],
            'last_active' => $difference,
            'is_activated' => $row['is_activated'] % 10 == 1,
            'posts' => $row['posts'],
            'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
            'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['realName'] . '</a>',
        ];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);
}

// Search the member list, using one or more criteria.
function SearchMembers()
{
    global $db_prefix, $context, $txt;

    // Get a list of all the membergroups and postgroups that can be selected.

    $context['membergroups'] = [
        [
            'id' => 0,
            'name' => $txt['membergroups_members'],
            'can_be_additional' => false,
        ],
    ];

    $context['postgroups'] = [];

    $request = db_query(
        "
		SELECT ID_GROUP, groupName, minPosts
		FROM {$db_prefix}membergroups
		WHERE ID_GROUP != 3
		ORDER BY minPosts, IF(ID_GROUP < 4, ID_GROUP, 4), groupName",
        __FILE__,
        __LINE__
    );

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        if (-1 == $row['minPosts']) {
            $context['membergroups'][] = [
                'id' => $row['ID_GROUP'],
                'name' => $row['groupName'],
                'can_be_additional' => true,
            ];
        } else {
            $context['postgroups'][] = [
                'id' => $row['ID_GROUP'],
                'name' => $row['groupName'],
            ];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    $context['page_title'] = $txt[9];

    $context['sub_template'] = 'search_members';
}

// List all members who are awaiting approval / activation
function MembersAwaitingActivation()
{
    global $txt, $context, $db_prefix, $scripturl, $modSettings;

    // Not a lot here!

    $context['page_title'] = $txt[9];

    $context['sub_template'] = 'admin_browse';

    $context['browse_type'] = $_REQUEST['type'] ?? (!empty($modSettings['registration_method']) && 1 == $modSettings['registration_method'] ? 'activate' : 'approve');

    if (isset($context['admin_tabs']['tabs'][$context['browse_type']])) {
        $context['admin_tabs']['tabs'][$context['browse_type']]['is_selected'] = true;
    }

    // Allowed filters are those we can have, in theory.

    $context['allowed_filters'] = 'approve' == $context['browse_type'] ? [3, 4, 5] : [0, 2];

    $context['current_filter'] = isset($_REQUEST['filter']) && in_array($_REQUEST['filter'], $context['allowed_filters'], true) && !empty($context['activation_numbers'][$_REQUEST['filter']]) ? (int)$_REQUEST['filter'] : -1;

    // Sort out the different sub areas that we can actually filter by.

    $context['available_filters'] = [];

    foreach ($context['activation_numbers'] as $type => $amount) {
        // We have some of these...

        if (in_array($type, $context['allowed_filters'], true) && $amount > 0) {
            $context['available_filters'][] = [
                'type' => $type,
                'amount' => $amount,
                'desc' => $txt['admin_browse_filter_type_' . $type] ?? '?',
                'selected' => $type == $context['current_filter'],
            ];
        }
    }

    // If the filter was not sent, set it to whatever has people in it!

    if (-1 == $context['current_filter'] && !empty($context['available_filters'][0]['amount'])) {
        $context['current_filter'] = $context['available_filters'][0]['type'];
    }

    // This little variable is used to determine if we should flag where we are looking.

    if ((0 != $context['current_filter'] && 3 != $context['current_filter']) && 1 == count($context['available_filters'])) {
        $context['show_filter'] = true;
    }

    // The columns that can be sorted.

    $context['columns'] = [
        'ID_MEMBER' => ['label' => $txt['admin_browse_id']],
        'memberName' => ['label' => $txt['admin_browse_username']],
        'emailAddress' => ['label' => $txt['admin_browse_email']],
        'memberIP' => ['label' => $txt['admin_browse_ip']],
        'dateRegistered' => ['label' => $txt['admin_browse_registered']],
    ];

    // Default sort column to 'dateRegistered' if the current one is unknown or not set.

    if (!isset($_REQUEST['sort']) || !isset($context['columns'][$_REQUEST['sort']])) {
        $_REQUEST['sort'] = 'dateRegistered';
    }

    // Provide extra information about each column - the link, whether it's selected, etc.

    foreach ($context['columns'] as $col => $dummy) {
        $context['columns'][$col]['href'] = $scripturl . '?action=viewmembers;sa=browse;type=' . $context['browse_type'] . ';sort=' . $col . ';start=0';

        if (!isset($_REQUEST['desc']) && $col == $_REQUEST['sort']) {
            $context['columns'][$col]['href'] .= ';desc';
        }

        $context['columns'][$col]['link'] = '<a href="' . $context['columns'][$col]['href'] . '">' . $context['columns'][$col]['label'] . '</a>';

        $context['columns'][$col]['selected'] = $_REQUEST['sort'] == $col;
    }

    $context['sort_by'] = $_REQUEST['sort'];

    $context['sort_direction'] = !isset($_REQUEST['desc']) ? 'down' : 'up';

    // Calculate the number of results.

    $request = db_query(
        "
		SELECT COUNT(*)
		FROM {$db_prefix}members
		WHERE is_activated = $context[current_filter]",
        __FILE__,
        __LINE__
    );

    [$context['num_members']] = $GLOBALS['xoopsDB']->fetchRow($request);

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    // Construct the page links.

    $context['page_index'] = constructPageIndex($scripturl . '?action=viewmembers;sa=browse;type=' . $context['browse_type'] . ';sort=' . $_REQUEST['sort'] . (isset($_REQUEST['desc']) ? ';desc' : ''), $_REQUEST['start'], $context['num_members'], $modSettings['defaultMaxMembers']);

    $context['start'] = (int)$_REQUEST['start'];

    // Determine which actions we should allow on this page.

    if ('approve' == $context['browse_type']) {
        // If we are approving deleted accounts we have a slightly different list... actually a mirror ;)

        if (4 == $context['current_filter']) {
            $context['allowed_actions'] = [
                'reject' => $txt['admin_browse_w_approve_deletion'],
                'ok' => $txt['admin_browse_w_reject'],
            ];
        } else {
            $context['allowed_actions'] = [
                'ok' => $txt['admin_browse_w_approve'],
                'okemail' => $txt['admin_browse_w_approve'] . ' ' . $txt['admin_browse_w_email'],
                'require_activation' => $txt['admin_browse_w_approve_require_activate'],
                'reject' => $txt['admin_browse_w_reject'],
                'rejectemail' => $txt['admin_browse_w_reject'] . ' ' . $txt['admin_browse_w_email'],
            ];
        }
    } elseif ('activate' == $context['browse_type']) {
        $context['allowed_actions'] = [
            'ok' => $txt['admin_browse_w_activate'],
            'okemail' => $txt['admin_browse_w_activate'] . ' ' . $txt['admin_browse_w_email'],
            'delete' => $txt['admin_browse_w_delete'],
            'deleteemail' => $txt['admin_browse_w_delete'] . ' ' . $txt['admin_browse_w_email'],
            'remind' => $txt['admin_browse_w_remind'] . ' ' . $txt['admin_browse_w_email'],
        ];
    }

    $request = db_query(
        "
		SELECT ID_MEMBER, memberName, emailAddress, memberIP, dateRegistered
		FROM {$db_prefix}members
		WHERE is_activated = $context[current_filter]
		ORDER BY $_REQUEST[sort]" . (!isset($_REQUEST['desc']) ? '' : ' DESC') . "
		LIMIT $context[start], $modSettings[defaultMaxMembers]",
        __FILE__,
        __LINE__
    );

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        $context['members'][] = [
            'id' => $row['ID_MEMBER'],
            'username' => $row['memberName'],
            'href' => $scripturl . '?action=profile;u=' . $row['ID_MEMBER'],
            'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['ID_MEMBER'] . '">' . $row['memberName'] . '</a>',
            'email' => $row['emailAddress'],
            'ip' => $row['memberIP'],
            'dateRegistered' => timeformat($row['dateRegistered']),
        ];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);
}

// Do the approve/activate/delete stuff
function AdminApprove()
{
    global $txt, $context, $db_prefix, $scripturl, $modSettings, $sourcedir, $language, $user_info;

    require_once $sourcedir . '/Subs-Post.php';

    // We also need to the login languages here - for emails.

    loadLanguage('Login');

    // Sort out where we are going...

    $browse_type = $_REQUEST['type'] ?? (!empty($modSettings['registration_method']) && 1 == $modSettings['registration_method'] ? 'activate' : 'approve');

    $current_filter = (int)$_REQUEST['orig_filter'];

    // If we are applying a filter do just that - then redirect.

    if (isset($_REQUEST['filter']) && $_REQUEST['filter'] != $_REQUEST['orig_filter']) {
        redirectexit('action=viewmembers;sa=browse;type=' . $_REQUEST['type'] . ';sort=' . $_REQUEST['sort'] . ';filter=' . $_REQUEST['filter'] . ';start=' . $_REQUEST['start']);
    }

    // Nothing to do?

    if (!isset($_POST['todoAction']) && !isset($_POST['time_passed'])) {
        redirectexit('action=viewmembers;sa=browse;type=' . $_REQUEST['type'] . ';sort=' . $_REQUEST['sort'] . ';filter=' . $current_filter . ';start=' . $_REQUEST['start']);
    }

    // Are we dealing with members who have been waiting for > set amount of time?

    if (isset($_POST['time_passed'])) {
        $timeBefore = time() - 86400 * (int)$_POST['time_passed'];

        $condition = "
			AND dateRegistered < $timeBefore";
    } // Coming from checkboxes - validate the members passed through to us.

    else {
        $members = [];

        foreach ($_POST['todoAction'] as $id) {
            $members[] = (int)$id;
        }

        $condition = '
			AND ID_MEMBER IN (' . implode(', ', $members) . ')';
    }

    // Get information on each of the members, things that are important to us, like email address...

    $request = db_query(
        "
		SELECT ID_MEMBER, memberName, realName, emailAddress, validation_code, lngfile
		FROM {$db_prefix}members
		WHERE is_activated = $current_filter$condition
		ORDER BY lngfile",
        __FILE__,
        __LINE__
    );

    $member_count = $GLOBALS['xoopsDB']->getRowsNum($request);

    // If no results then just return!

    if (0 == $member_count) {
        redirectexit('action=viewmembers;sa=browse;type=' . $_REQUEST['type'] . ';sort=' . $_REQUEST['sort'] . ';filter=' . $current_filter . ';start=' . $_REQUEST['start']);
    }

    $member_info = [];

    $members = [];

    // Fill the info array.

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        $members[] = $row['ID_MEMBER'];

        $member_info[] = [
            'id' => $row['ID_MEMBER'],
            'username' => $row['memberName'],
            'name' => $row['realName'],
            'email' => $row['emailAddress'],
            'language' => empty($row['lngfile']) || empty($modSettings['userLanguage']) ? $language : $row['lngfile'],
            'code' => $row['validation_code'],
        ];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    // Are we activating or approving the members?

    if ('ok' == $_POST['todo'] || 'okemail' == $_POST['todo']) {
        // Approve/activate this member.

        db_query(
            "
			UPDATE {$db_prefix}members
			SET validation_code = '', is_activated = 1
			WHERE is_activated = $current_filter$condition
			LIMIT $member_count",
            __FILE__,
            __LINE__
        );

        // Do we have to let the integration code know about the activations?

        if (isset($modSettings['integrate_activate']) && function_exists($modSettings['integrate_activate'])) {
            foreach ($member_info as $member) {
                call_user_func($modSettings['integrate_activate'], $member['username']);
            }
        }

        // Check for email.

        if ('okemail' == $_POST['todo']) {
            foreach ($member_info as $member) {
                if (empty($current_language) || $current_language != $member['language']) {
                    $current_language = loadLanguage('index', $member['language'], false);

                    loadLanguage('ManageMembers', $member['language'], false);
                }

                sendmail(
                    $member['email'],
                    $txt['register_subject'],
                    "$txt[hello_guest] $member[name]!\n\n" . "$txt[admin_approve_accept_desc] $txt[719] $member[name]\n\n" . "$txt[701]\n" . "$scripturl?action=profile\n\n" . $txt[130]
                );
            }
        }
    } // Maybe we're sending it off for activation?

    elseif ('require_activation' == $_POST['todo']) {
        // We have to do this for each member I'm afraid.

        foreach ($member_info as $member) {
            // Generate a random activation code.

            $validation_code = mb_substr(preg_replace('/\W/', '', md5(mt_rand())), 0, 10);

            // Set these members for activation - I know this includes two ID_MEMBER checks but it's safer than bodging $condition ;).

            db_query(
                "
				UPDATE {$db_prefix}members
				SET validation_code = '$validation_code', is_activated = 0
				WHERE is_activated = $current_filter
					$condition
					AND ID_MEMBER = $member[id]
				LIMIT 1",
                __FILE__,
                __LINE__
            );

            if (empty($current_language) || $current_language != $member['language']) {
                $current_language = loadLanguage('index', $member['language'], false);

                loadLanguage('ManageMembers', $member['language'], false);
            }

            // Send out the activation email.

            sendmail(
                $member['email'],
                $txt['register_subject'],
                "$txt[hello_guest] $member[name]!\n\n" . "$txt[admin_approve_require_activation] $txt[admin_approve_remind_desc2]\n" . "$scripturl?action=activate;u=$member[id];code=$validation_code\n\n" . $txt[130]
            );
        }
    } // Are we rejecting them?

    elseif ('reject' == $_POST['todo'] || 'rejectemail' == $_POST['todo']) {
        require_once $sourcedir . '/Subs-Members.php';

        deleteMembers($members);

        // Send email telling them they aren't welcome?

        if ('rejectemail' == $_POST['todo']) {
            foreach ($member_info as $member) {
                if (empty($current_language) || $current_language != $member['language']) {
                    $current_language = loadLanguage('ManageMembers', $member['language'], false);
                }

                sendmail(
                    $member['email'],
                    $txt['admin_approve_reject'],
                    "$member[name],\n\n" . "$txt[admin_approve_reject_desc]\n\n" . $txt[130]
                );
            }
        }
    } // A simple delete?

    elseif ('delete' == $_POST['todo'] || 'deleteemail' == $_POST['todo']) {
        require_once $sourcedir . '/Subs-Members.php';

        deleteMembers($members);

        // Send email telling them they aren't welcome?

        if ('deleteemail' == $_POST['todo']) {
            foreach ($member_info as $member) {
                if (empty($current_language) || $current_language != $member['language']) {
                    $current_language = loadLanguage('ManageMembers', $member['language'], false);
                }

                sendmail(
                    $member['email'],
                    $txt['admin_approve_delete'],
                    "$member[name],\n\n" . "$txt[admin_approve_delete_desc]\n\n" . $txt[130]
                );
            }
        }
    } // Remind them to activate their account?

    elseif ('remind' == $_POST['todo']) {
        foreach ($member_info as $member) {
            if (empty($current_language) || $current_language != $member['language']) {
                $current_language = loadLanguage('ManageMembers', $member['language'], false);
            }

            sendmail(
                $member['email'],
                $txt['admin_approve_remind'],
                "$member[name],\n\n" . "$txt[admin_approve_remind_desc] $context[forum_name].\n\n$txt[admin_approve_remind_desc2]\n\n" . "$scripturl?action=activate;u=$member[id];code=$member[code]\n\n" . $txt[130]
            );
        }
    }

    // Back to the user's language!

    if (isset($current_language) && $current_language != $user_info['language']) {
        loadLanguage('index');

        loadLanguage('ManageMembers');
    }

    // Although updateStats *may* catch this, best to do it manually just incase (Doesn't always sort out unapprovedMembers).

    if (in_array($current_filter, [3, 4], true)) {
        updateSettings(['unapprovedMembers' => ($modSettings['unapprovedMembers'] > $member_count ? $modSettings['unapprovedMembers'] - $member_count : 0)]);
    }

    // Update the member's stats. (but, we know the member didn't change their name.)

    updateStats('member', false);

    // If they haven't been deleted, update the post group statistics on them...

    if (!in_array($_POST['todo'], ['delete', 'deleteemail', 'reject', 'rejectemail', 'remind'], true)) {
        updateStats('postgroups', 'ID_MEMBER IN (' . implode(', ', $members) . ')');
    }

    redirectexit('action=viewmembers;sa=browse;type=' . $_REQUEST['type'] . ';sort=' . $_REQUEST['sort'] . ';filter=' . $current_filter . ';start=' . $_REQUEST['start']);
}

function jeffsdatediff($old)
{
    // Get the current time as the user would see it...

    $forumTime = forum_time();

    // Calculate the seconds that have passed since midnight.

    $sinceMidnight = date('H', $forumTime) * 60 * 60 + date('i', $forumTime) * 60 + date('s', $forumTime);

    // Take the difference between the two times.

    $dis = time() - $old;

    // Before midnight?

    if ($dis < $sinceMidnight) {
        return 0;
    }  

    $dis -= $sinceMidnight;

    // Divide out the seconds in a day to get the number of days.

    return ceil($dis / (24 * 60 * 60));
}
