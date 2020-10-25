<?php
/**********************************************************************************
 * index.php                                                                       *
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

/*	This file is a Xoops module for SMF.  It uses
    the following functions:

    string ob_xoopsfix(string text)
    - fixes URLs in SMF to point to Xoops module

    bool integrate_redirect (&$setLocation, $refresh)
    - sets redirection in forum for form submissions

    integrate_pre_load ()
    - starts up the SMF session variables for use in the Xoops module

    xoops_smf_exit(string $with_output)
    - exits SMF securely

    integrate_login ($user, $password, $cookietime)
    - logs in user to Xoops upon validation in SMF

    integrate_logout($user)
    - logs user out of Xoops

    integrate_outgoing_email ($subject, &$message, $headers)
    - fixes URLs in email messages to point to Xoops module

    integrate register ($regOptions, $theme_vars)
    - registers new users in Xoops

    integrate_validate_login ($username, $password, $cookietime)
    - validates user exists in Xoops, and writes the user into SMF if there is no user in SMF

*/
require_once dirname(__DIR__, 2) . '/mainfile.php';
$xoopsLogger->activated = false;
$smfUrl['root'] = XOOPS_URL;
$smfUrl['admin'] = $smfUrl['root'] . '/modules/' . $xoopsModule->dirname() . '/' . 'admin';
$smfUrl['images'] = $smfUrl['root'] . '/modules/' . $xoopsModule->dirname() . '/' . 'images';
global $scripturl, $context, $sc, $xoopsTpl, $xoopsModuleConfig, $settings, $smf_header, $xoopsTpl, $xoopsOption;
global $db_connection;

$context['disable_login_hashing'] = false;

//define the integration functions
define(
    'SMF_INTEGRATION_SETTINGS',
    serialize(
        [
            'integrate_change_member_data' => 'integrate_change_member_data',
            'integrate_change_email' => 'integrate_change_email',
            'integrate_reset_pass' => 'integrate_reset_pass',
            'integrate_exit' => 'xoops_smf_exit',
            'integrate_logout' => 'integrate_logout',
            'integrate_outgoing_email' => 'integrate_outgoing_email',
            'integrate_login' => 'integrate_login',
            'integrate_validate_login' => 'integrate_validate_login',
            'integrate_redirect' => 'integrate_redirect',
            'integrate_delete_member' => 'integrate_delete_member',
            'integrate_register' => 'integrate_register',
            'integrate_pre_load' => 'integrate_pre_load',
            'integrate_verify_user' => 'integrate_verify_user',
        ]
    )
);

// Let's get this integration started...
$xheader = 1;
if (!empty($_GET['action'])) {
    if ('helpadmin' == mb_substr($_GET['action'], 0, 9) || 'logout' == mb_substr($_GET['action'], 0, 6)) {
        $xheader = 0;
    } //elseif ($_GET['action']=="profile2") $xheader = 0;

    elseif ('permissions;sa=modify2' == mb_substr($_GET['action'], 0, 22)) {
        $xheader = 0;
    }
}
if (!empty($_POST['delall'])) {
    $xheader = 0;
}
//print_r($_POST);
//echo $_GET['action'];
// Just in case it gets flushed in the middle for any reason..
if (1 == (int)$xheader) {
    require XOOPS_ROOT_PATH . '/header.php';
}

ob_start('ob_xoopsfix');
ob_start();

function ob_xoopsfix($buffer)
{
    global $scripturl, $xoopsModule, $xoopsModuleConfig;

    $buffer = str_replace($scripturl, XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/index.php', $buffer);

    $buffer = str_replace('name="seqnum" value="0"', 'name="seqnum" value="1"', $buffer);

    if (1 == $xoopsModuleConfig['wrapped'] && 0 == $xoopsModuleConfig['xoopsregister']) {
        $buffer = str_replace('modules/' . $xoopsModule->dirname() . '/index.php?action=register', 'register.php', $buffer);
    }

    return $buffer;
}

function integrate_verify_user()
{
    // check Autologin for XOOPS
}

function integrate_reset_pass($user1, $user2, $newPassword)
{
    $xprefix = !defined('XOOPS_SITE_PREFIX') ? XOOPS_DB_PREFIX : XOOPS_SITE_PREFIX;

    mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME);

    $request = $GLOBALS['xoopsDB']->queryF(
        '
		UPDATE ' . $xprefix . "_users
		SET pass = '" . md5($newPassword) . "'
		WHERE loginname = '" . $user1 . "'"
    );
}

function integrate_change_member_data($memberNames, $var, $data)
{
    if (!is_array($memberNames)) {
        return false;
    }

    $xoops = [
        'emailAddress' => 'email',
        'realName' => 'uname',
        'memberName' => 'loginname',
    ];

    $xoops2 = ['websiteUrl' => 'url'];

    if (!empty($xoops[$var])) {
        foreach ($memberNames as $member) {
            $xprefix = !defined('XOOPS_SITE_PREFIX') ? XOOPS_DB_PREFIX : XOOPS_SITE_PREFIX;

            mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME);

            $sql = 'UPDATE ' . $xprefix . '_users
		      SET ' . $xoops[$var] . ' = ' . $data . "
		      WHERE loginname = '" . $member . "'";

            $request = $GLOBALS['xoopsDB']->queryF($sql);
        }
    }
}

function integrate_delete_member($user)
{
    if (is_array($user)) {
        die('Array');
    }

    mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME);

    // Gruppenrechte löschen

    $request = $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $xprefix . '_groups_users_link	WHERE uid = ' . $user);

    $request = $GLOBALS['xoopsDB']->queryF('SELECT profileid FROM ' . $xprefix . '_user_profile LIMIT 1');

    if (false === $request || 0 === $GLOBALS['xoopsDB']->getRowsNum($request)) { // XOOPS 2.0
    } else {
        // erweiterte Profile entfernen

        $request = $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $xprefix . '_user_profile	WHERE profileid = ' . $user);
    }
}

function integrate_redirect($setLocation, $refresh)
{
    global $boardurl, $xoopsModule, $xoopsRequestUri;

    if ('' == $setLocation) {
        $setLocation = XOOPS_URL . '/index.php';
    }

    $setLocation = un_htmlspecialchars(ob_xoopsfix($setLocation));

    return true;
}

function integrate_outgoing_email($subject, &$message, $headers)
{
    global $boardurl, $xoopsModule;

    $myurl = XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/index.php';

    $message = un_htmlspecialchars(ob_xoopsfix($message));

    return true;
}

function integrate_pre_load()
{
    global $modSettings, $sc, $context, $xoopsModule, $xoopsRequestUri;

    global $xoopsUser, $cookiename;

    loadSession();

    cleanRequest();

    //Turn off compressed output

    $modSettings['enableCompressedOutput'] = '0';

    //Turn off local cookies

    $modSettings['localCookies'] = '0';

    //Turn off SEF in SMF

    $modSettings['queryless_urls'] = '';

    if (isset($_GET['sesc'])) {
        $_SESSION['rand_code'] = $_GET['sesc'];
    }

    if (isset($_POST['sc'])) {
        $_SESSION['rand_code'] = $_POST['sc'];
    }

    $sc = $_SESSION['rand_code'];

    $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];

    $modSettings['disableCheckUA'] = true;

    if (!isset($_SESSION['rand_code'])) {
        $_SESSION['rand_code'] = md5(session_id() . mt_rand());
    }

    $context['disable_login_hashing'] = true;

    //$_SESSION['old_url'] = XOOPS_URL .'/index.php';

    $_SESSION['old_url'] = XOOPS_URL . '/' . $xoopsRequestUri;
}

function xoops_smf_exit($with_output)
{
    global $xheader, $xoopsTpl;

    global $xoopsUser, $xoopsUserIsAdmin, $xoopsConfig, $xoopsLogger;

    global $xoopsOption, $xoopsTpl, $sc, $smf_header, $scripturl, $settings, $xoopsModuleConfig;

    global $context;

    $buffer = ob_get_contents();

    ob_end_clean();

    if (empty($context['page_title'])) {
        $context['page_title'] = '';
    }

    $smf_header = '<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/script.js?fin11"></script>
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "' . $settings['theme_url'] . '";
		var smf_images_url = "' . $settings['images_url'] . '";
		var smf_scripturl = "' . ob_xoopsfix($scripturl) . '";
		var smf_session_id = "' . $context['session_id'] . '";
	// ]]></script>
	<title>' . $context['page_title'] . '</title>';

    if (!$with_output || 0 == $xoopsModuleConfig['wrapped']) {
        echo ob_xoopsfix($buffer);

        exit();
    }

    $xoopsTpl->assign('xoops_module_header', $smf_header);

    echo ob_xoopsfix($buffer);

    //$xoopsTpl->assign('smf_content', ob_xoopsfix($buffer));

    if (1 == $xheader) {
        require XOOPS_ROOT_PATH . '/footer.php';
    }

    die();
}

function integrate_login($username, $password, $cookietime)
{
    global $xoopsConfig, $db_name, $user_settings;

    $xprefix = !defined('XOOPS_SITE_PREFIX') ? XOOPS_DB_PREFIX : XOOPS_SITE_PREFIX;

    //Get the user from Xoops

    mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME);

    $pwd = (!empty($_REQUEST['passwrd']) && '' != $_REQUEST['passwrd']) ? md5($_REQUEST['passwrd']) : 'migrated';

    $sess_id = $_COOKIE[session_name()];

    $request = $GLOBALS['xoopsDB']->queryF('SELECT profileid FROM ' . $xprefix . '_user_profile LIMIT 1');

    if (false === $request || 0 === $GLOBALS['xoopsDB']->getRowsNum($request)) { // XOOPS 2.0
        $request = $GLOBALS['xoopsDB']->queryF(
            '
			  SELECT uid,uname, pass, email, user_regdate,url
			  FROM ' . $xprefix . "_users 
			  WHERE uname = '$username'
			  LIMIT 1"
        );
    } else { // XOOPS 2.2 bzw. erw. Profile
        $request = $GLOBALS['xoopsDB']->queryF(
            '
			  SELECT u.uid,u.uname, u.pass, u.email, p.user_regdate,p.url
			  FROM ' . $xprefix . '_users as u , ' . $xprefix . "_user_profile as p
			  WHERE u.loginname = '$username' AND p.profileid = u.uid
			  LIMIT 1"
        );
    }

    $user = $GLOBALS['xoopsDB']->fetchArray($request);

    //What?  No user in Xoops?

    if (false === $user || 0 === $GLOBALS['xoopsDB']->getRowsNum($request)) {
        echo '<a href="' . XOOPS_URL . '">Return to Main</a><br>';

        die('No User Login');
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    $request = $GLOBALS['xoopsDB']->queryF(
        '
		SELECT *
		FROM ' . $xprefix . "_groups_users_link
		WHERE uid = '$user[uid]'"
    );

    $group = $GLOBALS['xoopsDB']->fetchBoth($request);

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    $memberHandler = xoops_getHandler('member');

    $myts = MyTextSanitizer::getInstance();

    require_once XOOPS_ROOT_PATH . '/class/auth/authfactory.php';

    require_once XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/user.php';

    $xoopsAuth = XoopsAuthFactory::getAuthConnection($myts->addSlashes($username));

    $user = $memberHandler->loginUserMd5($myts->addSlashes($username), $myts->addSlashes($user['pass']));

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
    }

    mysqli_select_db($GLOBALS['xoopsDB']->conn, $db_name);
}

function integrate_validate_login($username, $password, $cookietime)
{
    global $xoopsConfig, $db_name, $db_prefix, $xoopsUser, $xoopsRequestUri;

    $xprefix = !defined('XOOPS_SITE_PREFIX') ? XOOPS_DB_PREFIX : XOOPS_SITE_PREFIX;

    if ('' == $db_prefix) {
        $db_prefix = $xprefix . '_smf_';
    }

    // Check if the user already exists in SMF.

    mysqli_select_db($GLOBALS['xoopsDB']->conn, $db_name);

    $request = $GLOBALS['xoopsDB']->queryF(
        '
		SELECT ID_MEMBER,uid,passwd,passwordSalt
		FROM ' . $xprefix . "_users
		WHERE memberName = '$username'
		LIMIT 1"
    );

    $smf_user = $GLOBALS['xoopsDB']->fetchArray($request);

    if (false === $smf_user || 0 === $GLOBALS['xoopsDB']->getRowsNum($request)) {
        $GLOBALS['xoopsDB']->freeRecordSet($request);

        return false;
    } //OK, so no user in SMF.  Does this user exist in Xoops?

    if ($smf_user['ID_MEMBER'] == $smf_user['uid']) {
        return false;
    }

    mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME);

    // SMF-Password erstellen

    $sha_passwd = sha1(mb_strtolower($username) . un_htmlspecialchars(stripslashes($password)));

    $memberID = $smf_user['uid'];

    if ($smf_user['passwd'] != $sha_passwd) {
        $other_passwords = [];

        if ('' == $smf_user['passwordSalt']) {
            $smf_user['passwordSalt'] = mb_substr(md5(mt_rand()), 0, 4);
        }

        $user_passwd = $sha_passwd;

        //updateMemberData($memberID, array('ID_MEMBER' => $memberID,'passwd' => '\'' . $user_passwd . '\'', 'passwordSalt' => '\'' . $smf_user['passwordSalt'] . '\''));

        $request = $GLOBALS['xoopsDB']->queryF(
                '
		         UPDATE ' . $xprefix . "_users 
						 SET ID_MEMBER = $memberID,
						 passwd ='" . $user_passwd . "',
						 passwordSalt='" . $smf_user['passwordSalt'] . "'
		         WHERE memberName = '$username'"
            );
    }

    updateStats('member', $memberID, $username);

    $GLOBALS['xoopsDB']->queryF(
            "
			UPDATE {$db_prefix}log_activity 
			SET registers = registers + 1 
			WHERE date ='" . date('Y-m-d') . "' 
			LIMIT 1"
        );

    return false;
}

function integrate_logout($username)
{
    global $xoopsConfig, $db_name;

    $xprefix = !defined('XOOPS_SITE_PREFIX') ? XOOPS_DB_PREFIX : XOOPS_SITE_PREFIX;

    mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME);

    $logout = $GLOBALS['xoopsDB']->queryF(
        '
		UPDATE ' . $xprefix . "_session
		SET sess_data = ''
		WHERE sess_id = '" . $_COOKIE[session_name()] . "'"
    );

    if ($xoopsConfig['use_mysession'] && '' != $xoopsConfig['session_name']) {
        setcookie($xoopsConfig['session_name'], '', time() - 3600, '/', '', 0);
    }

    $_SESSION = [];

    session_destroy();

    mysqli_select_db($GLOBALS['xoopsDB']->conn, $db_name);

    $_SESSION['logout_url'] = XOOPS_URL . '/index.php';
}

function integrate_register($Options, $theme_vars)
{
    global $xoopsConfig, $db_name;

    $xprefix = !defined('XOOPS_SITE_PREFIX') ? XOOPS_DB_PREFIX : XOOPS_SITE_PREFIX;

    mysqli_select_db($GLOBALS['xoopsDB']->conn, XOOPS_DB_NAME);

    $request = $GLOBALS['xoopsDB']->queryF('SELECT uid,is_activated FROM ' . $xprefix . '_users WHERE memberName=' . $Options['register_vars']['memberName'] . '');

    if ($request && $GLOBALS['xoopsDB']->getRowsNum($request) > 0) {
        $xoops_id = $GLOBALS['xoopsDB']->fetchArray($request);

        $xoops_id = (int)$xoops_id['uid'];
    }

    $request = $GLOBALS['xoopsDB']->queryF('SELECT profileid FROM ' . $xprefix . '_user_profile LIMIT 1');

    if (false === $request || 0 === $GLOBALS['xoopsDB']->getRowsNum($request)) { // XOOPS 2.0
        $sql = '
		UPDATE ' . $xprefix . '_users
		SET name =' . $Options['register_vars']['realName'] . ', 
		ID_MEMBER = ' . $xoops_id . ',
		uname = ' . $Options['register_vars']['realName'] . ',
		email = ' . $Options['register_vars']['emailAddress'] . ",
		pass = '" . md5($_POST['passwrd1']) . "',
		user_regdate = '" . $Options['register_vars']['dateRegistered'] . "'
		WHERE uid = " . $xoops_id;
    } else {
        $sql = '
		UPDATE ' . $xprefix . '_users
		SET name =' . $Options['register_vars']['realName'] . ', 
		ID_MEMBER = ' . $xoops_id . ',
		loginname =' . $Options['register_vars']['memberName'] . ',
		uname = ' . $Options['register_vars']['realName'] . ',
		email = ' . $Options['register_vars']['emailAddress'] . ",
		pass = '" . md5($_POST['passwrd1']) . "'
		WHERE uid = " . $xoops_id;

        $GLOBALS['xoopsDB']->queryF(
            '
		INSERT INTO ' . $xprefix . '_user_profile
			(profileid, user_regdate)
		VALUES (' . $xoops_id . ',' . $Options['register_vars']['dateRegistered'] . ')'
        );
    }

    $GLOBALS['xoopsDB']->queryF($sql);

    $GLOBALS['xoopsDB']->queryF(
        '
		INSERT INTO ' . $xprefix . "_groups_users_link
			(groupid, uid)
		VALUES ('2', '$xoops_id');"
    );

    mysqli_select_db($GLOBALS['xoopsDB']->conn, $db_name);
}

$GLOBALS['xoopsTpl']->assign('xoops_showrblock', (int)$xoopsModuleConfig['showrblock']);
$GLOBALS['xoopsTpl']->assign('xoops_showlblock', (int)$xoopsModuleConfig['showlblock']);
$GLOBALS['xoopsTpl']->assign('xoops_showcblock', 1);

$sc = empty($_SESSION['rand_code']) ? '' : $_SESSION['rand_code'];
