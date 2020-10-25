<?php
// SIMPLE-XOOPS START
// Integration SMF-Board
if (defined('SMF_FORUM_ACTIVE')) { // SMF-Board integraded and activ
    // Registrierformular
    if (!empty($_POST['loginname'])) {
        $uname = $_POST['loginname'];
    } // XOOPS 2.2

    elseif (!empty($_POST['uname'])) {
        $uname = $_POST['uname'];
    } // XOOPS 2.0

    // Load the settings...

    if (!defined('SMF')) {
        define('SMF', 1);
    }

    $time_start = microtime();

    require_once dirname(__DIR__) . '/Settings.php';

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

    $context = [];

    if (!empty($_POST['op']) && 'finish' == $_POST['op']) {
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '" . $xoopsDB->prefix('user_profile') . "'");

        $sql = 'SELECT uid,uname,passwd, ID_MEMBER, ID_GROUP, lngfile, is_activated, emailAddress, additionalGroups, memberName, passwordSalt,dateRegistered FROM ' . $xoopsDB->prefix('users') . ' WHERE ';

        if ($xoopsDB->getRowsNum($result) > 0) { // XOOPS2.2
            $sql .= 'loginname=';
        } else {
            $sql .= 'uname=';
        }

        $sql .= "'$uname' LIMIT 1";
    } else {
        $sql = 'SELECT uid,uname,passwd, ID_MEMBER, ID_GROUP, lngfile, is_activated, emailAddress, additionalGroups, memberName, passwordSalt,dateRegistered,email FROM ' . $xoopsDB->prefix('users') . " WHERE memberName='$uname' LIMIT 1";
    }

    $request = db_query($sql, __FILE__, __LINE__);

    if (0 == $GLOBALS['xoopsDB']->getRowsNum($request)) { // User in als SMF nicht bekannt !
        // checken ob XOOPS

        $result = $xoopsDB->queryF("SHOW TABLES LIKE '" . $xoopsDB->prefix('user_profile') . "'");

        if ($xoopsDB->getRowsNum($result) > 0) { // XOOPS2.2
            $sql = 'SELECT uid,uname,passwd, ID_MEMBER, ID_GROUP, lngfile, is_activated, emailAddress, additionalGroups, memberName, passwordSalt,dateRegistered,email FROM ' . $xoopsDB->prefix('users') . " WHERE loginname='$uname' LIMIT 1";
        } else {
            $sql = 'SELECT uid,uname,passwd, ID_MEMBER, ID_GROUP, lngfile, is_activated, emailAddress, additionalGroups, memberName, passwordSalt,dateRegistered,email FROM ' . $xoopsDB->prefix('users') . " WHERE uname='$uname' LIMIT 1";
        }

        $request = db_query($sql, __FILE__, __LINE__);

        if (0 == $GLOBALS['xoopsDB']->getRowsNum($request)) { // User
            die('unknown User!');
        }
    }

    $user_settings = $GLOBALS['xoopsDB']->fetchArray($request);

    if (empty($user_settings['ID_MEMBER'])) {
        $user_settings['ID_MEMBER'] = $user_settings['uid'];
    }

    $memberID = $user_settings['ID_MEMBER'];

    $user_settings['ip'] = $_SERVER['REMOTE_ADDR'];

    if (empty($user_settings['memberName'])) {
        $user_settings['memberName'] = $uname;
    }

    if (empty($user_settings['dateRegistered'])) {
        $user_settings['dateRegistered'] = time();

        $newreg = true;
    }

    $GLOBALS['xoopsDB']->freeRecordSet($request);

    // Hier User-Aktivierungen checken!

    // SMF-Password erstellen

    if (!empty($_POST['vpass'])) {
        $_POST['pass'] = $_POST['vpass'];
    }

    $sha_passwd = sha1(mb_strtolower($user_settings['memberName']) . un_htmlspecialchars(stripslashes($_POST['pass'])));

    if ($user_settings['passwd'] != $sha_passwd) {
        if ('' == $user_settings['passwordSalt']) {
            $user_settings['passwordSalt'] = mb_substr(md5(mt_rand()), 0, 4);
        }

        $user_settings['passwd'] = $sha_passwd;

        $request = db_query("UPDATE {$db_prefix}members SET ID_MEMBER=" . $user_settings['ID_MEMBER'] . ' WHERE uid=' . $user_settings['ID_MEMBER'], __FILE__, __LINE__);

        updateMemberData(
            $user_settings['ID_MEMBER'],
            [
                'realName' => '\'' . $user_settings['uname'] . '\'',
                'emailAddress' => '\'' . $user_settings['email'] . '\'',
                'memberName' => '\'' . $user_settings['memberName'] . '\'',
                'dateRegistered' => $user_settings['dateRegistered'],
                'passwd' => '\'' . $user_settings['passwd'] . '\'',
                'passwordSalt' => '\'' . $user_settings['passwordSalt'] . '\'',
            ]
        );

        if ($newreg) {
            $sql = "UPDATE {$db_prefix}settings SET value='" . $user_settings['ID_MEMBER'] . "' WHERE variable='latestMember'";

            $request = db_query($sql, __FILE__, __LINE__);

            $sql = "UPDATE {$db_prefix}settings SET value='" . $user_settings['uname'] . "' WHERE variable='latestRealName'";

            $request = db_query($sql, __FILE__, __LINE__);

            $sql = "SELECT value FROM {$db_prefix}settings WHERE variable='totalMembers'";

            $request = db_query($sql, __FILE__, __LINE__);

            if ($GLOBALS['xoopsDB']->getRowsNum($request) > 0) {
                $user_total = $GLOBALS['xoopsDB']->fetchArray($request);

                $user_total = $user_total['value'];

                $user_total++;

                $sql = "UPDATE {$db_prefix}settings SET value='" . $user_total . "' WHERE variable='totalMembers'";

                $request = db_query($sql, __FILE__, __LINE__);
            }
        }

        $request = db_query(
            "
			UPDATE {$db_prefix}log_activity 
			SET registers = registers + 1 
			WHERE date ='" . date('Y-m-d') . "' 
			LIMIT 1",
            __FILE__,
            __LINE__
        );
    }

    // Get ready to set the cookie...

    $username = $user_settings['memberName'];

    $ID_MEMBER = $user_settings['ID_MEMBER'];

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
}
