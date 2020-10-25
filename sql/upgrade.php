<?php

if (!defined('XOOPS_ROOT_PATH')) {
    require_once '../../../mainfile.php';
}
global $xoopsDB, $xoopsConfig, $xoopsUser;
//PM-Message
$sql = 'UPDATE ' . $xoopsDB->prefix('user_profile') . " SET pm_link=\"<a href='{X_URL}/modules/smf/index.php?action=pm;sa=send;u={X_UID}'><img src='{X_URL}/modules/pm/images/pm.gif' alt='Eine Nachricht schreiben an {X_UNAME}'></a>\"";
$erg = $xoopsDB->queryF($sql);
$sql = 'UPDATE ' . $xoopsDB->prefix('user_profile_field') . " SET field_default=\"<a href='{X_URL}/modules/smf/index.php?action=pm;sa=send;u={X_UID}'><img src='{X_URL}/modules/pm/images/pm.gif' alt='Eine Nachricht schreiben an {X_UNAME}'></a>\" WHERE field_name='pm_link'";
$erg = $xoopsDB->queryF($sql);
