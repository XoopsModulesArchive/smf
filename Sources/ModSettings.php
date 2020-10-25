<?php

/**********************************************************************************
 * ModSettings.php                                                                 *
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

/*	This file is here to make it easier for installed mods to have settings
    and options.  It uses the following functions:

    void ModifyFeatureSettings()
        // !!!

    void ModifyFeatureSettings2()
        // !!!

    void ModifyBasicSettings()
        // !!!

    void ModifyLayoutSettings()
        // !!!

    void ModifyKarmaSettings()
        // !!!

    Adding new settings to the $modSettings array:
    ---------------------------------------------------------------------------
// !!!
*/

/*	Adding options to one of the setting screens isn't hard.  The basic format for a checkbox is:
        array('check', 'nameInModSettingsAndSQL'),

       And for a text box:
        array('text', 'nameInModSettingsAndSQL')
       (NOTE: You have to add an entry for this at the bottom!)

       In these cases, it will look for $txt['nameInModSettingsAndSQL'] as the description,
       and $helptxt['nameInModSettingsAndSQL'] as the help popup description.

    Here's a quick explanation of how to add a new item:

     * A text input box.  For textual values.
    ie.	array('text', 'nameInModSettingsAndSQL', 'OptionalInputBoxWidth',
            &$txt['OptionalDescriptionOfTheOption'], 'OptionalReferenceToHelpAdmin'),

     * A text input box.  For numerical values.
    ie.	array('int', 'nameInModSettingsAndSQL', 'OptionalInputBoxWidth',
            &$txt['OptionalDescriptionOfTheOption'], 'OptionalReferenceToHelpAdmin'),

     * A text input box.  For floating point values.
    ie.	array('float', 'nameInModSettingsAndSQL', 'OptionalInputBoxWidth',
            &$txt['OptionalDescriptionOfTheOption'], 'OptionalReferenceToHelpAdmin'),

         * A large text input box. Used for textual values spanning multiple lines.
    ie.	array('large_text', 'nameInModSettingsAndSQL', 'OptionalNumberOfRows',
            &$txt['OptionalDescriptionOfTheOption'], 'OptionalReferenceToHelpAdmin'),

     * A check box.  Either one or zero. (boolean)
    ie.	array('check', 'nameInModSettingsAndSQL', null, &$txt['descriptionOfTheOption'],
            'OptionalReferenceToHelpAdmin'),

     * A selection box.  Used for the selection of something from a list.
    ie.	array('select', 'nameInModSettingsAndSQL', array('valueForSQL' => &$txt['displayedValue']),
            &$txt['descriptionOfTheOption'], 'OptionalReferenceToHelpAdmin'),
    Note that just saying array('first', 'second') will put 0 in the SQL for 'first'.

     * A password input box. Used for passwords, no less!
    ie.	array('password', 'nameInModSettingsAndSQL', 'OptionalInputBoxWidth',
            &$txt['descriptionOfTheOption'], 'OptionalReferenceToHelpAdmin'),

    For each option:
        type (see above), variable name, size/possible values, description, helptext.
    OR	make type 'rule' for an empty string for a horizontal rule.
    OR	make type 'heading' with a string for a titled section. */

// This function passes control through to the relevant tab.
function ModifyFeatureSettings()
{
    global $context, $txt, $scripturl, $modSettings, $sourcedir;

    // You need to be an admin to edit settings!

    isAllowedTo('admin_forum');

    // All the admin bar, to make it right.

    adminIndex('edit_mods_settings');

    loadLanguage('Help');

    loadLanguage('ModSettings');

    // Will need the utility functions from here.

    require_once $sourcedir . '/ManageServer.php';

    $context['page_title'] = $txt['modSettings_title'];

    $context['sub_template'] = 'show_settings';

    $subActions = [
        'basic' => 'ModifyBasicSettings',
        'layout' => 'ModifyLayoutSettings',
        'googlebot' => 'ModifyGooglebotSettings',
        'karma' => 'ModifyKarmaSettings',
    ];

    // By default do the basic settings.

    $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'basic';

    $context['sub_action'] = $_REQUEST['sa'];

    // Load up all the tabs...

    $context['admin_tabs'] = [
        'title' => &$txt['modSettings_title'],
        'help' => 'modsettings',
        'description' => $txt['smf3'],
        'tabs' => [
            'basic' => [
                'title' => $txt['mods_cat_features'],
                'href' => $scripturl . '?action=featuresettings;sa=basic;sesc=' . $context['session_id'],
            ],
            'layout' => [
                'title' => $txt['mods_cat_layout'],
                'href' => $scripturl . '?action=featuresettings;sa=layout;sesc=' . $context['session_id'],
            ],
            'googlebot' => [
                'title' => $txt['ob_googlebot_modname'],
                'href' => $scripturl . '?action=featuresettings;sa=googlebot;sesc=' . $context['session_id'],
            ],
            'karma' => [
                'title' => $txt['smf293'],
                'href' => $scripturl . '?action=featuresettings;sa=karma;sesc=' . $context['session_id'],
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
function ModifyFeatureSettings2()
{
    global $context, $txt, $scripturl, $modSettings, $sourcedir;

    isAllowedTo('admin_forum');

    loadLanguage('ModSettings');

    // Quick session check...

    checkSession();

    require_once $sourcedir . '/ManageServer.php';

    $subActions = [
        'basic' => 'ModifyBasicSettings',
        'layout' => 'ModifyLayoutSettings',
        'googlebot' => 'ModifyGooglebotSettings',
        'karma' => 'ModifyKarmaSettings',
    ];

    // Default to core (I assume)

    $_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'basic';

    // Actually call the saving function.

    $subActions[$_REQUEST['sa']]();
}

function ModifyBasicSettings()
{
    global $txt, $scripturl, $context, $settings, $sc, $modSettings;

    $config_vars = [
        // Big Options... polls, sticky, bbc....
        ['select', 'pollMode', [&$txt['smf34'], &$txt['smf32'], &$txt['smf33']]],
        '',
        // Basic stuff, user languages, titles, flash, permissions...
        ['check', 'allow_guestAccess'],
        ['check', 'userLanguage'],
        ['check', 'allow_editDisplayName'],
        ['check', 'allow_hideOnline'],
        ['check', 'allow_hideEmail'],
        ['check', 'guest_hideContacts'],
        ['check', 'titlesEnable'],
        ['check', 'enable_buddylist'],
        ['text', 'default_personalText'],
        ['int', 'max_signatureLength'],
        '',
        // Stats, compression, cookies.... server type stuff.
        ['text', 'time_format'],
        ['select', 'number_format', ['1234.00' => '1234.00', '1,234.00' => '1,234.00', '1.234,00' => '1.234,00', '1 234,00' => '1 234,00', '1234,00' => '1234,00']],
        ['float', 'time_offset'],
        ['int', 'failed_login_threshold'],
        ['int', 'lastActive'],
        ['check', 'trackStats'],
        ['check', 'hitStats'],
        ['check', 'enableErrorLogging'],
        ['check', 'securityDisable'],
        '',
        // Reactive on email, and approve on delete
        ['check', 'send_validation_onChange'],
        ['check', 'approveAccountDeletion'],
        '',
        // Option-ish things... miscellaneous sorta.
        ['check', 'allow_disableAnnounce'],
        ['check', 'disallow_sendBody'],
        ['check', 'modlog_enabled'],
        ['check', 'queryless_urls'],
        '',
        // Width/Height image reduction.
        ['int', 'max_image_width'],
        ['int', 'max_image_height'],
        '',
        // Reporting of personal messages?
        ['check', 'enableReportPM'],
    ];

    // Saving?

    if (isset($_GET['save'])) {
        // Fix PM settings.

        $_POST['pm_spam_settings'] = (int)$_POST['max_pm_recipients'] . ',' . (int)$_POST['pm_posts_verification'] . ',' . (int)$_POST['pm_posts_per_hour'];

        $save_vars = $config_vars;

        $save_vars[] = ['text', 'pm_spam_settings'];

        saveDBSettings($save_vars);

        writeLog();

        redirectexit('action=featuresettings;sa=basic');
    }

    // Hack for PM spam settings.

    [$modSettings['max_pm_recipients'], $modSettings['pm_posts_verification'], $modSettings['pm_posts_per_hour']] = explode(',', $modSettings['pm_spam_settings']);

    $config_vars[] = ['int', 'max_pm_recipients'];

    $config_vars[] = ['int', 'pm_posts_verification'];

    $config_vars[] = ['int', 'pm_posts_per_hour'];

    $context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=basic';

    $context['settings_title'] = $txt['mods_cat_features'];

    prepareDBSettingContext($config_vars);
}

function ModifyLayoutSettings()
{
    global $txt, $scripturl, $context, $settings, $sc;

    $config_vars = [
        // Compact pages?
        ['check', 'compactTopicPagesEnable'],
        ['int', 'compactTopicPagesContiguous', null, $txt['smf235'] . '<div class="smalltext">' . str_replace(' ', '&nbsp;', '"3" ' . $txt['smf236'] . ': <b>1 ... 4 [5] 6 ... 9</b>') . '<br>' . str_replace(' ', '&nbsp;', '"5" ' . $txt['smf236'] . ': <b>1 ... 3 4 [5] 6 7 ... 9</b>') . '</div>'],
        '',
        // Stuff that just is everywhere - today, search, online, etc.
        ['select', 'todayMod', [&$txt['smf290'], &$txt['smf291'], &$txt['smf292']]],
        ['check', 'topbottomEnable'],
        ['check', 'onlineEnable'],
        ['check', 'enableVBStyleLogin'],
        '',
        // Pagination stuff.
        ['int', 'defaultMaxMembers'],
        '',
        // This is like debugging sorta.
        ['check', 'timeLoadPageEnable'],
        ['check', 'disableHostnameLookup'],
        '',
        // Who's online.
        ['check', 'who_enabled'],
    ];

    // Saving?

    if (isset($_GET['save'])) {
        saveDBSettings($config_vars);

        redirectexit('action=featuresettings;sa=layout');

        loadUserSettings();

        writeLog();
    }

    $context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=layout';

    $context['settings_title'] = $txt['mods_cat_layout'];

    prepareDBSettingContext($config_vars);
}

function ModifyKarmaSettings()
{
    global $txt, $scripturl, $context, $settings, $sc;

    $config_vars = [
        // Karma - On or off?
        ['select', 'karmaMode', explode('|', $txt['smf64'])],
        '',
        // Who can do it.... and who is restricted by time limits?
        ['int', 'karmaMinPosts'],
        ['float', 'karmaWaitTime'],
        ['check', 'karmaTimeRestrictAdmins'],
        '',
        // What does it look like?  [smite]?
        ['text', 'karmaLabel'],
        ['text', 'karmaApplaudLabel'],
        ['text', 'karmaSmiteLabel'],
    ];

    // Saving?

    if (isset($_GET['save'])) {
        saveDBSettings($config_vars);

        redirectexit('action=featuresettings;sa=karma');
    }

    $context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=karma';

    $context['settings_title'] = $txt['smf293'];

    prepareDBSettingContext($config_vars);
}

function ModifyGooglebotSettings()
{
    global $txt, $scripturl, $context, $settings, $sc;

    $config_vars = [
        // Count all instances of spiders?
        ['check', 'ob_googlebot_count_all_instances'],
        ['check', 'ob_googlebot_display_all_instances'],
        ['check', 'ob_googlebot_display_agent'],
        ['check', 'ob_googlebot_display_own_list'],
        '',
        // Count spiders on most online?
        ['check', 'ob_googlebot_count_most_online'],
        '',
        // Redirect PHPSESSID URLs?
        ['check', 'ob_googlebot_redirect_phpsessid'],
    ];

    // Saving?

    if (isset($_GET['save'])) {
        saveDBSettings($config_vars);

        redirectexit('action=featuresettings;sa=googlebot');
    }

    $context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=googlebot';

    $context['settings_title'] = $txt['ob_googlebot_modname'];

    prepareDBSettingContext($config_vars);
}
