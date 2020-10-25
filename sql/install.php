<?php

if (!defined('XOOPS_ROOT_PATH')) {
    require_once '../../../mainfile.php';
}
global $xoopsDB, $xoopsConfig, $xoopsUser;
require_once XOOPS_ROOT_PATH . '/modules/smf/Themes/default/languages/Install.' . $xoopsConfig['language'] . '.php';
if (empty($txt['lang_character_set'])) {
    require_once XOOPS_ROOT_PATH . '/modules/smf/Themes/default/languages/Install.english.php';
}  // Sprache wählen

$replaces = [
    '{$db_prefix}' => (defined('XOOPS_SITE_PREFIX')) ? XOOPS_SITE_PREFIX . '_smf_' : XOOPS_DB_PREFIX . '_smf_',
    '{$boarddir}' => addslashes(dirname(__DIR__)),
    '{$boardurl}' => XOOPS_URL . '/modules/smf',
    '{$enableCompressedOutput}' => '0',
    '{$databaseSession_enable}' => '0',
    '{$smf_version}' => '1.1.2',
];
foreach ($txt as $key => $value) {
    if ('default_' == mb_substr($key, 0, 8)) {
        $replaces['{$' . $key . '}'] = addslashes($value);
    }
}
$replaces['{$default_reserved_names}'] = strtr($replaces['{$default_reserved_names}'], ['\\\\n' => '\\n']);

$sql_lines = explode("\n", strtr(implode(' ', file(__DIR__ . '/mysql.sql')), $replaces));
$current_statement = '';
$failures = [];
$exists = [];

foreach ($sql_lines as $count => $line) {
    // No comments allowed!

    if ('#' != mb_substr(trim($line), 0, 1)) {
        $current_statement .= "\n" . rtrim($line);
    }

    // Is this the end of the query string?

    if (empty($current_statement) || (0 == preg_match('~;[\s]*$~s', $line) && $count != count($sql_lines))) {
        continue;
    }

    // Does this table already exist?  If so, don't insert more data into it!

    if (0 != preg_match('~^\s*INSERT INTO ([^\s\n\r]+?)~', $current_statement, $match) && in_array($match[1], $exists, true)) {
        $current_statement = '';

        continue;
    }

    if (false === $xoopsDB->queryF($current_statement)) {
        // Error 1050: Table already exists!

        if (1050 == $xoopsDB->errno() && 1 == preg_match('~^\s*CREATE TABLE ([^\s\n\r]+?)~', $current_statement, $match)) {
            $exists[] = $match[1];
        } else {
            $failures[$count] = $GLOBALS['xoopsDB']->error();
        }
    }

    $current_statement = '';
}

// Tabellenstruktur erweitern
$tabelle = [
    0 => '`ID_MEMBER` mediumint(8) unsigned NOT NULL',
    1 => "`memberName` varchar(80) NOT NULL default ''",
    2 => "`dateRegistered` int(10) unsigned NOT NULL default '0'",
    3 => "`posts` mediumint(8) unsigned NOT NULL default '0'",
    4 => "`ID_GROUP` smallint(5) unsigned NOT NULL default '0'",
    5 => '`lngfile` tinytext NOT NULL',
    6 => "`lastLogin` int(10) unsigned NOT NULL default '0'",
    7 => '`realName` tinytext NOT NULL',
    8 => "`instantMessages` smallint(5) NOT NULL default '0'",
    9 => "`unreadMessages` smallint(5) NOT NULL default '0'",
    10 => '`buddy_list` text NOT NULL',
    11 => '`pm_ignore_list` tinytext NOT NULL',
    12 => '`messageLabels` text NOT NULL',
    13 => "`passwd` varchar(64) NOT NULL default ''",
    14 => '`emailAddress` tinytext NOT NULL',
    15 => '`personalText` tinytext NOT NULL',
    16 => "`gender` tinyint(4) unsigned NOT NULL default '0'",
    17 => "`birthdate` date NOT NULL default '0001-01-01'",
    18 => '`websiteTitle` tinytext NOT NULL',
    19 => '`websiteUrl` tinytext NOT NULL',
    20 => '`location` tinytext NOT NULL',
    21 => '`ICQ` tinytext NOT NULL',
    22 => "`AIM` varchar(16) NOT NULL default ''",
    23 => "`YIM` varchar(32) NOT NULL default ''",
    24 => '`MSN` tinytext NOT NULL',
    25 => "`hideEmail` tinyint(4) NOT NULL default '0'",
    26 => "`showOnline` tinyint(4) NOT NULL default '1'",
    27 => "`timeFormat` varchar(80) NOT NULL default ''",
    28 => '`signature` text NOT NULL',
    29 => "`timeOffset` float NOT NULL default '0'",
    30 => '`avatar` tinytext NOT NULL',
    31 => "`pm_email_notify` tinyint(4) NOT NULL default '0'",
    32 => "`karmaBad` smallint(5) unsigned NOT NULL default '0'",
    33 => "`karmaGood` smallint(5) unsigned NOT NULL default '0'",
    34 => '`usertitle` tinytext NOT NULL',
    35 => "`notifyAnnouncements` tinyint(4) NOT NULL default '1'",
    36 => "`notifyOnce` tinyint(4) NOT NULL default '1'",
    37 => "`notifySendBody` tinyint(4) NOT NULL default '0'",
    38 => "`notifyTypes` tinyint(4) NOT NULL default '2'",
    39 => '`memberIP` tinytext NOT NULL',
    40 => '`memberIP2` tinytext NOT NULL',
    41 => '`secretQuestion` tinytext NOT NULL',
    42 => "`secretAnswer` varchar(64) NOT NULL default ''",
    43 => "`ID_THEME` tinyint(4) unsigned NOT NULL default '0'",
    44 => "`is_activated` tinyint(3) unsigned NOT NULL default '1'",
    45 => "`validation_code` varchar(10) NOT NULL default ''",
    46 => "`ID_MSG_LAST_VISIT` int(10) unsigned NOT NULL default '0'",
    47 => '`additionalGroups` tinytext NOT NULL',
    48 => "`smileySet` varchar(48) NOT NULL default ''",
    49 => "`ID_POST_GROUP` smallint(5) unsigned NOT NULL default '0'",
    50 => "`totalTimeLoggedIn` int(10) unsigned NOT NULL default '0'",
    51 => "`passwordSalt` varchar(5) NOT NULL default ''",
];
foreach ($tabelle as $tab) {
    $sql = 'ALTER TABLE ' . $xoopsDB->prefix('users') . " ADD $tab;";

    $erg = $xoopsDB->queryF($sql);
}

// Admin eintragen
$salt = mb_substr(md5(mt_rand()), 0, 4);
$ip = isset($_SERVER['REMOTE_ADDR']) ? addslashes(mb_substr(stripslashes($_SERVER['REMOTE_ADDR']), 0, 255)) : '';
$request = 'UPDATE ' . $xoopsDB->prefix('users') . " SET memberName='";
$xoops22 = $xoopsDB->query('SELECT profileid FROM ' . $xoopsDB->prefix('user_profile') . ' LIMIT 1');
if (!$xoops22 || 0 == $xoopsDB->getRowsNum($xoops22)) { // XOOPS 2.0
    $request .= $xoopsUser->getVar('uname') . "'";

    $xv2 = 0;
} else {
    $request .= $xoopsUser->getVar('loginname') . "'";

    $xv2 = 1;
}
$request .= ", realName='" . $xoopsUser->getVar('uname') . "'
							, passwd='" . $xoopsUser->getVar('pass') . "', emailAddress='" . $xoopsUser->getVar('email') . "',ID_GROUP=1, posts=" . (int)$xoopsUser->getVar('post') . "
							, dateRegistered='" . $xoopsUser->getVar('user_regdate') . "',
							 hideEmail=1, passwordSalt='$salt', memberIP='$ip', memberIP2='$ip',
							 ID_MEMBER=" . $xoopsUser->getVar('uid') . ',
							 posts= ' . $xoopsUser->getVar('posts') . '
			        WHERE uid=' . $xoopsUser->getVar('uid');

$erg = $xoopsDB->queryF($request);
if ($erg) {
    $id = $xoopsDB->getInsertId();
}
if (0 == $xv2) {
    $sql = 'SELECT uid,uname as uname, uname as loginname,email,posts,user_regdate FROM ' . $xoopsDB->prefix('users') . ' WHERE uid <>' . $xoopsUser->getVar('uid');
} else {
    $sql = 'SELECT u.uid,u.uname,u.loginname,u.email,p.posts,p.user_regdate FROM ' . $xoopsDB->prefix('users') . ' as u, ' . $xoopsDB->prefix('user_profile') . ' as p WHERE u.uid <>' . $xoopsUser->getVar('uid') . ' and p.profileid=u.uid';
}
$erg = $xoopsDB->queryF($sql);
$cm = 1;
$trid = '';
$trn = '';
$logt = 0;
if ($erg) {
    while (false !== ($row = $xoopsDB->fetchArray($erg))) {
        $sql = 'UPDATE ' . $xoopsDB->prefix('users') . " SET memberName='";

        $sql .= $row['loginname'] . "', emailAddress='" . $row['email'] . "',";

        $sql .= "realName='" . $row['uname'] . "',posts=" . $row['posts'] . ",dateRegistered='" . $row['user_regdate'] . "'";

        $sql .= ',ID_MEMBER=' . $row['uid'];

        $sql .= ' WHERE uid=' . $row['uid'];

        $erg1 = $xoopsDB->queryF($sql);

        $cm++;

        if ($row['user_regdate'] > $logt) {
            $logt = $row['user_regdate'];

            $trid = $row['uid'];

            $trn = $row['uname'];
        }
    }
}
// totalMembers
$sql = 'INSERT INTO ' . $xoopsDB->prefix('smf_settings') . " SET variable='totalMembers', value='" . $cm . "'";
$erg = $xoopsDB->queryF($sql);
//latestMember
$sql = 'INSERT INTO ' . $xoopsDB->prefix('smf_settings') . " SET variable='latestMember', value='" . $trid . "'";
$erg = $xoopsDB->queryF($sql);
//latestRealName
$sql = 'INSERT INTO ' . $xoopsDB->prefix('smf_settings') . " SET variable='latestRealName', value='" . $trn . "'";
$erg = $xoopsDB->queryF($sql);
//PM-Message
$sql = 'UPDATE ' . $xoopsDB->prefix('user_profile') . " SET pm_link=\"<a href='{X_URL}/modules/smf/index.php?action=pm;sa=send;u={X_UID}'><img src='{X_URL}/modules/pm/images/pm.gif' alt='Eine Nachricht schreiben an {X_UNAME}'></a>\"";
$erg = $xoopsDB->queryF($sql);
$sql = 'UPDATE ' . $xoopsDB->prefix('user_profile_field') . " SET field_default=\"<a href='{X_URL}/modules/smf/index.php?action=pm;sa=send;u={X_UID}'><img src='{X_URL}/modules/pm/images/pm.gif' alt='Eine Nachricht schreiben an {X_UNAME}'></a>\" WHERE field_name='pm_link'";
$erg = $xoopsDB->queryF($sql);
// Usergroups
/*
$sql="DELETE ".$xoopsDB->prefix('groups')." WHERE groupid > 3";
$erg=$xoopsDB->queryF($sql);
$sql="DELETE ".$xoopsDB->prefix('groups_users_link')." WHERE groupid > 3";
$erg=$xoopsDB->queryF($sql);
$sql="UPDATE ".$xoopsDB->prefix('groups')." SET groupid= -1 WHERE groupid = 3";
$erg=$xoopsDB->queryF($sql);
$sql="UPDATE ".$xoopsDB->prefix('groups')." SET groupid= 0 WHERE groupid = 2";
$erg=$xoopsDB->queryF($sql);
$sql="UPDATE ".$xoopsDB->prefix('groups_users_link')." SET groupid= -1 WHERE groupid = 3";
$erg=$xoopsDB->queryF($sql);
$sql="UPDATE ".$xoopsDB->prefix('groups_users_link')." SET groupid= 0 WHERE groupid = 2";
$erg=$xoopsDB->queryF($sql);
*/
