<?php
/**********************************************************************************
 * Settings.php                                                                    *
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

global $xoopsConfig;
########## Maintenance ##########
# Note: If $maintenance is set to 2, the forum will be unusable!  Change it to 0 to fix it.
$maintenance = 0;        # Set to 1 to enable Maintenance Mode, 2 to make the forum untouchable. (you'll have to make it 0 again manually!)
$mtitle = 'Maintenance Mode';        # Title for the Maintenance Mode message.
$mmessage = 'Okay faithful users...we\'re attempting to restore an older backup of the database...news will be posted once we\'re back!';        # Description of why the forum is in maintenance mode.
########## Forum Info ##########
$mbname = $xoopsConfig['sitename'];        # The name of your forum.
$language = $xoopsConfig['language'];        # The default language file set for the forum.
$boardurl = XOOPS_URL . '/modules/smf'; //'http://127.0.0.1/smf';		# URL to your forum's folder. (without the trailing /!)
$webmaster_email = $xoopsConfig['adminmail'];        # Email address to send emails from. (like noreply@yourdomain.com.)
if (defined('XOOPS_SESSION_URL')) {
    ini_set('session.cookie_domain', XOOPS_SESSION_URL);
}
$cookiename = 'SMF' . abs(crc32(XOOPS_DB_NAME . preg_replace('~[^A-Za-z0-9_$]~', '', XOOPS_DB_PREFIX)) % 1000); //$_COOKIE['cookiename']; //'SMFCookie11';		# Name of the cookie to set for authentication.

########## Database Info ##########
$db_server = XOOPS_DB_HOST; //'localhost';
$db_name = XOOPS_DB_NAME; //'smf';
$db_user = XOOPS_DB_USER; //'root';
$db_passwd = XOOPS_DB_PASS; //'';
$db_prefix = (defined('XOOPS_SITE_PREFIX')) ? XOOPS_SITE_PREFIX . '_smf_' : XOOPS_DB_PREFIX . '_smf_'; //'smf_';
$db_persist = XOOPS_DB_PCONNECT;
$db_error_send = 1;

########## Directories/Files ##########
# Note: These directories do not have to be changed unless you move things.
$boarddir = __DIR__;        # The absolute path to the forum's folder. (not just '.'!)
$sourcedir = __DIR__ . '/Sources';        # Path to the Sources directory.

########## Error-Catching ##########
# Note: You shouldn't touch these settings.
$db_last_error = 1174827917;

if (file_exists(__DIR__ . '/install.php')) {
    header(
        'Location: http://' . (empty($_SERVER['HTTP_HOST']) ? $_SERVER['SERVER_NAME'] . (empty($_SERVER['SERVER_PORT']) || '80' == $_SERVER['SERVER_PORT'] ? '' : ':' . $_SERVER['SERVER_PORT']) : $_SERVER['HTTP_HOST']) . ('/' == strtr(dirname($_SERVER['PHP_SELF']), '\\', '/') ? '' : strtr(
            dirname($_SERVER['PHP_SELF']),
            '\\',
            '/'
        )) . '/install.php'
    );
}

# Make sure the paths are correct... at least try to fix them.
if (!file_exists($boarddir) && file_exists(__DIR__ . '/agreement.txt')) {
    $boarddir = __DIR__;
}
if (!file_exists($sourcedir) && file_exists($boarddir . '/Sources')) {
    $sourcedir = $boarddir . '/Sources';
}
