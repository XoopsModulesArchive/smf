<?php

/**********************************************************************************
 * ManageSmileys.php                                                               *
 * **********************************************************************************
 * SMF: Simple Machines Forum                                                      *
 * Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                    *
 * =============================================================================== *
 * Software Version:           SMF 1.1.1                                           *
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

/* // !!!

    void ManageSmileys()
        // !!!

    void EditSmileySettings()
        // !!!

    void EditSmileySets()
        // !!!

    void AddSmiley()
        // !!!

    void EditSmileys()
        // !!!

    void EditSmileyOrder()
        // !!!

    void InstallSmileySet()
        // !!!

    void ImportSmileys($smileyPath)
        // !!!
*/

function ManageSmileys()
{
    global $context, $txt, $scripturl, $modSettings;

    isAllowedTo('manage_smileys');

    adminIndex('manage_smileys');

    loadLanguage('ManageSmileys');

    loadTemplate('ManageSmileys');

    $subActions = [
        'addsmiley' => 'AddSmiley',
        'editicon' => 'EditMessageIcons',
        'editicons' => 'EditMessageIcons',
        'editsets' => 'EditSmileySets',
        'editsmileys' => 'EditSmileys',
        'import' => 'EditSmileySets',
        'modifyset' => 'EditSmileySets',
        'modifysmiley' => 'EditSmileys',
        'setorder' => 'EditSmileyOrder',
        'settings' => 'EditSmileySettings',
        'install' => 'InstallSmileySet',
    ];

    // Default the sub-action to 'edit smiley settings'.

    $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'settings';

    $context['page_title'] = &$txt['smileys_manage'];

    $context['sub_action'] = $_REQUEST['sa'];

    $context['sub_template'] = &$context['sub_action'];

    // Load up all the tabs...

    $context['admin_tabs'] = [
        'title' => &$txt['smileys_manage'],
        'help' => 'smileys',
        'description' => $txt['smiley_settings_explain'],
        'tabs' => [
            'editsets' => [
                'title' => $txt['smiley_sets'],
                'description' => $txt['smiley_editsets_explain'],
                'href' => $scripturl . '?action=smileys;sa=editsets',
            ],
            'addsmiley' => [
                'title' => $txt['smileys_add'],
                'description' => $txt['smiley_addsmiley_explain'],
                'href' => $scripturl . '?action=smileys;sa=addsmiley',
            ],
            'editsmileys' => [
                'title' => $txt['smileys_edit'],
                'description' => $txt['smiley_editsmileys_explain'],
                'href' => $scripturl . '?action=smileys;sa=editsmileys',
            ],
            'setorder' => [
                'title' => $txt['smileys_set_order'],
                'description' => $txt['smiley_setorder_explain'],
                'href' => $scripturl . '?action=smileys;sa=setorder',
            ],
            'editicons' => [
                'title' => $txt['icons_edit_message_icons'],
                'description' => $txt['icons_edit_icons_explain'],
                'href' => $scripturl . '?action=smileys;sa=editicons',
            ],
            'settings' => [
                'title' => $txt['settings'],
                'description' => $txt['smiley_settings_explain'],
                'href' => $scripturl . '?action=smileys;sa=settings',
                'is_last' => true,
            ],
        ],
    ];

    // Select the right tab based on the sub action.

    if (isset($context['admin_tabs']['tabs'][$context['sub_action']])) {
        $context['admin_tabs']['tabs'][$context['sub_action']]['is_selected'] = true;
    }

    // Some settings may not be enabled, disallow these from the tabs as appropriate.

    if (empty($modSettings['messageIcons_enable'])) {
        unset($context['admin_tabs']['tabs']['editicons']);
    }

    if (empty($modSettings['smiley_enable'])) {
        unset($context['admin_tabs']['tabs']['addsmiley']);

        unset($context['admin_tabs']['tabs']['editsmileys']);

        unset($context['admin_tabs']['tabs']['setorder']);
    }

    // Call the right function for this sub-acton.

    $subActions[$_REQUEST['sa']]();
}

function EditSmileySettings()
{
    global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

    // A form was submitted.

    if (isset($_POST['sc'], $_POST['smiley_sets_url'])) {
        checkSession();

        $context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);

        updateSettings(
            [
                'smiley_sets_default' => empty($context['smiley_sets'][$_POST['default_smiley_set']]) ? 'default' : $context['smiley_sets'][$_POST['default_smiley_set']],
                'smiley_sets_enable' => isset($_POST['smiley_sets_enable']) ? '1' : '0',
                'smiley_enable' => isset($_POST['smiley_enable']) ? '1' : '0',
                'messageIcons_enable' => isset($_POST['messageIcons_enable']) ? '1' : '0',
                'smileys_url' => $_POST['smiley_sets_url'],
                'smileys_dir' => $_POST['smiley_sets_dir'],
            ]
        );

        cache_put_data('parsing_smileys', null, 480);

        cache_put_data('posting_smileys', null, 480);

        // Redirect to mjake sure the new settings are reflected in the tabs.

        redirectexit('action=smileys;sa=settings');
    }

    $context['smileys_dir'] = empty($modSettings['smileys_dir']) ? $boarddir . '/Smileys' : $modSettings['smileys_dir'];

    $context['smileys_dir_found'] = is_dir($context['smileys_dir']);

    $context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);

    $set_names = explode("\n", $modSettings['smiley_sets_names']);

    foreach ($context['smiley_sets'] as $i => $set) {
        $context['smiley_sets'][$i] = [
            'id' => $i,
            'path' => $set,
            'name' => $set_names[$i],
            'selected' => $set == $modSettings['smiley_sets_default'],
        ];
    }
}

function EditSmileySets()
{
    global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

    // Set the right tab to be selected.

    $context['admin_tabs']['tabs']['editsets']['is_selected'] = true;

    // They must've been submitted a form.

    if (isset($_POST['sc'])) {
        checkSession();

        // Delete selected smiley sets.

        if (!empty($_POST['delete']) && !empty($_POST['smiley_set'])) {
            $set_paths = explode(',', $modSettings['smiley_sets_known']);

            $set_names = explode("\n", $modSettings['smiley_sets_names']);

            foreach ($_POST['smiley_set'] as $id => $val) {
                if (isset($set_paths[$id], $set_names[$id]) && !empty($id)) {
                    unset($set_paths[$id], $set_names[$id]);
                }
            }

            updateSettings(
                [
                    'smiley_sets_known' => addslashes(implode(',', $set_paths)),
                    'smiley_sets_names' => addslashes(implode("\n", $set_names)),
                    'smiley_sets_default' => addslashes(in_array($modSettings['smiley_sets_default'], $set_paths, true) ? $modSettings['smiley_sets_default'] : $set_paths[0]),
                ]
            );

            cache_put_data('parsing_smileys', null, 480);

            cache_put_data('posting_smileys', null, 480);
        } // Add a new smiley set.

        elseif (!empty($_POST['add'])) {
            $context['sub_action'] = 'modifyset';
        } // Create or modify a smiley set.

        elseif (isset($_POST['set'])) {
            $set_paths = explode(',', $modSettings['smiley_sets_known']);

            $set_names = explode("\n", $modSettings['smiley_sets_names']);

            // Create a new smiley set.

            if (-1 == $_POST['set'] && isset($_POST['smiley_sets_path'])) {
                if (in_array($_POST['smiley_sets_path'], $set_paths, true)) {
                    fatal_lang_error('smiley_set_already_exists');
                }

                updateSettings(
                    [
                        'smiley_sets_known' => addslashes($modSettings['smiley_sets_known']) . ',' . $_POST['smiley_sets_path'],
                        'smiley_sets_names' => addslashes($modSettings['smiley_sets_names']) . "\n" . $_POST['smiley_sets_name'],
                        'smiley_sets_default' => empty($_POST['smiley_sets_default']) ? addslashes($modSettings['smiley_sets_default']) : $_POST['smiley_sets_path'],
                    ]
                );
            } // Modify an existing smiley set.

            else {
                // Make sure the smiley set exists.

                if (!isset($set_paths[$_POST['set']]) || !isset($set_names[$_POST['set']])) {
                    fatal_lang_error('smiley_set_not_found');
                }

                // Make sure the path is not yet used by another smileyset.

                if (in_array($_POST['smiley_sets_path'], $set_paths, true) && $_POST['smiley_sets_path'] != $set_paths[$_POST['set']]) {
                    fatal_lang_error('smiley_set_path_already_used');
                }

                $set_paths[$_POST['set']] = stripslashes($_POST['smiley_sets_path']);

                $set_names[$_POST['set']] = stripslashes($_POST['smiley_sets_name']);

                updateSettings(
                    [
                        'smiley_sets_known' => addslashes(implode(',', $set_paths)),
                        'smiley_sets_names' => addslashes(implode("\n", $set_names)),
                        'smiley_sets_default' => empty($_POST['smiley_sets_default']) ? addslashes($modSettings['smiley_sets_default']) : $_POST['smiley_sets_path'],
                    ]
                );
            }

            // The user might have checked to also import smileys.

            if (!empty($_POST['smiley_sets_import'])) {
                ImportSmileys($_POST['smiley_sets_path']);
            }

            cache_put_data('parsing_smileys', null, 480);

            cache_put_data('posting_smileys', null, 480);
        }
    }

    // Load all available smileysets...

    $context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);

    $set_names = explode("\n", $modSettings['smiley_sets_names']);

    foreach ($context['smiley_sets'] as $i => $set) {
        $context['smiley_sets'][$i] = [
            'id' => $i,
            'path' => $set,
            'name' => $set_names[$i],
            'selected' => $set == $modSettings['smiley_sets_default'],
        ];
    }

    // Importing any smileys from an existing set?

    if ('import' == $context['sub_action']) {
        checkSession('get');

        $_GET['set'] = (int)$_GET['set'];

        // Sanity check - then import.

        if (isset($context['smiley_sets'][$_GET['set']])) {
            ImportSmileys($context['smiley_sets'][$_GET['set']]['path']);
        }

        // Force the process to continue.

        $context['sub_action'] = 'modifyset';
    }

    // If we're modifying or adding a smileyset, some context info needs to be set.

    if ('modifyset' == $context['sub_action']) {
        $_GET['set'] = !isset($_GET['set']) ? -1 : (int)$_GET['set'];

        if (-1 == $_GET['set'] || !isset($context['smiley_sets'][$_GET['set']])) {
            $context['current_set'] = [
                'id' => '-1',
                'path' => '',
                'name' => '',
                'selected' => false,
                'is_new' => true,
            ];
        } else {
            $context['current_set'] = &$context['smiley_sets'][$_GET['set']];

            $context['current_set']['is_new'] = false;

            // Calculate whether there are any smileys in the directory that can be imported.

            if (!empty($modSettings['smiley_enable']) && !empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir'] . '/' . $context['current_set']['path'])) {
                $smileys = [];

                $dir = dir($modSettings['smileys_dir'] . '/' . $context['current_set']['path']);

                while ($entry = $dir->read()) {
                    if (in_array(mb_strrchr($entry, '.'), ['.jpg', '.gif', '.jpeg', '.png'], true)) {
                        $smileys[mb_strtolower($entry)] = addslashes($entry);
                    }
                }

                $dir->close();

                // Exclude the smileys that are already in the database.

                $request = db_query(
                    "
					SELECT filename
					FROM {$db_prefix}smileys
					WHERE filename IN ('" . implode("', '", $smileys) . "')",
                    __FILE__,
                    __LINE__
                );

                while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
                    if (isset($smileys[mb_strtolower($row['filename'])])) {
                        unset($smileys[mb_strtolower($row['filename'])]);
                    }
                }

                $GLOBALS['xoopsDB']->freeRecordSet($request);

                $context['current_set']['can_import'] = count($smileys);

                // Setup this string to look nice.

                $txt['smiley_set_import_multiple'] = sprintf($txt['smiley_set_import_multiple'], $context['current_set']['can_import']);
            }
        }

        // Retrieve all potential smiley set directories.

        $context['smiley_set_dirs'] = [];

        if (!empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir'])) {
            $dir = dir($modSettings['smileys_dir']);

            while ($entry = $dir->read()) {
                if (!in_array($entry, ['.', '..'], true) && is_dir($modSettings['smileys_dir'] . '/' . $entry)) {
                    $context['smiley_set_dirs'][] = [
                        'id' => $entry,
                        'path' => $modSettings['smileys_dir'] . '/' . $entry,
                        'selectable' => $entry == $context['current_set']['path'] || !in_array($entry, explode(',', $modSettings['smiley_sets_known']), true),
                        'current' => $entry == $context['current_set']['path'],
                    ];
                }
            }

            $dir->close();
        }
    }
}

function AddSmiley()
{
    global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

    // Get a list of all known smiley sets.

    $context['smileys_dir'] = empty($modSettings['smileys_dir']) ? $boarddir . '/Smileys' : $modSettings['smileys_dir'];

    $context['smileys_dir_found'] = is_dir($context['smileys_dir']);

    $context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);

    $set_names = explode("\n", $modSettings['smiley_sets_names']);

    foreach ($context['smiley_sets'] as $i => $set) {
        $context['smiley_sets'][$i] = [
            'id' => $i,
            'path' => $set,
            'name' => $set_names[$i],
            'selected' => $set == $modSettings['smiley_sets_default'],
        ];
    }

    // Submitting a form?

    if (isset($_POST['sc'], $_POST['smiley_code'])) {
        checkSession();

        // Some useful arrays... types we allow - and ports we don't!

        $allowedTypes = ['jpeg', 'jpg', 'gif', 'png', 'bmp'];

        $disabledFiles = ['con', 'com1', 'com2', 'com3', 'com4', 'prn', 'aux', 'lpt1', '.htaccess', 'index.php'];

        $_POST['smiley_code'] = htmltrim__recursive($_POST['smiley_code']);

        $_POST['smiley_location'] = empty($_POST['smiley_location']) || $_POST['smiley_location'] > 2 || $_POST['smiley_location'] < 0 ? 0 : (int)$_POST['smiley_location'];

        $_POST['smiley_filename'] = htmltrim__recursive($_POST['smiley_filename']);

        // Make sure some code was entered.

        if (empty($_POST['smiley_code'])) {
            fatal_lang_error('smiley_has_no_code');
        }

        // Check whether the new code has duplicates. It should be unique.

        $request = db_query(
            "
			SELECT ID_SMILEY
			FROM {$db_prefix}smileys
			WHERE code = BINARY '$_POST[smiley_code]'",
            __FILE__,
            __LINE__
        );

        if ($GLOBALS['xoopsDB']->getRowsNum($request) > 0) {
            fatal_lang_error('smiley_not_unique');
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        // If we are uploading - check all the smiley sets are writable!

        if ('existing' != $_POST['method']) {
            $writeErrors = [];

            foreach ($context['smiley_sets'] as $set) {
                if (!is_writable($context['smileys_dir'] . '/' . $set['path'])) {
                    $writeErrors[] = $set['path'];
                }
            }

            if (!empty($writeErrors)) {
                fatal_error($txt['smileys_upload_error_notwritable'] . ' ' . implode(', ', $writeErrors));
            }
        }

        // Uploading just one smiley for all of them?

        if (isset($_POST['sameall']) && isset($_FILES['uploadSmiley']['name']) && '' != $_FILES['uploadSmiley']['name']) {
            if (!is_uploaded_file($_FILES['uploadSmiley']['tmp_name']) || ('' == @ini_get('open_basedir') && !file_exists($_FILES['uploadSmiley']['tmp_name']))) {
                fatal_lang_error('smileys_upload_error');
            }

            // Sorry, no spaces, dots, or anything else but letters allowed.

            $_FILES['uploadSmiley']['name'] = preg_replace(['/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'], ['_', '.', ''], $_FILES['uploadSmiley']['name']);

            // We only allow image files - it's THAT simple - no messing around here...

            if (!in_array(mb_strtolower(mb_substr(mb_strrchr($_FILES['uploadSmiley']['name'], '.'), 1)), $allowedTypes, true)) {
                fatal_error($txt['smileys_upload_error_types'] . ' ' . implode(', ', $allowedTypes) . '.', false);
            }

            // We only need the filename...

            $destName = basename($_FILES['uploadSmiley']['name']);

            // Make sure they aren't trying to upload a nasty file - for their own good here!

            if (in_array(mb_strtolower($destName), $disabledFiles, true)) {
                fatal_lang_error('smileys_upload_error_illegal');
            }

            // Check if the file already exists... and if not move it to EVERY smiley set directory.

            $i = 0;

            // Keep going until we find a set the file doesn't exist in. (or maybe it exists in all of them?)

            while (isset($context['smiley_sets'][$i]) && file_exists($context['smileys_dir'] . '/' . $context['smiley_sets'][$i]['path'] . '/' . $destName)) {
                $i++;
            }

            // Okay, we're going to put the smiley right here, since it's not there yet!

            if (isset($context['smiley_sets'][$i]['path'])) {
                $smileyLocation = $context['smileys_dir'] . '/' . $context['smiley_sets'][$i]['path'] . '/' . $destName;

                move_uploaded_file($_FILES['uploadSmiley']['tmp_name'], $smileyLocation);

                @chmod($smileyLocation, 0644);

                // Now, we want to move it from there to all the other sets.

                for ($n = count($context['smiley_sets']); $i < $n; $i++) {
                    $currentPath = $context['smileys_dir'] . '/' . $context['smiley_sets'][$i]['path'] . '/' . $destName;

                    // The file is already there!  Don't overwrite it!

                    if (file_exists($currentPath)) {
                        continue;
                    }

                    // Okay, so copy the first one we made to here.

                    copy($smileyLocation, $currentPath);

                    @chmod($currentPath, 0644);
                }
            }

            // Finally make sure it's saved correctly!

            $_POST['smiley_filename'] = $destName;
        } // What about uploading several files?

        elseif ('existing' != $_POST['method']) {
            foreach ($_FILES as $name => $data) {
                if ('' == $_FILES[$name]['name']) {
                    fatal_lang_error('smileys_upload_error_blank');
                }

                if (empty($newName)) {
                    $newName = basename($_FILES[$name]['name']);
                } elseif (basename($_FILES[$name]['name']) != $newName) {
                    fatal_lang_error('smileys_upload_error_name');
                }
            }

            foreach ($context['smiley_sets'] as $i => $set) {
                if (!isset($_FILES['individual_' . $set['name']]['name']) || '' == $_FILES['individual_' . $set['name']]['name']) {
                    continue;
                }

                // Got one...

                if (!is_uploaded_file($_FILES['individual_' . $set['name']]['tmp_name']) || ('' == @ini_get('open_basedir') && !file_exists($_FILES['individual_' . $set['name']]['tmp_name']))) {
                    fatal_lang_error('smileys_upload_error');
                }

                // Sorry, no spaces, dots, or anything else but letters allowed.

                $_FILES['individual_' . $set['name']]['name'] = preg_replace(['/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'], ['_', '.', ''], $_FILES['individual_' . $set['name']]['name']);

                // We only allow image files - it's THAT simple - no messing around here...

                if (!in_array(mb_strtolower(mb_substr(mb_strrchr($_FILES['individual_' . $set['name']]['name'], '.'), 1)), $allowedTypes, true)) {
                    fatal_error($txt['smileys_upload_error_types'] . ' ' . implode(', ', $allowedTypes) . '.', false);
                }

                // We only need the filename...

                $destName = basename($_FILES['individual_' . $set['name']]['name']);

                // Make sure they aren't trying to upload a nasty file - for their own good here!

                if (in_array(mb_strtolower($destName), $disabledFiles, true)) {
                    fatal_lang_error('smileys_upload_error_illegal');
                }

                // If the file exists - ignore it.

                $smileyLocation = $context['smileys_dir'] . '/' . $set['path'] . '/' . $destName;

                if (file_exists($smileyLocation)) {
                    continue;
                }

                // Finally - move the image!

                move_uploaded_file($_FILES['individual_' . $set['name']]['tmp_name'], $smileyLocation);

                @chmod($smileyLocation, 0644);

                // Should always be saved correctly!

                $_POST['smiley_filename'] = $destName;
            }
        }

        // Also make sure a filename was given.

        if (empty($_POST['smiley_filename'])) {
            fatal_lang_error('smiley_has_no_filename');
        }

        // Find the position on the right.

        $smileyOrder = '0';

        if (1 != $_POST['smiley_location']) {
            $request = db_query(
                "
				SELECT MAX(smileyOrder) + 1
				FROM {$db_prefix}smileys
				WHERE hidden = $_POST[smiley_location]
					AND smileyRow = 0",
                __FILE__,
                __LINE__
            );

            [$smileyOrder] = $GLOBALS['xoopsDB']->fetchRow($request);

            $GLOBALS['xoopsDB']->freeRecordSet($request);

            if (empty($smileyOrder)) {
                $smileyOrder = '0';
            }
        }

        db_query(
            "
			INSERT INTO {$db_prefix}smileys
				(code, filename, description, hidden, smileyOrder)
			VALUES (SUBSTRING('$_POST[smiley_code]', 1, 30), SUBSTRING('$_POST[smiley_filename]', 1, 48), SUBSTRING('$_POST[smiley_description]', 1, 80), $_POST[smiley_location], $smileyOrder)",
            __FILE__,
            __LINE__
        );

        cache_put_data('parsing_smileys', null, 480);

        cache_put_data('posting_smileys', null, 480);

        // No errors? Out of here!

        redirectexit('action=smileys;sa=editsmileys');
    }

    $context['selected_set'] = $modSettings['smiley_sets_default'];

    // Get all possible filenames for the smileys.

    $context['filenames'] = [];

    if ($context['smileys_dir_found']) {
        foreach ($context['smiley_sets'] as $smiley_set) {
            if (!file_exists($context['smileys_dir'] . '/' . $smiley_set['path'])) {
                continue;
            }

            $dir = dir($context['smileys_dir'] . '/' . $smiley_set['path']);

            while ($entry = $dir->read()) {
                if (!in_array($entry, $context['filenames'], true) && in_array(mb_strrchr($entry, '.'), ['.jpg', '.gif', '.jpeg', '.png'], true)) {
                    $context['filenames'][mb_strtolower($entry)] = [
                        'id' => htmlspecialchars($entry, ENT_QUOTES | ENT_HTML5),
                        'selected' => false,
                    ];
                }
            }

            $dir->close();
        }

        ksort($context['filenames']);
    }

    // Create a new smiley from scratch.

    $context['filenames'] = array_values($context['filenames']);

    $context['current_smiley'] = [
        'id' => 0,
        'code' => '',
        'filename' => $context['filenames'][0]['id'],
        'description' => &$txt['smileys_default_description'],
        'location' => 0,
        'is_new' => true,
    ];
}

function EditSmileys()
{
    global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

    // Force the correct tab to be displayed.

    $context['admin_tabs']['tabs']['editsmileys']['is_selected'] = true;

    // Submitting a form?

    if (isset($_POST['sc'])) {
        checkSession();

        // Changing the selected smileys?

        if (isset($_POST['smiley_action']) && !empty($_POST['checked_smileys'])) {
            foreach ($_POST['checked_smileys'] as $id => $smiley_id) {
                $_POST['checked_smileys'][$id] = (int)$smiley_id;
            }

            if ('delete' == $_POST['smiley_action']) {
                db_query(
                    "
					DELETE FROM {$db_prefix}smileys
					WHERE ID_SMILEY IN (" . implode(', ', $_POST['checked_smileys']) . ')',
                    __FILE__,
                    __LINE__
                );
            } // Changing the status of the smiley?

            else {
                // Check it's a valid type.

                $displayTypes = [
                    'post' => 0,
                    'hidden' => 1,
                    'popup' => 2,
                ];

                if (isset($displayTypes[$_POST['smiley_action']])) {
                    db_query(
                        "
						UPDATE {$db_prefix}smileys
						SET hidden = " . $displayTypes[$_POST['smiley_action']] . '
						WHERE ID_SMILEY IN (' . implode(', ', $_POST['checked_smileys']) . ')',
                        __FILE__,
                        __LINE__
                    );
                }
            }
        } // Create/modify a smiley.

        elseif (isset($_POST['smiley'])) {
            $_POST['smiley'] = (int)$_POST['smiley'];

            $_POST['smiley_code'] = htmltrim__recursive($_POST['smiley_code']);

            $_POST['smiley_filename'] = htmltrim__recursive($_POST['smiley_filename']);

            $_POST['smiley_location'] = empty($_POST['smiley_location']) || $_POST['smiley_location'] > 2 || $_POST['smiley_location'] < 0 ? 0 : (int)$_POST['smiley_location'];

            // Make sure some code was entered.

            if (empty($_POST['smiley_code'])) {
                fatal_lang_error('smiley_has_no_code');
            }

            // Also make sure a filename was given.

            if (empty($_POST['smiley_filename'])) {
                fatal_lang_error('smiley_has_no_filename');
            }

            // Check whether the new code has duplicates. It should be unique.

            $request = db_query(
                "
				SELECT ID_SMILEY
				FROM {$db_prefix}smileys
				WHERE code = BINARY '$_POST[smiley_code]'" . (empty($_POST['smiley']) ? '' : "
					AND ID_SMILEY != $_POST[smiley]"),
                __FILE__,
                __LINE__
            );

            if ($GLOBALS['xoopsDB']->getRowsNum($request) > 0) {
                fatal_lang_error('smiley_not_unique');
            }

            $GLOBALS['xoopsDB']->freeRecordSet($request);

            db_query(
                "
				UPDATE {$db_prefix}smileys
				SET
					code = '$_POST[smiley_code]',
					filename = '$_POST[smiley_filename]',
					description = '$_POST[smiley_description]',
					hidden = $_POST[smiley_location]
				WHERE ID_SMILEY = $_POST[smiley]",
                __FILE__,
                __LINE__
            );

            // Sort all smiley codes for more accurate parsing (longest code first).

            db_query(
                "
				ALTER TABLE {$db_prefix}smileys
				ORDER BY LENGTH(code) DESC",
                __FILE__,
                __LINE__
            );
        }

        cache_put_data('parsing_smileys', null, 480);

        cache_put_data('posting_smileys', null, 480);
    }

    // Load all known smiley sets.

    $context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);

    $set_names = explode("\n", $modSettings['smiley_sets_names']);

    foreach ($context['smiley_sets'] as $i => $set) {
        $context['smiley_sets'][$i] = [
            'id' => $i,
            'path' => $set,
            'name' => $set_names[$i],
            'selected' => $set == $modSettings['smiley_sets_default'],
        ];
    }

    // Prepare overview of all (custom) smileys.

    if ('editsmileys' == $context['sub_action']) {
        $sortColumns = [
            'code',
            'filename',
            'description',
            'hidden',
        ];

        // Default to 'order by filename'.

        $context['sort'] = empty($_REQUEST['sort']) || !in_array($_REQUEST['sort'], $sortColumns, true) ? 'filename' : $_REQUEST['sort'];

        $request = db_query(
            "
			SELECT ID_SMILEY, code, filename, description, smileyRow, smileyOrder, hidden
			FROM {$db_prefix}smileys
			ORDER BY $context[sort]",
            __FILE__,
            __LINE__
        );

        $context['smileys'] = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $context['smileys'][] = [
                'id' => $row['ID_SMILEY'],
                'code' => htmlspecialchars($row['code'], ENT_QUOTES | ENT_HTML5),
                'filename' => htmlspecialchars($row['filename'], ENT_QUOTES | ENT_HTML5),
                'description' => htmlspecialchars($row['description'], ENT_QUOTES | ENT_HTML5),
                'row' => $row['smileyRow'],
                'order' => $row['smileyOrder'],
                'location' => empty($row['hidden']) ? $txt['smileys_location_form'] : (1 == $row['hidden'] ? $txt['smileys_location_hidden'] : $txt['smileys_location_popup']),
                'sets_not_found' => [],
            ];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        if (!empty($modSettings['smileys_dir']) && is_dir($modSettings['smileys_dir'])) {
            foreach ($context['smiley_sets'] as $smiley_set) {
                foreach ($context['smileys'] as $smiley_id => $smiley) {
                    if (!file_exists($modSettings['smileys_dir'] . '/' . $smiley_set['path'] . '/' . $smiley['filename'])) {
                        $context['smileys'][$smiley_id]['sets_not_found'][] = $smiley_set['path'];
                    }
                }
            }
        }

        $context['selected_set'] = $modSettings['smiley_sets_default'];
    } // Modifying smileys.

    elseif ('modifysmiley' == $context['sub_action']) {
        // Get a list of all known smiley sets.

        $context['smileys_dir'] = empty($modSettings['smileys_dir']) ? $boarddir . '/Smileys' : $modSettings['smileys_dir'];

        $context['smileys_dir_found'] = is_dir($context['smileys_dir']);

        $context['smiley_sets'] = explode(',', $modSettings['smiley_sets_known']);

        $set_names = explode("\n", $modSettings['smiley_sets_names']);

        foreach ($context['smiley_sets'] as $i => $set) {
            $context['smiley_sets'][$i] = [
                'id' => $i,
                'path' => $set,
                'name' => $set_names[$i],
                'selected' => $set == $modSettings['smiley_sets_default'],
            ];
        }

        $context['selected_set'] = $modSettings['smiley_sets_default'];

        // Get all possible filenames for the smileys.

        $context['filenames'] = [];

        if ($context['smileys_dir_found']) {
            foreach ($context['smiley_sets'] as $smiley_set) {
                if (!file_exists($context['smileys_dir'] . '/' . $smiley_set['path'])) {
                    continue;
                }

                $dir = dir($context['smileys_dir'] . '/' . $smiley_set['path']);

                while ($entry = $dir->read()) {
                    if (!in_array($entry, $context['filenames'], true) && in_array(mb_strrchr($entry, '.'), ['.jpg', '.gif', '.jpeg', '.png'], true)) {
                        $context['filenames'][mb_strtolower($entry)] = [
                            'id' => htmlspecialchars($entry, ENT_QUOTES | ENT_HTML5),
                            'selected' => false,
                        ];
                    }
                }

                $dir->close();
            }

            ksort($context['filenames']);
        }

        $request = db_query(
            "
			SELECT ID_SMILEY AS id, code, filename, description, hidden AS location, 0 AS is_new
			FROM {$db_prefix}smileys
			WHERE ID_SMILEY = " . (int)$_REQUEST['smiley'],
            __FILE__,
            __LINE__
        );

        if (1 != $GLOBALS['xoopsDB']->getRowsNum($request)) {
            fatal_lang_error('smiley_not_found');
        }

        $context['current_smiley'] = $GLOBALS['xoopsDB']->fetchArray($request);

        $GLOBALS['xoopsDB']->freeRecordSet($request);

        $context['current_smiley']['code'] = htmlspecialchars($context['current_smiley']['code'], ENT_QUOTES | ENT_HTML5);

        $context['current_smiley']['filename'] = htmlspecialchars($context['current_smiley']['filename'], ENT_QUOTES | ENT_HTML5);

        $context['current_smiley']['description'] = htmlspecialchars($context['current_smiley']['description'], ENT_QUOTES | ENT_HTML5);

        if (isset($context['filenames'][mb_strtolower($context['current_smiley']['filename'])])) {
            $context['filenames'][mb_strtolower($context['current_smiley']['filename'])]['selected'] = true;
        }
    }
}

function EditSmileyOrder()
{
    global $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

    // Move smileys to another position.

    if (isset($_GET['sesc'])) {
        checkSession('get');

        $_GET['location'] = empty($_GET['location']) || 'popup' != $_GET['location'] ? 0 : 2;

        $_GET['source'] = empty($_GET['source']) ? 0 : (int)$_GET['source'];

        if (empty($_GET['source'])) {
            fatal_lang_error('smiley_not_found');
        }

        if (!empty($_GET['after'])) {
            $_GET['after'] = (int)$_GET['after'];

            $request = db_query(
                "
				SELECT smileyRow, smileyOrder, hidden
				FROM {$db_prefix}smileys
				WHERE hidden = $_GET[location]
					AND ID_SMILEY = $_GET[after]",
                __FILE__,
                __LINE__
            );

            if (1 != $GLOBALS['xoopsDB']->getRowsNum($request)) {
                fatal_lang_error('smiley_not_found');
            }

            [$smileyRow, $smileyOrder, $smileyLocation] = $GLOBALS['xoopsDB']->fetchRow($request);

            $GLOBALS['xoopsDB']->freeRecordSet($request);
        } else {
            $smileyRow = (int)$_GET['row'];

            $smileyOrder = -1;

            $smileyLocation = (int)$_GET['location'];
        }

        db_query(
            "
			UPDATE {$db_prefix}smileys
			SET smileyOrder = smileyOrder + 1
			WHERE hidden = $_GET[location]
				AND smileyRow = $smileyRow
				AND smileyOrder > $smileyOrder",
            __FILE__,
            __LINE__
        );

        db_query(
            "
			UPDATE {$db_prefix}smileys
			SET
				smileyOrder = $smileyOrder + 1,
				smileyRow = $smileyRow,
				hidden = $smileyLocation
			WHERE ID_SMILEY = $_GET[source]",
            __FILE__,
            __LINE__
        );

        cache_put_data('parsing_smileys', null, 480);

        cache_put_data('posting_smileys', null, 480);
    }

    $request = db_query(
        "
		SELECT ID_SMILEY, code, filename, description, smileyRow, smileyOrder, hidden
		FROM {$db_prefix}smileys
		WHERE hidden != 1
		ORDER BY smileyOrder, smileyRow",
        __FILE__,
        __LINE__
    );

    $context['smileys'] = [
        'postform' => [
            'rows' => [],
        ],
        'popup' => [
            'rows' => [],
        ],
    ];

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        $location = empty($row['hidden']) ? 'postform' : 'popup';

        $context['smileys'][$location]['rows'][$row['smileyRow']][] = [
            'id' => $row['ID_SMILEY'],
            'code' => htmlspecialchars($row['code'], ENT_QUOTES | ENT_HTML5),
            'filename' => htmlspecialchars($row['filename'], ENT_QUOTES | ENT_HTML5),
            'description' => htmlspecialchars($row['description'], ENT_QUOTES | ENT_HTML5),
            'row' => $row['smileyRow'],
            'order' => $row['smileyOrder'],
            'selected' => !empty($_REQUEST['move']) && $_REQUEST['move'] == $row['ID_SMILEY'],
        ];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    $context['move_smiley'] = empty($_REQUEST['move']) ? 0 : (int)$_REQUEST['move'];

    // Make sure all rows are sequential.

    foreach (array_keys($context['smileys']) as $location) {
        $context['smileys'][$location] = [
            'id' => $location,
            'title' => 'postform' == $location ? $txt['smileys_location_form'] : $txt['smileys_location_popup'],
            'description' => 'postform' == $location ? $txt['smileys_location_form_description'] : $txt['smileys_location_popup_description'],
            'last_row' => count($context['smileys'][$location]['rows']),
            'rows' => array_values($context['smileys'][$location]['rows']),
        ];
    }

    // Check & fix smileys that are not ordered properly in the database.

    foreach (array_keys($context['smileys']) as $location) {
        foreach ($context['smileys'][$location]['rows'] as $id => $smiley_row) {
            // Fix empty rows if any.

            if ($id != $smiley_row[0]['row']) {
                db_query(
                    "
					UPDATE {$db_prefix}smileys
					SET smileyRow = $id
					WHERE smileyRow = {$smiley_row[0]['row']}
						AND hidden = " . ('postform' == $location ? '0' : '2'),
                    __FILE__,
                    __LINE__
                );

                // Only change the first row value of the first smiley (we don't need the others :P).

                $context['smileys'][$location]['rows'][$id][0]['row'] = $id;
            }

            // Make sure the smiley order is always sequential.

            foreach ($smiley_row as $order_id => $smiley) {
                if ($order_id != $smiley['order']) {
                    db_query(
                        "
						UPDATE {$db_prefix}smileys
						SET smileyOrder = $order_id
						WHERE ID_SMILEY = $smiley[id]",
                        __FILE__,
                        __LINE__
                    );
                }
            }
        }
    }

    cache_put_data('parsing_smileys', null, 480);

    cache_put_data('posting_smileys', null, 480);
}

function InstallSmileySet()
{
    global $sourcedir, $boarddir, $modSettings;

    isAllowedTo('manage_smileys');

    checkSession('request');

    require_once $sourcedir . '/Subs-Package.php';

    $name = strtok(basename(isset($_FILES['set_gz']) ? $_FILES['set_gz']['name'] : $_REQUEST['set_gz']), '.');

    $name = preg_replace(['/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'], ['_', '.', ''], $name);

    // !!! Decide: overwrite or not?

    if (isset($_FILES['set_gz']) && is_uploaded_file($_FILES['set_gz']['tmp_name']) && ('' != @ini_get('open_basedir') || file_exists($_FILES['set_gz']['tmp_name']))) {
        $extracted = read_tgz_file($_FILES['set_gz']['tmp_name'], $boarddir . '/Smileys/' . $name);
    } elseif (isset($_REQUEST['set_gz'])) {
        checkSession('request');

        // Check that the theme is from simplemachines.org, for now... maybe add mirroring later.

        if (0 == preg_match('~^http://[\w_\-]+\.simplemachines\.org/~', $_REQUEST['set_gz']) || false !== mb_strpos($_REQUEST['set_gz'], 'dlattach')) {
            fatal_lang_error('not_on_simplemachines');
        }

        $extracted = read_tgz_file($_REQUEST['set_gz'], $boarddir . '/Smileys/' . $name);
    } else {
        redirectexit('action=smileys');
    }

    updateSettings(
        [
            'smiley_sets_known' => addslashes($modSettings['smiley_sets_known'] . ',' . $name),
            'smiley_sets_names' => addslashes($modSettings['smiley_sets_names'] . "\n" . strtok(basename(isset($_FILES['set_gz']) ? $_FILES['set_gz']['name'] : $_REQUEST['set_gz']), '.')),
        ]
    );

    cache_put_data('parsing_smileys', null, 480);

    cache_put_data('posting_smileys', null, 480);

    // !!! Add some confirmation?

    redirectexit('action=smileys');
}

// A function to import new smileys from an existing directory into the database.
function ImportSmileys($smileyPath)
{
    global $db_prefix, $modSettings;

    if (empty($modSettings['smileys_dir']) || !is_dir($modSettings['smileys_dir'] . '/' . $smileyPath)) {
        fatal_lang_error('smiley_set_unable_to_import');
    }

    $smileys = [];

    $dir = dir($modSettings['smileys_dir'] . '/' . $smileyPath);

    while ($entry = $dir->read()) {
        if (in_array(mb_strrchr($entry, '.'), ['.jpg', '.gif', '.jpeg', '.png'], true)) {
            $smileys[mb_strtolower($entry)] = addslashes($entry);
        }
    }

    $dir->close();

    // Exclude the smileys that are already in the database.

    $request = db_query(
        "
		SELECT filename
		FROM {$db_prefix}smileys
		WHERE filename IN ('" . implode("', '", $smileys) . "')",
        __FILE__,
        __LINE__
    );

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        if (isset($smileys[mb_strtolower($row['filename'])])) {
            unset($smileys[mb_strtolower($row['filename'])]);
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    $request = db_query(
        "
		SELECT MAX(smileyOrder)
		FROM {$db_prefix}smileys
		WHERE hidden = 0
			AND smileyRow = 0",
        __FILE__,
        __LINE__
    );

    [$smileyOrder] = $GLOBALS['xoopsDB']->fetchRow($request);

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    $new_smileys = [];

    foreach ($smileys as $smiley) {
        if (mb_strlen($smiley) <= 48) {
            $new_smileys[] = "(SUBSTRING(':" . strtok($smiley, '.') . ":', 1, 30), '$smiley', SUBSTRING('" . strtok($smiley, '.') . "', 1, 80), 0, " . ++$smileyOrder . ')';
        }
    }

    if (!empty($new_smileys)) {
        db_query(
            "
			INSERT INTO {$db_prefix}smileys
				(code, filename, description, smileyRow, smileyOrder)
			VALUES" . implode(
                ',
				',
                $new_smileys
            ),
            __FILE__,
            __LINE__
        );

        // Make sure the smiley codes are still in the right order.

        db_query(
            "
			ALTER TABLE {$db_prefix}smileys
			ORDER BY LENGTH(code) DESC",
            __FILE__,
            __LINE__
        );

        cache_put_data('parsing_smileys', null, 480);

        cache_put_data('posting_smileys', null, 480);
    }
}

function EditMessageIcons()
{
    global $user_info, $modSettings, $context, $settings, $db_prefix, $txt, $boarddir;

    $context['admin_tabs']['tabs']['editicons']['is_selected'] = true;

    $context['icons'] = [];

    $request = db_query(
        "
		SELECT m.ID_ICON, m.title, m.filename, m.iconOrder, m.ID_BOARD, b.name AS boardName
		FROM {$db_prefix}message_icons AS m
			LEFT JOIN {$db_prefix}boards AS b ON (b.ID_BOARD = m.ID_BOARD)
		WHERE $user_info[query_see_board]",
        __FILE__,
        __LINE__
    );

    $lastIcon = 0;

    $trueOrder = 0;

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
        $context['icons'][$row['ID_ICON']] = [
            'id' => $row['ID_ICON'],
            'title' => $row['title'],
            'filename' => $row['filename'],
            'image_url' => $settings[file_exists($settings['theme_dir'] . '/images/post/' . $row['filename'] . '.gif') ? 'actual_images_url' : 'default_images_url'] . '/post/' . $row['filename'] . '.gif',
            'board_id' => $row['ID_BOARD'],
            'board' => empty($row['boardName']) ? $txt['icons_edit_icons_all_boards'] : $row['boardName'],
            'order' => $row['iconOrder'],
            'true_order' => $trueOrder++,
            'after' => $lastIcon,
        ];

        $lastIcon = $row['ID_ICON'];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    // Submitting a form?

    if (isset($_POST['sc'])) {
        checkSession();

        // Deleting icons?

        if (isset($_POST['delete']) && !empty($_POST['checked_icons'])) {
            $deleteIcons = [];

            foreach ($_POST['checked_icons'] as $icon) {
                $deleteIcons[] = (int)$icon;
            }

            // Do the actual delete!

            db_query(
                "
				DELETE FROM {$db_prefix}message_icons
				WHERE ID_ICON IN (" . implode(', ', $deleteIcons) . ')
				LIMIT ' . count($deleteIcons),
                __FILE__,
                __LINE__
            );
        } // Editing/Adding an icon?

        elseif ('editicon' == $context['sub_action'] && isset($_GET['icon'])) {
            $_GET['icon'] = (int)$_GET['icon'];

            // Do some preperation with the data... like check the icon exists *somewhere*

            if (false !== mb_strpos($_POST['icon_filename'], '.gif')) {
                $_POST['icon_filename'] = mb_substr($_POST['icon_filename'], 0, -4);
            }

            if (!file_exists($settings['default_theme_dir'] . '/images/post/' . $_POST['icon_filename'] . '.gif')) {
                fatal_lang_error('icon_not_found');
            } // There is a 16 character limit on message icons...

            elseif (mb_strlen($_POST['icon_filename']) > 16) {
                fatal_lang_error('icon_name_too_long');
            } elseif ($_POST['icon_location'] == $_GET['icon'] && !empty($_GET['icon'])) {
                fatal_lang_error('icon_after_itself');
            }

            // First do the sorting... if this is an edit reduce the order of everything after it by one ;)

            if (0 != $_GET['icon']) {
                $oldOrder = $context['icons'][$_GET['icon']]['true_order'];

                foreach ($context['icons'] as $id => $data) {
                    if ($data['true_order'] > $oldOrder) {
                        $context['icons'][$id]['true_order']--;
                    }
                }
            }

            // Get the new order.

            $newOrder = 0 == $_POST['icon_location'] ? 0 : $context['icons'][$_POST['icon_location']]['true_order'] + 1;

            // Do the same, but with the one that used to be after this icon, done to avoid conflict.

            foreach ($context['icons'] as $id => $data) {
                if ($data['true_order'] >= $newOrder) {
                    $context['icons'][$id]['true_order']++;
                }
            }

            // Finally set the current icon's position!

            $context['icons'][$_GET['icon']]['true_order'] = $newOrder;

            // Simply replace the existing data for the other bits.

            $context['icons'][$_GET['icon']]['title'] = $_POST['icon_description'];

            $context['icons'][$_GET['icon']]['filename'] = $_POST['icon_filename'];

            $context['icons'][$_GET['icon']]['board_id'] = (int)$_POST['icon_board'];

            // Do a huge replace ;)

            $insert = [];

            foreach ($context['icons'] as $id => $icon) {
                // Make sure to escape the other icon titles, however if one is being added it's already escaped.

                if (0 != $id) {
                    $icon['title'] = addslashes($icon['title']);
                }

                $insert[] = "($id, $icon[board_id], SUBSTRING('$icon[title]', 1, 80), SUBSTRING('$icon[filename]', 1, 80), $icon[true_order])";
            }

            db_query(
                "
				REPLACE INTO {$db_prefix}message_icons
					(ID_ICON, ID_BOARD, title, filename, iconOrder)
				VALUES
					" . implode(
                    ',
					',
                    $insert
                ),
                __FILE__,
                __LINE__
            );
        }

        // Sort by order, so it is quicker :)

        db_query(
            "
			ALTER TABLE {$db_prefix}message_icons
			ORDER BY iconOrder",
            __FILE__,
            __LINE__
        );

        // Unless we're adding a new thing, we'll escape

        if (!isset($_POST['add'])) {
            redirectexit('action=smileys;sa=editicons');
        }
    }

    // If we're adding/editing an icon we'll need a list of boards

    if ('editicon' == $context['sub_action'] || isset($_POST['add'])) {
        $context['new_icon'] = !isset($_GET['icon']) || !isset($context['icons'][$_GET['icon']]);

        // Force the sub_template just incase.

        $context['sub_template'] = 'editicon';

        if (!$context['new_icon']) {
            $context['icon'] = &$context['icons'][$_GET['icon']];
        }

        $request = db_query(
            "
			SELECT ID_BOARD, name
			FROM {$db_prefix}boards
			WHERE $user_info[query_see_board]",
            __FILE__,
            __LINE__
        );

        $context['boards'] = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($request))) {
            $context['boards'][$row['ID_BOARD']] = $row['name'];
        }

        $GLOBALS['xoopsDB']->freeRecordSet($request);
    }
}
