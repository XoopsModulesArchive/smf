<?php

global $xoopsDB;
$sql = 'ALTER TABLE ' . $xoopsDB->prefix('users') . ' 
  DROP `ID_MEMBER`,
  DROP `memberName`,
  DROP `dateRegistered`,
  DROP `ID_GROUP`,
  DROP `lngfile`,
  DROP `lastLogin`,
  DROP `realName`,
  DROP `instantMessages`,
  DROP `unreadMessages`,
  DROP `buddy_list`,
  DROP `pm_ignore_list`,
  DROP `messageLabels`,
  DROP `passwd`,
  DROP `emailAddress`,
  DROP `personalText`,
  DROP `gender`,
  DROP `birthdate`,
  DROP `websiteTitle`,
  DROP `websiteUrl`,
  DROP `location`,
  DROP `ICQ`,
  DROP `AIM`,
  DROP `YIM`,
  DROP `MSN`,
  DROP `hideEmail`,
  DROP `showOnline`,
  DROP `timeFormat`,
  DROP `signature`,
  DROP `timeOffset`,
  DROP `avatar`,
  DROP `pm_email_notify`,
  DROP `karmaBad`,
  DROP `karmaGood`,
  DROP `usertitle`,
  DROP `notifyAnnouncements`,
  DROP `notifyOnce`,
  DROP `notifySendBody`,
  DROP `notifyTypes`,
  DROP `memberIP`,
  DROP `memberIP2`,
  DROP `secretQuestion`,
  DROP `secretAnswer`,
  DROP `ID_THEME`,
  DROP `is_activated`,
  DROP `validation_code`,
  DROP `ID_MSG_LAST_VISIT`,
  DROP `additionalGroups`,
  DROP `smileySet`,
  DROP `ID_POST_GROUP`,
  DROP `totalTimeLoggedIn`,
  DROP `passwordSalt`';
$erg = $xoopsDB->queryF($sql);
/*
$sql ="ALTER TABLE ".$xoopsDB->prefix('groups')."
  DROP `ID_GROUP`,
  DROP `groupName`,
  DROP `onlineColor`,
  DROP `minPosts`,
  DROP `maxMessages`,
  DROP `stars`";
$erg=$xoopsDB->queryF($sql);
*/

//PM-Message
$sql = 'UPDATE '
       . $xoopsDB->prefix('user_profile')
       . " SET pm_link='<a href=\"javascript:openWithSelfMain(\'{X_URL}/modules/pm/pmlite.php?send2=1&to_userid={X_UID}\', \'pmlite\', 550, 450);\" title=\"Eine Nachricht schreiben an {X_UNAME}\"><img src=\"{X_URL}/modules/pm/images/pm.gif\" alt=\"Eine Nachricht schreiben an {X_UNAME}\"></a>'";
$erg = $xoopsDB->queryF($sql);
$sql = 'UPDATE '
       . $xoopsDB->prefix('user_profile_field')
       . " SET field_default='<a href=\"javascript:openWithSelfMain(\'{X_URL}/modules/pm/pmlite.php?send2=1&to_userid={X_UID}\', \'pmlite\', 550, 450);\" title=\"Eine Nachricht schreiben an {X_UNAME}\"><img src=\"{X_URL}/modules/pm/images/pm.gif\" alt=\"Eine Nachricht schreiben an {X_UNAME}\"></a>' WHERE field_name='pm_link'";
$erg = $xoopsDB->queryF($sql);

// Usergroups
/*
$sql="UPDATE ".$xoopsDB->prefix('groups')." SET groupid= 3 WHERE groupid = -1";
$erg=$xoopsDB->queryF($sql);
$sql="UPDATE ".$xoopsDB->prefix('groups')." SET groupid= 2 WHERE groupid = 0";
$erg=$xoopsDB->queryF($sql);
$sql="UPDATE ".$xoopsDB->prefix('groups_users_link')." SET groupid= 3 WHERE groupid = -1";
$erg=$xoopsDB->queryF($sql);
$sql="UPDATE ".$xoopsDB->prefix('groups_users_link')." SET groupid= 2 WHERE groupid = 0";
$erg=$xoopsDB->queryF($sql);
*/
