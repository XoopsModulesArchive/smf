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
require dirname(__DIR__, 3) . '/include/cp_header.php';

if (!empty($_POST['pwdrecover']) && 'on' == $_POST['pwdrecover']) {
    if (md5($_POST['pass']) == $xoopsUser->getVar('pass')) {
        $request = $xoopsDB->query('SELECT profileid FROM ' . $xoopsDB->prefix('user_profile') . ' LIMIT 1');

        if (!$request || 0 == $xoopsDB->getRowsNum($request)) { // XOOPS 2.0
            $uname = $xoopsUser->getVar('uname');
        } else {
            $uname = $xoopsUser->getVar('loginname');
        }

        if (!defined('SMF_BOARD_INTEGRATED')) {
            define('SMF_BOARD_INTEGRATED', 1);
        }

        require XOOPS_ROOT_PATH . '/modules/smf/include/checklogin.php';
    }
}

$mode = 1;
// check Admin PW
$request = $xoopsDB->query('SELECT profileid FROM ' . $xoopsDB->prefix('user_profile') . ' LIMIT 1');
if (!$request || 0 == $xoopsDB->getRowsNum($request)) { // XOOPS 2.0
    $erg = $xoopsDB->query('SELECT passwd FROM ' . $xoopsDB->prefix('users') . " WHERE memberName='" . $xoopsUser->getVar('uname') . "'");
} else {
    $erg = $xoopsDB->query('SELECT passwd FROM ' . $xoopsDB->prefix('users') . " WHERE memberName='" . $xoopsUser->getVar('loginname') . "'");
}
if ($erg && $xoopsDB->getRowsNum($erg) > 0) {
    [$pass] = $xoopsDB->fetchRow($erg);

    if ($pass != $xoopsUser->getVar('pass')) {
        $mode = 0;
    }
}
xoops_cp_header();
echo "<table width='100%' border='0' cellspacing='1' class='outer'>" . '<tr><td class="odd">';
echo "<a href='./index.php'><h4>" . _MD_A_SMFCONF . '</h4></a>';
if ($mode > 0) {
    $msg = '<form action="index.php" method="post">';

    $msg .= '<input type="hidden" name="pwdrecover" value="on">';

    $msg .= 'Administrator - Passwort : ';

    $msg .= '<input type="password" name="pass" value="">';

    $msg .= '<input type="submit" name="post" value="' . _SUBMIT . '">';

    $msg .= '</form>';

    xoops_error($msg, 'PASSWORT RECONFIG');
} else {
    ?>
    <table border="0" cellpadding="4" cellspacing="1" width="100%">

        <tr class='bg1' align="left">
            <td><span class='fg2'><a href="../index.php?action=admin"><?php echo _MI_ADMIN; ?></a></span></td>
            <td><span class='fg2'><?php echo _MI_ADMIN_DESC; ?></span></td>
        </tr>


    </table>
    <?php
}

echo '</td></tr></table>';
xoops_cp_footer();
?>
