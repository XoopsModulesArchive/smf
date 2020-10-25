<?php

/**********************************************************************************
 * ManageServer.php                                                                *
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

/*	This file contains all the functionality required to be able to edit the
    core server settings. This includes anything from which an error may result
    in the forum destroying itself in a firey fury.

    void ModifySettings()
        // !!!

    void ModifySettings2()
        // !!!

    void ModifyCoreSettings()
        - shows an interface for the settings in Settings.php to be changed.
        - uses the rawdata sub template (not theme-able.)
        - requires the admin_forum permission.
        - uses the edit_settings administration area.
        - contains the actual array of settings to show from Settings.php.
        - accessed from ?action=serversettings.

    void ModifyCoreSettings2()
        - saves those settings set from ?action=serversettings to the
          Settings.php file.
        - requires the admin_forum permission.
        - contains arrays of the types of data to save into Settings.php.
        - redirects back to ?action=serversettings.
        - accessed from ?action=serversettings2.

    void ModifyOtherSettings()
        // !!!

    void ModifyCacheSettings()
        // !!!
*/

// This is the main pass through function, it creates tabs and the like.
function ModifySettings()
{
    global $context, $txt, $scripturl, $modSettings;

    // This is just to keep the database password more secure.

    isAllowedTo('admin_forum');

    checkSession('get');

    // The administration bar......

    adminIndex('edit_settings');

    $context['page_title'] = $txt[222];

    $context['sub_template'] = 'show_settings';

    $subActions = [
        'core' => 'ModifyCoreSettings',
        'other' => 'ModifyOtherSettings',
        'cache' => 'ModifyCacheSettings',
    ];

    // By default we're editing the core settings

    $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'core';

    $context['sub_action'] = $_REQUEST['sa'];

    // Load up all the tabs...

    $context['admin_tabs'] = [
        'title' => &$txt[222],
        'help' => 'serversettings',
        'description' => $txt[347],
        'tabs' => [
            'core' => [
                'title' => $txt['core_configuration'],
                'href' => $scripturl . '?action=serversettings;sa=core;sesc=' . $context['session_id'],
            ],
            'other' => [
                'title' => $txt['other_configuration'],
                'href' => $scripturl . '?action=serversettings;sa=other;sesc=' . $context['session_id'],
            ],
            'cache' => [
                'title' => $txt['caching_settings'],
                'href' => $scripturl . '?action=serversettings;sa=cache;sesc=' . $context['session_id'],
                'is_last' => true,
            ],
        ],
    ];

    // Select the right tab based on the sub action.

    if (isset($context['admin_tabs']['tabs'][$context['sub_action']])) {
        $context['admin_tabs']['tabs'][$context['sub_action']]['is_selected'] = true;
    }

    // Call the right function for this sub-acton.

    $subActions[$_REQUEST['sa']]();
}

// This function basically just redirects to the right save function.
function ModifySettings2()
{
    global $context, $txt, $scripturl, $modSettings;

    isAllowedTo('admin_forum');

    // Quick session check...

    checkSession();

    $subActions = [
        'core' => 'ModifyCoreSettings2',
        'other' => 'ModifyOtherSettings',
        'cache' => 'ModifyCacheSettings',
    ];

    // Default to core (I assume)

    $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'core';

    // Actually call the saving function.

    $subActions[$_REQUEST['sa']]();
}

// Basic forum settings - database name, host, etc.
function ModifyCoreSettings()
{
    global $scripturl, $context, $settings, $txt, $sc, $boarddir, $func;

    // Warn the user if the backup of Settings.php failed.

    $settings_not_writable = !is_writable($boarddir . '/Settings.php');

    $settings_backup_fail = !@is_writable($boarddir . '/Settings_bak.php') || !@copy($boarddir . '/Settings.php', $boarddir . '/Settings_bak.php');

    /* If you're writing a mod, it's a bad idea to add things here....
    For each option:
        variable name, description, type (constant), size/possible values, helptext.
    OR	an empty string for a horizontal rule.
    OR	a string for a titled section. */

    $config_vars = [
        ['db_server', &$txt['smf5'], 'text'],
        ['db_user', &$txt['smf6'], 'text'],
        ['db_passwd', &$txt['smf7'], 'password'],
        ['db_name', &$txt['smf8'], 'text'],
        ['db_prefix', &$txt['smf54'], 'text'],
        ['db_persist', &$txt['db_persist'], 'check', null, 'db_persist'],
        ['db_error_send', &$txt['db_error_send'], 'check'],
        '',
        ['maintenance', &$txt[348], 'check'],
        ['mtitle', &$txt['maintenance1'], 'text', 36],
        ['mmessage', &$txt['maintenance2'], 'text', 36],
        '',
        ['mbname', &$txt[350], 'text', 30],
        ['webmaster_email', &$txt[355], 'text', 30],
        ['cookiename', &$txt[352], 'text', 20],
        'language' => ['language', &$txt['default_language'], 'select', []],
        '',
        ['boardurl', &$txt[351], 'text', 36],
        ['boarddir', &$txt[356], 'text', 36],
        ['sourcedir', &$txt[360], 'text', 36],
        '',
    ];

    // Find the available language files.

    $language_directories = [
        $settings['default_theme_dir'] . '/languages',
        $settings['actual_theme_dir'] . '/languages',
    ];

    if (!empty($settings['base_theme_dir'])) {
        $language_directories[] = $settings['base_theme_dir'] . '/languages';
    }

    $language_directories = array_unique($language_directories);

    foreach ($language_directories as $language_dir) {
        if (!file_exists($language_dir)) {
            continue;
        }

        $dir = dir($language_dir);

        while ($entry = $dir->read()) {
            if (preg_match('~^index\.(.+)\.php$~', $entry, $matches)) {
                $config_vars['language'][3][$matches[1]] = [$matches[1], $func['ucwords'](strtr($matches[1], '_', ' '))];
            }
        }

        $dir->close();
    }

    // Setup the template stuff.

    $context['post_url'] = $scripturl . '?action=serversettings2;sa=core';

    $context['settings_title'] = $txt['core_configuration'];

    $context['save_disabled'] = $settings_not_writable;

    if ($settings_not_writable) {
        $context['settings_message'] = '<div align="center"><b>' . $txt['settings_not_writable'] . '</b></div><br>';
    } elseif ($settings_backup_fail) {
        $context['settings_message'] = '<div align="center"><b>' . $txt['smf1'] . '</b></div><br>';
    }

    // Fill the config array.

    $context['config_vars'] = [];

    foreach ($config_vars as $config_var) {
        if (!is_array($config_var)) {
            $context['config_vars'][] = $config_var;
        } else {
            $varname = $config_var[0];

            global $$varname;

            $context['config_vars'][] = [
                'label' => $config_var[1],
                'help' => $config_var[4] ?? '',
                'type' => $config_var[2],
                'size' => empty($config_var[3]) ? 0 : $config_var[3],
                'data' => isset($config_var[3]) && is_array($config_var[3]) ? $config_var[3] : [],
                'name' => $config_var[0],
                'value' => htmlspecialchars($$varname, ENT_QUOTES | ENT_HTML5),
                'disabled' => $settings_not_writable,
            ];
        }
    }
}

// Put the core settings in Settings.php.
function ModifyCoreSettings2()
{
    global $boarddir, $sc, $cookiename, $modSettings, $user_settings, $sourcedir;

    global $context;

    // Strip the slashes off of the post vars.

    foreach ($_POST as $key => $val) {
        $_POST[$key] = stripslashes__recursive($val);
    }

    // Fix the darn stupid cookiename! (more may not be allowed, but these for sure!)

    if (isset($_POST['cookiename'])) {
        $_POST['cookiename'] = preg_replace('~[,;\s\.$]+~' . ($context['utf8'] ? 'u' : ''), '', $_POST['cookiename']);
    }

    // Fix the forum's URL if necessary.

    if ('/index.php' == mb_substr($_POST['boardurl'], -10)) {
        $_POST['boardurl'] = mb_substr($_POST['boardurl'], 0, -10);
    } elseif ('/' == mb_substr($_POST['boardurl'], -1)) {
        $_POST['boardurl'] = mb_substr($_POST['boardurl'], 0, -1);
    }

    if ('http://' != mb_substr($_POST['boardurl'], 0, 7) && 'file://' != mb_substr($_POST['boardurl'], 0, 7) && 'https://' != mb_substr($_POST['boardurl'], 0, 8)) {
        $_POST['boardurl'] = 'http://' . $_POST['boardurl'];
    }

    // Any passwords?

    $config_passwords = [
        'db_passwd',
    ];

    // All the strings to write.

    $config_strs = [
        'mtitle',
        'mmessage',
        'language',
        'mbname',
        'boardurl',
        'cookiename',
        'webmaster_email',
        'db_name',
        'db_user',
        'db_server',
        'db_prefix',
        'boarddir',
        'sourcedir',
    ];

    // All the numeric variables.

    $config_ints = [];

    // All the checkboxes.

    $config_bools = [
        'db_persist',
        'db_error_send',
        'maintenance',
    ];

    // Now sort everything into a big array, and figure out arrays and etc.

    $config_vars = [];

    foreach ($config_passwords as $config_var) {
        if (isset($_POST[$config_var][1]) && $_POST[$config_var][0] == $_POST[$config_var][1]) {
            $config_vars[$config_var] = '\'' . addcslashes($_POST[$config_var][0], "'\\") . '\'';
        }
    }

    foreach ($config_strs as $config_var) {
        if (isset($_POST[$config_var])) {
            $config_vars[$config_var] = '\'' . addcslashes($_POST[$config_var], "'\\") . '\'';
        }
    }

    foreach ($config_ints as $config_var) {
        if (isset($_POST[$config_var])) {
            $config_vars[$config_var] = (int)$_POST[$config_var];
        }
    }

    foreach ($config_bools as $key) {
        if (!empty($_POST[$key])) {
            $config_vars[$key] = '1';
        } else {
            $config_vars[$key] = '0';
        }
    }

    require_once $sourcedir . '/Admin.php';

    updateSettingsFile($config_vars);

    // If the cookie name was changed, reset the cookie.

    if (isset($config_vars['cookiename']) && $cookiename != $_POST['cookiename']) {
        require_once $sourcedir . '/Subs-Auth.php';

        $cookiename = $_POST['cookiename'];

        setLoginCookie(60 * $modSettings['cookieTime'], $user_settings['ID_MEMBER'], sha1($user_settings['passwd'] . $user_settings['passwordSalt']));

        redirectexit('action=serversettings;sa=core;sesc=' . $sc, $context['server']['needs_login_fix']);
    }

    redirectexit('action=serversettings;sa=core;sesc=' . $sc);
}

// This function basically edits anything which is configuration and stored in the database, except for caching.
function ModifyOtherSettings()
{
    global $context, $scripturl, $txt, $helptxt, $sc, $modSettings;

    // In later life we may move the setting definitions out of the language files, but for now it's RC2 and I can't be bothered.

    loadLanguage('ModSettings');

    loadLanguage('Help');

    // Define the variables we want to edit.

    $config_vars = [
        // SMTP stuff.
        ['select', 'mail_type', [$txt['mail_type_default'], 'SMTP']],
        ['text', 'smtp_host'],
        ['text', 'smtp_port'],
        ['text', 'smtp_username'],
        ['password', 'smtp_password'],
        '',
        // Cookies...
        ['int', 'cookieTime'],
        ['check', 'localCookies'],
        ['check', 'globalCookies'],
        '',
        // Database reapir, optimization, etc.
        ['int', 'autoOptDatabase'],
        ['int', 'autoOptMaxOnline'],
        ['check', 'autoFixDatabase'],
        '',
        ['check', 'enableCompressedOutput'],
        ['check', 'databaseSession_enable'],
        ['check', 'databaseSession_loose'],
        ['int', 'databaseSession_lifetime'],
    ];

    // Are we saving?

    if (isset($_GET['save'])) {
        // Make the SMTP password a little harder to see in a backup etc.

        if (!empty($_POST['smtp_password'][1])) {
            $_POST['smtp_password'][0] = base64_encode($_POST['smtp_password'][0]);

            $_POST['smtp_password'][1] = base64_encode($_POST['smtp_password'][1]);
        }

        saveDBSettings($config_vars);

        redirectexit('action=serversettings;sa=other;sesc=' . $sc);
    }

    $context['post_url'] = $scripturl . '?action=serversettings2;save;sa=other';

    $context['settings_title'] = $txt['other_configuration'];

    // Prepare the template.

    prepareDBSettingContext($config_vars);
}

// Simply modifying cache functions
function ModifyCacheSettings()
{
    global $context, $scripturl, $txt, $helptxt, $sc, $modSettings;

    // Cache information is in here, honest.

    loadLanguage('ModSettings');

    loadLanguage('Help');

    // Define the variables we want to edit.

    $config_vars = [
        // Only a couple of settings, but they are important
        ['select', 'cache_enable', [$txt['cache_off'], $txt['cache_level1'], $txt['cache_level2'], $txt['cache_level3']]],
        ['text', 'cache_memcached'],
    ];

    // Saving again?

    if (isset($_GET['save'])) {
        saveDBSettings($config_vars);

        redirectexit('action=serversettings;sa=cache;sesc=' . $sc);
    }

    $context['post_url'] = $scripturl . '?action=serversettings2;save;sa=cache';

    $context['settings_title'] = $txt['caching_settings'];

    $context['settings_message'] = $txt['caching_information'];

    // Detect an optimizer?

    if (function_exists('eaccelerator_put')) {
        $detected = 'eAccelerator';
    } elseif (function_exists('mmcache_put')) {
        $detected = 'MMCache';
    } elseif (function_exists('apc_store')) {
        $detected = 'APC';
    } elseif (function_exists('output_cache_put')) {
        $detected = 'Zend';
    } else {
        $detected = 'no_caching';
    }

    $context['settings_message'] = sprintf($context['settings_message'], $txt['detected_' . $detected]);

    // Prepare the template.

    prepareDBSettingContext($config_vars);
}

// Helper function, it sets up the context for database settings.
function prepareDBSettingContext($config_vars)
{
    global $txt, $helptxt, $context, $modSettings;

    $context['config_vars'] = [];

    foreach ($config_vars as $config_var) {
        if (!is_array($config_var) || !isset($config_var[1])) {
            $context['config_vars'][] = $config_var;
        } else {
            $context['config_vars'][$config_var[1]] = [
                'label' => $txt[$config_var[1]] ?? (isset($config_var[3]) && !is_array($config_var[3]) ? $config_var[3] : ''),
                'help' => isset($helptxt[$config_var[1]]) ? $config_var[1] : '',
                'type' => $config_var[0],
                'size' => !empty($config_var[2]) && !is_array($config_var[2]) ? $config_var[2] : (in_array($config_var[0], ['int', 'float'], true) ? 6 : 0),
                'data' => [],
                'name' => $config_var[1],
                'value' => isset($modSettings[$config_var[1]]) ? htmlspecialchars($modSettings[$config_var[1]], ENT_QUOTES | ENT_HTML5) : '',
                'disabled' => false,
            ];

            // If this is a select box handle any data.

            if (!empty($config_var[2]) && is_array($config_var[2])) {
                // If it's associative

                if (isset($config_var[2][0]) && is_array($config_var[2][0])) {
                    $context['config_vars'][$config_var[1]]['data'] = $config_var[2];
                } else {
                    foreach ($config_var[2] as $key => $item) {
                        $context['config_vars'][$config_var[1]]['data'][] = [$key, $item];
                    }
                }
            }
        }
    }
}

// Helper function for saving database settings.
function saveDBSettings($config_vars)
{
    foreach ($config_vars as $var) {
        if (!isset($var[1]) || !isset($_POST[$var[1]])) {
            continue;
        } // Checkboxes!

        elseif ('check' == $var[0]) {
            $setArray[$var[1]] = !empty($_POST[$var[1]]) ? '1' : '0';
        } // Select boxes!

        elseif ('select' == $var[0] && array_key_exists($_POST[$var[1]], $var[2])) {
            $setArray[$var[1]] = $_POST[$var[1]];
        } // Integers!

        elseif ('int' == $var[0]) {
            $setArray[$var[1]] = (int)$_POST[$var[1]];
        } // Floating point!

        elseif ('float' == $var[0]) {
            $setArray[$var[1]] = (float)$_POST[$var[1]];
        } // Text!

        elseif ('text' == $var[0] || 'large_text' == $var[0]) {
            $setArray[$var[1]] = $_POST[$var[1]];
        } // Passwords!

        elseif ('password' == $var[0]) {
            if (isset($_POST[$var[1]][1]) && $_POST[$var[1]][0] == $_POST[$var[1]][1]) {
                $setArray[$var[1]] = $_POST[$var[1]][0];
            }
        }
    }

    updateSettings($setArray);
}
