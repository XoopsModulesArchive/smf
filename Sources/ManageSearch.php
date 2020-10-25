<?php

/**********************************************************************************
 * ManageSearch.php                                                                *
 * **********************************************************************************
 * SMF: Simple Machines Forum                                                      *
 * Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                    *
 * =============================================================================== *
 * Software Version:           SMF 1.1.2                                           *
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

/* The admin screen to change the search settings.

    void ManageSearch()
        - main entry point for the admin search settings screen.
        - called by ?action=managesearch.
        - requires the admin_forum permission.
        - loads the ManageSearch template.
        - loads the Search language file.
        - calls a function based on the given sub-action.
        - defaults to sub-action 'settings'.

    void EditSearchSettings()
        - edit some general settings related to the search function.
        - called by ?action=managesearch;sa=settings.
        - requires the admin_forum permission.
        - uses the 'modify_settings' sub template of the ManageSearch template.

    void EditWeights()
        - edit the relative weight of the search factors.
        - called by ?action=managesearch;sa=weights.
        - requires the admin_forum permission.
        - uses the 'modify_weights' sub template of the ManageSearch template.

    void EditSearchMethod()
        - edit the search method and search index used.
        - called by ?action=managesearch;sa=method.
        - requires the admin_forum permission.
        - uses the 'select_search_method' sub template of the ManageSearch
          template.
        - allows to create and delete a fulltext index on the messages table.
        - allows to delete a custom index (that CreateMessageIndex() created).
        - calculates the size of the current search indexes in use.

    void CreateMessageIndex()
        - create a custom search index for the messages table.
        - called by ?action=managesearch;sa=createmsgindex.
        - linked from the EditSearchMethod screen.
        - requires the admin_forum permission.
        - uses the 'create_index', 'create_index_progress', and
          'create_index_done' sub templates of the ManageSearch template.
        - depending on the size of the message table, the process is divided
          in steps.
*/

function ManageSearch()
{
    global $context, $txt, $scripturl;

    isAllowedTo('admin_forum');

    adminIndex('manage_search');

    loadLanguage('Search');

    loadTemplate('ManageSearch');

    $subActions = [
        'settings' => 'EditSearchSettings',
        'weights' => 'EditWeights',
        'method' => 'EditSearchMethod',
        'createfulltext' => 'EditSearchMethod',
        'removecustom' => 'EditSearchMethod',
        'removefulltext' => 'EditSearchMethod',
        'createmsgindex' => 'CreateMessageIndex',
    ];

    // Default the sub-action to 'edit search settings'.

    $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'settings';

    $context['sub_action'] = $_REQUEST['sa'];

    // Create the tabs for the template.

    $context['admin_tabs'] = [
        'title' => &$txt['manage_search'],
        'help' => 'search',
        'description' => $txt['search_settings_desc'],
        'tabs' => [
            'weights' => [
                'title' => $txt['search_weights'],
                'description' => $txt['search_weights_desc'],
                'href' => $scripturl . '?action=managesearch;sa=weights',
            ],
            'method' => [
                'title' => $txt['search_method'],
                'description' => $txt['search_method_desc'],
                'href' => $scripturl . '?action=managesearch;sa=method',
            ],
            'settings' => [
                'title' => $txt['settings'],
                'description' => $txt['search_settings_desc'],
                'href' => $scripturl . '?action=managesearch;sa=settings',
                'is_last' => true,
            ],
        ],
    ];

    // Make sure the tab they are using has is_selected set.

    if (isset($context['admin_tabs']['tabs'][$_REQUEST['sa']])) {
        $context['admin_tabs']['tabs'][$_REQUEST['sa']]['is_selected'] = true;
    }

    // Call the right function for this sub-acton.

    $subActions[$_REQUEST['sa']]();
}

function EditSearchSettings()
{
    global $txt, $context, $sourcedir;

    $context['page_title'] = $txt['search_settings_title'];

    $context['sub_template'] = 'modify_settings';

    // Including a file needed for inline permissions.

    require_once $sourcedir . '/ManagePermissions.php';

    // A form was submitted.

    if (isset($_POST['save'])) {
        checkSession();

        updateSettings(
            [
                'simpleSearch' => isset($_POST['simpleSearch']) ? '1' : '0',
                'search_results_per_page' => (int)$_POST['search_results_per_page'],
                'search_max_results' => (int)$_POST['search_max_results'],
            ]
        );

        // Save the permissions.

        save_inline_permissions(['search_posts']);
    }

    // Initialize permissions.

    init_inline_permissions(['search_posts']);
}

function EditWeights()
{
    global $txt, $context, $modSettings;

    $context['page_title'] = $txt['search_weights_title'];

    $context['sub_template'] = 'modify_weights';

    $factors = [
        'search_weight_frequency',
        'search_weight_age',
        'search_weight_length',
        'search_weight_subject',
        'search_weight_first_message',
        'search_weight_sticky',
    ];

    // A form was submitted.

    if (isset($_POST['save'])) {
        checkSession();

        $changes = [];

        foreach ($factors as $factor) {
            $changes[$factor] = (int)$_POST[$factor];
        }

        updateSettings($changes);
    }

    $context['relative_weights'] = ['total' => 0];

    foreach ($factors as $factor) {
        $context['relative_weights']['total'] += $modSettings[$factor] ?? 0;
    }

    foreach ($factors as $factor) {
        $context['relative_weights'][$factor] = round(100 * ($modSettings[$factor] ?? 0) / $context['relative_weights']['total'], 1);
    }
}

function EditSearchMethod()
{
    global $txt, $context, $modSettings, $db_prefix;

    $context['admin_tabs']['tabs']['method']['is_selected'] = true;

    $context['page_title'] = $txt['search_method_title'];

    $context['sub_template'] = 'select_search_method';

    // Detect whether a fulltext index is set.

    $request = db_query(
        "
		SHOW INDEX
		FROM {$db_prefix}messages",
        false,
        false
    );

    $context['fulltext_index'] = '';

    if (false !== $request || 0 != $GLOBALS['xoopsDB']->getRowsNum($request)) {
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            if ('body' == $row['Column_name'] && (isset($row['Index_type']) && 'FULLTEXT' == $row['Index_type'] || isset($row['Comment']) && 'FULLTEXT' == $row['Comment'])) {
                $context['fulltext_index'][] = $row['Key_name'];
            }
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        if (is_array($context['fulltext_index'])) {
            $context['fulltext_index'] = array_unique($context['fulltext_index']);
        }
    }

    $request = db_query(
        "
		SHOW COLUMNS
		FROM {$db_prefix}messages",
        false,
        false
    );

    if (false !== $request) {
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            if ('body' == $row['Field'] && 'mediumtext' == $row['Type']) {
                $context['cannot_create_fulltext'] = true;
            }
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }

    if (0 !== preg_match('~^`(.+?)`\.(.+?)$~', $db_prefix, $match)) {
        $request = db_query(
            '
			SHOW TABLE STATUS
			FROM `' . strtr($match[1], ['`' => '']) . "`
			LIKE '" . str_replace('_', '\_', $match[2]) . "messages'",
            false,
            false
        );
    } else {
        $request = db_query(
            "
			SHOW TABLE STATUS
			LIKE '" . str_replace('_', '\_', $db_prefix) . "messages'",
            false,
            false
        );
    }

    if (false !== $request) {
        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            if ((isset($row['Type']) && 'myisam' != mb_strtolower($row['Type'])) || (isset($row['Engine']) && 'myisam' != mb_strtolower($row['Engine']))) {
                $context['cannot_create_fulltext'] = true;
            }
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }

    if (!empty($_REQUEST['sa']) && 'createfulltext' == $_REQUEST['sa']) {
        checkSession('get');

        // Make sure it's gone before creating it.

        db_query(
            "
			ALTER TABLE {$db_prefix}messages
			DROP INDEX body",
            false,
            false
        );

        db_query(
            "
			ALTER TABLE {$db_prefix}messages
			ADD FULLTEXT body (body)",
            __FILE__,
            __LINE__
        );

        $context['fulltext_index'] = 'body';
    } elseif (!empty($_REQUEST['sa']) && 'removefulltext' == $_REQUEST['sa'] && !empty($context['fulltext_index'])) {
        checkSession('get');

        db_query(
            "
			ALTER TABLE {$db_prefix}messages
			DROP INDEX " . implode(
                ',
			DROP INDEX ',
                $context['fulltext_index']
            ),
            __FILE__,
            __LINE__
        );

        $context['fulltext_index'] = '';

        // Go back to the default search method.

        if (!empty($modSettings['search_index']) && 'fulltext' == $modSettings['search_index']) {
            updateSettings(
                [
                    'search_index' => '',
                ]
            );
        }
    } elseif (!empty($_REQUEST['sa']) && 'removecustom' == $_REQUEST['sa']) {
        checkSession('get');

        db_query(
            "
			DROP TABLE IF EXISTS {$db_prefix}log_search_words",
            __FILE__,
            __LINE__
        );

        updateSettings(
            [
                'search_custom_index_config' => '',
            ]
        );

        // Go back to the default search method.

        if (!empty($modSettings['search_index']) && 'custom' == $modSettings['search_index']) {
            updateSettings(
                [
                    'search_index' => '',
                ]
            );
        }
    } elseif (isset($_POST['save'])) {
        checkSession();

        updateSettings(
            [
                'search_index' => empty($_POST['search_index']) || !in_array($_POST['search_index'], ['fulltext', 'custom'], true) ? '' : $_POST['search_index'],
                'search_force_index' => isset($_POST['search_force_index']) ? '1' : '0',
                'search_match_words' => isset($_POST['search_match_words']) ? '1' : '0',
            ]
        );
    }

    $context['table_info'] = [
        'data_length' => 0,
        'index_length' => 0,
        'fulltext_length' => 0,
        'custom_index_length' => 0,
    ];

    // Get some info about the messages table, to show its size and index size.

    if (0 != preg_match('~^`(.+?)`\.(.+?)$~', $db_prefix, $match)) {
        $request = db_query(
            '
			SHOW TABLE STATUS
			FROM `' . strtr($match[1], ['`' => '']) . "`
			LIKE '" . str_replace('_', '\_', $match[2]) . "messages'",
            false,
            false
        );
    } else {
        $request = db_query(
            "
			SHOW TABLE STATUS
			LIKE '" . str_replace('_', '\_', $db_prefix) . "messages'",
            false,
            false
        );
    }

    if (false !== $request && 1 == $GLOBALS['xoopsDB']->getRowsNum($request)) {
        // Only do this if the user has permission to execute this query.

        $row = $GLOBALS['xoopsDB']->fetchArray($request);

        $context['table_info']['data_length'] = $row['Data_length'];

        $context['table_info']['index_length'] = $row['Index_length'];

        $context['table_info']['fulltext_length'] = $row['Index_length'];

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }

    // Now check the custom index table, if it exists at all.

    if (0 !== preg_match('~^`(.+?)`\.(.+?)$~', $db_prefix, $match)) {
        $request = db_query(
            '
			SHOW TABLE STATUS
			FROM `' . strtr($match[1], ['`' => '']) . "`
			LIKE '" . str_replace('_', '\_', $match[2]) . "log_search_words'",
            false,
            false
        );
    } else {
        $request = db_query(
            "
			SHOW TABLE STATUS
			LIKE '" . str_replace('_', '\_', $db_prefix) . "log_search_words'",
            false,
            false
        );
    }

    if (false !== $request && 1 == $GLOBALS['xoopsDB']->getRowsNum($request)) {
        // Only do this if the user has permission to execute this query.

        $row = $GLOBALS['xoopsDB']->fetchArray($request);

        $context['table_info']['index_length'] += $row['Data_length'] + $row['Index_length'];

        $context['table_info']['custom_index_length'] = $row['Data_length'] + $row['Index_length'];

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }

    // Format the data and index length in kilobytes.

    foreach ($context['table_info'] as $type => $size) {
        $context['table_info'][$type] = comma_format($context['table_info'][$type] / 1024);
    }

    $context['custom_index'] = !empty($modSettings['search_custom_index_config']);

    $context['partial_custom_index'] = !empty($modSettings['search_custom_index_resume']) && empty($modSettings['search_custom_index_config']);

    $context['double_index'] = !empty($context['fulltext_index']) && $context['custom_index'];
}

function CreateMessageIndex()
{
    global $modSettings, $context, $db_prefix;

    $context['admin_tabs']['tabs']['method']['is_selected'] = true;

    $messages_per_batch = 100;

    $index_properties = [
        2 => [
            'column_definition' => 'smallint(5)',
        ],
        4 => [
            'column_definition' => 'mediumint(8)',
            'step_size' => 1000000,
            'max_size' => 16777215,
        ],
        5 => [
            'column_definition' => 'int(10)',
            'step_size' => 100000000,
            'max_size' => 4294967295,
        ],
    ];

    if (isset($_REQUEST['resume']) && !empty($modSettings['search_custom_index_resume'])) {
        $context['index_settings'] = unserialize($modSettings['search_custom_index_resume']);

        $context['start'] = (int)$context['index_settings']['resume_at'];

        unset($context['index_settings']['resume_at']);

        $context['step'] = 1;
    } else {
        $context['index_settings'] = [
            'bytes_per_word' => isset($_REQUEST['bytes_per_word']) && isset($index_properties[$_REQUEST['bytes_per_word']]) ? (int)$_REQUEST['bytes_per_word'] : 2,
        ];

        $context['start'] = isset($_REQUEST['start']) ? (int)$_REQUEST['start'] : 0;

        $context['step'] = isset($_REQUEST['step']) ? (int)$_REQUEST['step'] : 0;
    }

    if (0 !== $context['step']) {
        checkSession('request');
    }

    // Step 0: let the user determine how they like their index.

    if (0 === $context['step']) {
        $context['sub_template'] = 'create_index';
    }

    // Step 1: insert all the words.

    if (1 === $context['step']) {
        $context['sub_template'] = 'create_index_progress';

        if (0 === $context['start']) {
            db_query(
                "
				DROP TABLE IF EXISTS {$db_prefix}log_search_words",
                __FILE__,
                __LINE__
            );

            db_query(
                "
				CREATE TABLE {$db_prefix}log_search_words (
					ID_WORD " . $index_properties[$context['index_settings']['bytes_per_word']]['column_definition'] . " unsigned NOT NULL default '0',
					ID_MSG int(10) unsigned NOT NULL default '0',
					PRIMARY KEY (ID_WORD, ID_MSG)
				) ENGINE = ISAM",
                __FILE__,
                __LINE__
            );

            // Temporarily switch back to not using a search index.

            if (!empty($modSettings['search_index']) && 'custom' == $modSettings['search_index']) {
                updateSettings(['search_index' => '']);
            }

            // Don't let simultanious processes be updating the search index.

            if (!empty($modSettings['search_custom_index_config'])) {
                updateSettings(['search_custom_index_config' => '']);
            }
        }

        $num_messages = [
            'done' => 0,
            'todo' => 0,
        ];

        $request = db_query(
            "
			SELECT ID_MSG >= $context[start] AS todo, COUNT(*) AS numMesages
			FROM {$db_prefix}messages
			GROUP BY todo",
            __FILE__,
            __LINE__
        );

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $num_messages[empty($row['todo']) ? 'done' : 'todo'] = $row['numMesages'];
        }

        if (empty($num_messages['todo'])) {
            $context['step'] = 2;

            $context['percentage'] = 80;

            $context['start'] = 0;
        } else {
            // Number of seconds before the next step.

            $stop = time() + 3;

            while (time() < $stop) {
                $inserts = '';

                $request = db_query(
                    "
					SELECT ID_MSG, body
					FROM {$db_prefix}messages
					WHERE ID_MSG BETWEEN $context[start] AND " . ($context['start'] + $messages_per_batch - 1) . "
					LIMIT $messages_per_batch",
                    __FILE__,
                    __LINE__
                );

                while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
                    foreach (text2words($row['body'], $context['index_settings']['bytes_per_word'], true) as $ID_WORD) {
                        $inserts .= "($ID_WORD, $row[ID_MSG]),\n";
                    }
                }

                $num_messages['done'] += $GLOBALS['xoopsDB']->getRowsNum($request);

                $num_messages['todo'] -= $GLOBALS['xoopsDB']->getRowsNum($request);

                $GLOBALS['xoopsDB']->freeRecordSet($request);

                $context['start'] += $messages_per_batch;

                if (!empty($inserts)) {
                    db_query(
                        "
						INSERT IGNORE INTO {$db_prefix}log_search_words
							(ID_WORD, ID_MSG)
						VALUES
							" . mb_substr($inserts, 0, -2),
                        __FILE__,
                        __LINE__
                    );
                }

                if (0 === $num_messages['todo']) {
                    $context['step'] = 2;

                    $context['start'] = 0;

                    break;
                }  

                updateSettings(['search_custom_index_resume' => serialize(array_merge($context['index_settings'], ['resume_at' => $context['start']]))]);
            }

            // Since there are still two steps to go, 90% is the maximum here.

            $context['percentage'] = round($num_messages['done'] / ($num_messages['done'] + $num_messages['todo']), 3) * 80;
        }
    } // Step 2: removing the words that occur too often and are of no use.

    elseif (2 === $context['step']) {
        if ($context['index_settings']['bytes_per_word'] < 4) {
            $context['step'] = 3;
        } else {
            $stop_words = 0 === $context['start'] || empty($modSettings['search_stopwords']) ? [] : explode(',', $modSettings['search_stopwords']);

            $stop = time() + 3;

            $context['sub_template'] = 'create_index_progress';

            $maxMessages = ceil(60 * $modSettings['totalMessages'] / 100);

            while (time() < $stop) {
                $request = db_query(
                    "
					SELECT ID_WORD, count(ID_WORD) AS numWords
					FROM {$db_prefix}log_search_words
					WHERE ID_WORD BETWEEN $context[start] AND " . ($context['start'] + $index_properties[$context['index_settings']['bytes_per_word']]['step_size'] - 1) . "
					GROUP BY ID_WORD
					HAVING numWords > $maxMessages",
                    __FILE__,
                    __LINE__
                );

                while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
                    $stop_words[] = $row['ID_WORD'];
                }

                $GLOBALS['xoopsDB']->freeRecordSet($request);

                updateSettings(['search_stopwords' => implode(',', $stop_words)]);

                if (!empty($stop_words)) {
                    db_query(
                        "
						DELETE FROM {$db_prefix}log_search_words
						WHERE ID_WORD in (" . implode(', ', $stop_words) . ')',
                        __FILE__,
                        __LINE__
                    );
                }

                $context['start'] += $index_properties[$context['index_settings']['bytes_per_word']]['step_size'];

                if ($context['start'] > $index_properties[$context['index_settings']['bytes_per_word']]['max_size']) {
                    $context['step'] = 3;

                    break;
                }
            }

            $context['percentage'] = 80 + round($context['start'] / $index_properties[$context['index_settings']['bytes_per_word']]['max_size'], 3) * 20;
        }
    }

    // Step 3: remove words not distinctive enough.

    if (3 === $context['step']) {
        $context['sub_template'] = 'create_index_done';

        updateSettings(['search_index' => 'custom', 'search_custom_index_config' => serialize($context['index_settings'])]);

        db_query(
            "
			DELETE FROM {$db_prefix}settings
			WHERE variable = 'search_custom_index_resume'",
            __FILE__,
            __LINE__
        );
    }
}
