<?php
/**********************************************************************************
 * index.php                                                                       *
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

/*	This, as you have probably guessed, is the crux on which SMF functions.
    Everything should start here, so all the setup and security is done
    properly.  The most interesting part of this file is the action array in
    the smf_main() function.  It is formatted as so:

        'action-in-url' => array('Source-File.php', 'FunctionToCall'),

    Then, you can access the FunctionToCall() function from Source-File.php
    with the URL index.php?action=action-in-url.  Relatively simple, no?
*/
// XOOPS-Integration Start
$time_start = microtime();
require_once __DIR__ . '/xheader.php';

$forum_version = 'SMF 1.1.2';

// Get everything started up...
define('SMF', 1);

@set_magic_quotes_runtime(0);
error_reporting(E_ALL);

// Load the settings...
require_once __DIR__ . '/Settings.php';

// And important includes.
require_once $sourcedir . '/QueryString.php';
require_once $sourcedir . '/Subs.php';
require_once $sourcedir . '/Errors.php';
require_once $sourcedir . '/Load.php';
require_once $sourcedir . '/Security.php';

// Using an old version of PHP?
if (1 != @version_compare(PHP_VERSION, '4.2.3')) {
    require_once $sourcedir . '/Subs-Compat.php';
}

// If $maintenance is set specifically to 2, then we're upgrading or something.
if (!empty($maintenance) && 2 == $maintenance) {
    db_fatal_error();
}

// Connect to the MySQL database.
if (empty($db_persist)) {
    $db_connection = @mysql_connect($db_server, $db_user, $db_passwd);
} else {
    $db_connection = @mysql_pconnect($db_server, $db_user, $db_passwd);
}

// Show an error if the connection couldn't be made.
if (!$db_connection || !@mysqli_select_db($GLOBALS['xoopsDB']->conn, $db_name, $db_connection)) {
    db_fatal_error();
}

// Load the settings from the settings table, and perform operations like optimizing.
reloadSettings();

//Get rid of ?PHPSESSID in the case is a Googlebot any other Spider. Even if is a user (maybe User-Agent extension), will be redirected. Easier this way.
if ($modSettings['ob_googlebot_redirect_phpsessid'] && ob_googlebot_getAgent($_SERVER['HTTP_USER_AGENT'], $spider_name, $agent)) {
    $actualurl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $correcturl = preg_replace('/([?&]PHPSESSID=[^&]*)/', '', $actualurl);

    $correcturl = str_replace('index.php&', 'index.php?', $correcturl);

    if ($correcturl != $actualurl) {
        header('HTTP/1.1 301 Moved Permanently');

        header('Location: ' . $correcturl);

        exit();
    }
}

// Clean the request variables, add slashes, etc.
cleanRequest();
$context = [];

// Determine if this is using WAP, WAP2, or imode.  Technically, we should check that wap comes before application/xhtml or text/html, but this doesn't work in practice as much as it should.
if (isset($_SERVER['HTTP_ACCEPT']) && false !== mb_strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml')) {
    $_REQUEST['wap2'] = 1;
} elseif (isset($_SERVER['HTTP_ACCEPT']) && false !== mb_strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml')) {
    if (false !== mb_strpos($_SERVER['HTTP_USER_AGENT'], 'DoCoMo/') || false !== mb_strpos($_SERVER['HTTP_USER_AGENT'], 'portalmmm/')) {
        $_REQUEST['imode'] = 1;
    } else {
        $_REQUEST['wap'] = 1;
    }
}

if (!defined('WIRELESS')) {
    define('WIRELESS', isset($_REQUEST['wap']) || isset($_REQUEST['wap2']) || isset($_REQUEST['imode']));
}

// Some settings and headers are different for wireless protocols.
if (WIRELESS) {
    define('WIRELESS_PROTOCOL', isset($_REQUEST['wap']) ? 'wap' : (isset($_REQUEST['wap2']) ? 'wap2' : (isset($_REQUEST['imode']) ? 'imode' : '')));

    // Some cellphones can't handle output compression...

    $modSettings['enableCompressedOutput'] = '0';

    // !!! Do we want these hard coded?

    $modSettings['defaultMaxMessages'] = 5;

    $modSettings['defaultMaxTopics'] = 9;

    // Wireless protocol header.

    if (WIRELESS_PROTOCOL == 'wap') {
        header('Content-Type: text/vnd.wap.wml');
    }
}

// Check if compressed output is enabled, supported, and not already being done.
if (!empty($modSettings['enableCompressedOutput']) && !headers_sent() && 0 == ob_get_length()) {
    // If zlib is being used, turn off output compression.

    if ('1' == @ini_get('zlib.output_compression') || 'ob_gzhandler' == @ini_get('outputHandler') || -1 == @version_compare(PHP_VERSION, '4.2.0')) {
        $modSettings['enableCompressedOutput'] = '0';
    } else {
        ob_start('ob_gzhandler');
    }
}
// This makes it so headers can be sent!
if (empty($modSettings['enableCompressedOutput'])) {
    ob_start();
}

// Register an error handler.
set_error_handler('errorHandler');

// Start the session. (assuming it hasn't already been.)
loadSession();

// What function shall we execute? (done like this for memory's sake.)
call_user_func(smf_main());

// Call obExit specially; we're coming from the main area ;).
obExit(null, null, true);

// The main controlling function.
function smf_main()
{
    global $modSettings, $settings, $user_info, $board, $topic, $maintenance, $sourcedir;

    // Special case: session keep-alive.

    if (isset($_GET['action']) && 'keepalive' == $_GET['action']) {
        die;
    }

    // Load the user's cookie (or set as guest) and load their settings.

    loadUserSettings();

    // Load the current board's information.

    loadBoard();

    // Load the current theme.  (note that ?theme=1 will also work, may be used for guest theming.)

    loadTheme();

    // Check if the user should be disallowed access.

    is_not_banned();

    // Load the current user's permissions.

    loadPermissions();

    // Do some logging, unless this is an attachment, avatar, theme option or XML feed.

    if (empty($_REQUEST['action']) || !in_array($_REQUEST['action'], ['dlattach', 'jsoption', '.xml'], true)) {
        // Log this user as online.

        writeLog();

        // Track forum statistics and hits...?

        if (!empty($modSettings['hitStats'])) {
            trackStats(['hits' => '+']);
        }
    }

    // Is the forum in maintenance mode? (doesn't apply to administrators.)

    if (!empty($maintenance) && !allowedTo('admin_forum')) {
        // You can only login.... otherwise, you're getting the "maintenance mode" display.

        if (isset($_REQUEST['action']) && 'login2' == $_REQUEST['action']) {
            require_once $sourcedir . '/LogInOut.php';

            return 'Login2';
        } // Don't even try it, sonny.

        require_once $sourcedir . '/Subs-Auth.php';

        return 'InMaintenance';
    } // If guest access is off, a guest can only do one of the very few following actions.

    elseif (empty($modSettings['allow_guestAccess']) && $user_info['is_guest'] && (!isset($_REQUEST['action']) || !in_array($_REQUEST['action'], ['login', 'login2', 'register', 'register2', 'reminder', 'activate', 'smstats', 'help', '.xml', 'verificationcode'], true))) {
        require_once $sourcedir . '/Subs-Auth.php';

        return 'KickGuest';
    } elseif (empty($_REQUEST['action'])) {
        // Action and board are both empty... BoardIndex!

        if (empty($board) && empty($topic)) {
            require_once $sourcedir . '/BoardIndex.php';

            return 'BoardIndex';
        } // Topic is empty, and action is empty.... MessageIndex!

        elseif (empty($topic)) {
            require_once $sourcedir . '/MessageIndex.php';

            return 'MessageIndex';
        } // Board is not empty... topic is not empty... action is empty.. Display!

        require_once $sourcedir . '/Display.php';

        return 'Display';
    }

    // Here's the monstrous $_REQUEST['action'] array - $_REQUEST['action'] => array($file, $function).

    $actionArray = [
        'activate' => ['Register.php', 'Activate'],
        'admin' => ['Admin.php', 'Admin'],
        'announce' => ['Post.php', 'AnnounceTopic'],
        'ban' => ['ManageBans.php', 'Ban'],
        'boardrecount' => ['Admin.php', 'AdminBoardRecount'],
        'buddy' => ['Subs-Members.php', 'BuddyListToggle'],
        'calendar' => ['Calendar.php', 'CalendarMain'],
        'cleanperms' => ['Admin.php', 'CleanupPermissions'],
        'collapse' => ['Subs-Boards.php', 'CollapseCategory'],
        'convertentities' => ['Admin.php', 'ConvertEntities'],
        'convertutf8' => ['Admin.php', 'ConvertUtf8'],
        'coppa' => ['Register.php', 'CoppaForm'],
        'deletemsg' => ['RemoveTopic.php', 'DeleteMessage'],
        'detailedversion' => ['Admin.php', 'VersionDetail'],
        'display' => ['Display.php', 'Display'],
        'dlattach' => ['Display.php', 'Download'],
        'dumpdb' => ['DumpDatabase.php', 'DumpDatabase2'],
        'editpoll' => ['Poll.php', 'EditPoll'],
        'editpoll2' => ['Poll.php', 'EditPoll2'],
        'featuresettings' => ['ModSettings.php', 'ModifyFeatureSettings'],
        'featuresettings2' => ['ModSettings.php', 'ModifyFeatureSettings2'],
        'findmember' => ['Subs-Auth.php', 'JSMembers'],
        'help' => ['Help.php', 'ShowHelp'],
        'helpadmin' => ['Help.php', 'ShowAdminHelp'],
        'im' => ['PersonalMessage.php', 'MessageMain'],
        'jsoption' => ['Themes.php', 'SetJavaScript'],
        'jsmodify' => ['Post.php', 'JavaScriptModify'],
        'lock' => ['LockTopic.php', 'LockTopic'],
        'lockVoting' => ['Poll.php', 'LockVoting'],
        'login' => ['LogInOut.php', 'Login'],
        'login2' => ['LogInOut.php', 'Login2'],
        'logout' => ['LogInOut.php', 'Logout'],
        'maintain' => ['Admin.php', 'Maintenance'],
        'manageattachments' => ['ManageAttachments.php', 'ManageAttachments'],
        'manageboards' => ['ManageBoards.php', 'ManageBoards'],
        'managecalendar' => ['ManageCalendar.php', 'ManageCalendar'],
        'managesearch' => ['ManageSearch.php', 'ManageSearch'],
        'markasread' => ['Subs-Boards.php', 'MarkRead'],
        'membergroups' => ['ManageMembergroups.php', 'ModifyMembergroups'],
        'mergetopics' => ['SplitTopics.php', 'MergeTopics'],
        'mlist' => ['Memberlist.php', 'Memberlist'],
        'modifycat' => ['ManageBoards.php', 'ModifyCat'],
        'modifykarma' => ['Karma.php', 'ModifyKarma'],
        'modlog' => ['Modlog.php', 'ViewModlog'],
        'movetopic' => ['MoveTopic.php', 'MoveTopic'],
        'movetopic2' => ['MoveTopic.php', 'MoveTopic2'],
        'news' => ['ManageNews.php', 'ManageNews'],
        'notify' => ['Notify.php', 'Notify'],
        'notifyboard' => ['Notify.php', 'BoardNotify'],
        'optimizetables' => ['Admin.php', 'OptimizeTables'],
        'packageget' => ['PackageGet.php', 'PackageGet'],
        'packages' => ['Packages.php', 'Packages'],
        'permissions' => ['ManagePermissions.php', 'ModifyPermissions'],
        'pgdownload' => ['PackageGet.php', 'PackageGet'],
        'pm' => ['PersonalMessage.php', 'MessageMain'],
        'post' => ['Post.php', 'Post'],
        'post2' => ['Post.php', 'Post2'],
        'postsettings' => ['ManagePosts.php', 'ManagePostSettings'],
        'printpage' => ['Printpage.php', 'PrintTopic'],
        'profile' => ['Profile.php', 'ModifyProfile'],
        'profile2' => ['Profile.php', 'ModifyProfile2'],
        'quotefast' => ['Post.php', 'QuoteFast'],
        'quickmod' => ['Subs-Boards.php', 'QuickModeration'],
        'quickmod2' => ['Subs-Boards.php', 'QuickModeration2'],
        'recent' => ['Recent.php', 'RecentPosts'],
        'regcenter' => ['ManageRegistration.php', 'RegCenter'],
        'register' => ['Register.php', 'Register'],
        'register2' => ['Register.php', 'Register2'],
        'reminder' => ['Reminder.php', 'RemindMe'],
        'removetopic2' => ['RemoveTopic.php', 'RemoveTopic2'],
        'removeoldtopics2' => ['RemoveTopic.php', 'RemoveOldTopics2'],
        'removepoll' => ['Poll.php', 'RemovePoll'],
        'repairboards' => ['RepairBoards.php', 'RepairBoards'],
        'reporttm' => ['SendTopic.php', 'ReportToModerator'],
        'reports' => ['Reports.php', 'ReportsMain'],
        'requestmembers' => ['Subs-Auth.php', 'RequestMembers'],
        'search' => ['Search.php', 'PlushSearch1'],
        'search2' => ['Search.php', 'PlushSearch2'],
        'sendtopic' => ['SendTopic.php', 'SendTopic'],
        'serversettings' => ['ManageServer.php', 'ModifySettings'],
        'serversettings2' => ['ManageServer.php', 'ModifySettings2'],
        'smileys' => ['ManageSmileys.php', 'ManageSmileys'],
        'smstats' => ['Stats.php', 'SMStats'],
        'spellcheck' => ['Subs-Post.php', 'SpellCheck'],
        'splittopics' => ['SplitTopics.php', 'SplitTopics'],
        'stats' => ['Stats.php', 'DisplayStats'],
        'sticky' => ['LockTopic.php', 'Sticky'],
        'theme' => ['Themes.php', 'ThemesMain'],
        'trackip' => ['Profile.php', 'trackIP'],
        'about:mozilla' => ['Karma.php', 'BookOfUnknown'],
        'about:unknown' => ['Karma.php', 'BookOfUnknown'],
        'unread' => ['Recent.php', 'UnreadTopics'],
        'unreadreplies' => ['Recent.php', 'UnreadTopics'],
        'viewErrorLog' => ['ManageErrors.php', 'ViewErrorLog'],
        'viewmembers' => ['ManageMembers.php', 'ViewMembers'],
        'viewprofile' => ['Profile.php', 'ModifyProfile'],
        'verificationcode' => ['Register.php', 'VerificationCode'],
        'vote' => ['Poll.php', 'Vote'],
        'viewquery' => ['ViewQuery.php', 'ViewQuery'],
        'useremail' => ['User_Email.php', 'UserEmailMain'],
        'who' => ['Who.php', 'Who'],
        '.xml' => ['News.php', 'ShowXmlFeed'],
    ];

    // Get the function and file to include - if it's not there, do the board index.

    if (!isset($_REQUEST['action']) || !isset($actionArray[$_REQUEST['action']])) {
        // Catch the action with the theme?

        if (!empty($settings['catch_action'])) {
            require_once $sourcedir . '/Themes.php';

            return 'WrapAction';
        }

        // Fall through to the board index then...

        require_once $sourcedir . '/BoardIndex.php';

        return 'BoardIndex';
    }

    // Otherwise, it was set - so let's go to that action.

    require_once $sourcedir . '/' . $actionArray[$_REQUEST['action']][0];

    return $actionArray[$_REQUEST['action']][1];
}

// XOOPS-Integration END
require __DIR__ . '/xfooter.php';
