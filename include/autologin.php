<?php

global $xoopsUser;
if (defined('SMF')) {
    return;
}
if ($xoopsUser) {
    return;
}
require_once dirname(__DIR__) . '/Settings.php';
if (isset($_COOKIE[$cookiename])) {
    [, , $timeout] = @unserialize($_COOKIE[$cookiename]);

    define('SMF', 'AUTOLOGIN');

    // And important includes.

    require_once $sourcedir . '/QueryString.php';

    require_once $sourcedir . '/Subs.php';

    require_once $sourcedir . '/Subs-Auth.php';

    require_once $sourcedir . '/Errors.php';

    require_once $sourcedir . '/Load.php';

    require_once $sourcedir . '/Security.php';

    // Using an old version of PHP?

    if (1 != @version_compare(PHP_VERSION, '4.2.3')) {
        require_once $sourcedir . '/Subs-Compat.php';
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

    // Clean the request variables, add slashes, etc.

    cleanRequest();

    loadUserSettings();

    if (!empty($ID_MEMBER)) {
        $_SESSION['login_url'] = XOOPS_URL . '/' . $xoopsRequestUri;

        // Get ready to set the cookie...

        $username = $user_settings['memberName'];

        $ID_MEMBER = $user_settings['ID_MEMBER'];

        $user_settings['ip'] = $_SERVER['REMOTE_ADDR'];

        //print_r($user_settings);

        //exit;

        // Bam!  Cookie set.  A session too, just incase.

        setLoginCookie(3153600, $user_settings['ID_MEMBER'], sha1($user_settings['passwd'] . $user_settings['passwordSalt']));

        updateMemberData($ID_MEMBER, ['lastLogin' => time(), 'memberIP' => '\'' . $user_settings['ip'] . '\'', 'memberIP2' => '\'' . $_SERVER['BAN_CHECK_IP'] . '\'']);

        // Get rid of the online entry for that old guest....

        db_query(
            "
		  DELETE FROM {$db_prefix}log_online
		  WHERE session = 'ip$user_settings[ip]'
		  LIMIT 1",
            __FILE__,
            __LINE__
        );

        $_SESSION['log_time'] = 0;

        global $xoopsConfig;

        $memberHandler = xoops_getHandler('member');

        $myts = MyTextSanitizer::getInstance();

        require_once XOOPS_ROOT_PATH . '/class/auth/authfactory.php';

        require_once XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/user.php';

        $xoopsAuth = XoopsAuthFactory::getAuthConnection($myts->addSlashes($username));

        $user = $memberHandler->loginUserMd5($myts->addSlashes($username), $myts->addSlashes($user_settings['pass']));

        if (false !== $user) {
            if (0 == $user->getVar('level')) {
                redirect_header(XOOPS_URL . '/index.php', 5, _US_NOACTTPADM);

                exit();
            }

            if (1 == $xoopsConfig['closesite']) {
                $allowed = false;

                foreach ($user->getGroups() as $group) {
                    if (in_array($group, $xoopsConfig['closesite_okgrp'], true) || XOOPS_GROUP_ADMIN == $group) {
                        $allowed = true;

                        break;
                    }
                }

                if (!$allowed) {
                    redirect_header(XOOPS_URL . '/index.php', 1, _NOPERM);

                    exit();
                }
            }

            $user->setVar('last_login', time());

            if (!$memberHandler->insertUser($user)) {
            }

            $_SESSION = [];

            $_SESSION['xoopsUserId'] = $user->getVar('uid');

            $_SESSION['xoopsUserGroups'] = $user->getGroups();

            if ($xoopsConfig['use_mysession'] && '' != $xoopsConfig['session_name']) {
                setcookie($xoopsConfig['session_name'], session_id(), time() + (60 * $xoopsConfig['session_expire']), '/', '', 0);
            }

            $user_theme = $user->getVar('theme');

            if (in_array($user_theme, $xoopsConfig['theme_set_allowed'], true)) {
                $_SESSION['xoopsUserTheme'] = $user_theme;
            }

            $_SESSION['xoopsUserLastLogin'] = $user->getVar('last_login');

            if (!$memberHandler->updateUserByField($user, 'last_login', time())) {
            }

            if (!empty($_POST['xoops_redirect']) && !mb_strpos($_POST['xoops_redirect'], 'register')) {
                if (!preg_match("/^http[s]*:\/\//i", $_POST['xoops_redirect'])) {
                    $url = XOOPS_URL . trim($_POST['xoops_redirect']);
                } else {
                    $url = $_POST['xoops_redirect'];
                }
            } else {
                $url = XOOPS_URL . '/index.php';
            }

            redirect_header($url, 1, sprintf(_US_LOGGINGU, $user_settings['realName']), false);
        } else {
            redirect_header(XOOPS_URL . '/user.php', 1, _US_INCORRECTLOGIN);
        }
    }
}
