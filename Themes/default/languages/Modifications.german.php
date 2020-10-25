<?php
// Version: 1.1; Modifications

//Begin User Email System Text Strings
$txt['user_email_title'] = 'User Email System';
$txt['permissionname_send_useremail'] = 'Sende dem User eine Email';
$txt['permissionhelp_send_useremail'] = 'Erlaubt der User das Empfangen der EMAIL.';
$txt['cannot_send_useremail'] = 'Keine Berechtigung zum Senden von EMail.';
$txt['user_email_youremail'] = 'Ihre Emailadresse';
$txt['user_email_subject'] = 'Betreff';
$txt['user_email_messagebody'] = 'Nachricht';
$txt['user_email_recipient'] = 'Empf&auml;nger';
$txt['user_email_sendemailheader'] = 'eine Email versenden';
$txt['user_email_options'] = 'Option';
$txt['user_email_sendcopy'] = 'Eine Kopie an Absender senden';
$txt['user_email_sendemail'] = 'Email verschicken';
$txt['user_email_emailsend'] = 'Email wurde verschickt';
$txt['user_email_emailsendtoreturn'] = 'Klicke <a href="%s">hier</a> um zur&uuml;ckzukehren';
$txt['user_email_subjectfailed'] = 'Es wurde kein Betreff angegeben.';
$txt['user_email_messagefailed'] = 'Es wurde kein Text eingegeben.';
$txt['user_email_nouser'] = 'Es wurde kein User ausgew&auml;hlt.';
$txt['user_email_cannouser'] = 'Dieser User kann nicht per Email erreicht werden.';
$txt['user_email_selfmailrequire'] = 'Eine Emailadresse ist erforderlich.';
$txt['user_email_selfmailfail'] = 'Die angegebene EMail ist ung&uuml;ltig';
$txt['user_email_selfmailbodytext'] = "Hallo %s,

Die folgende Nachricht ist von %s gesendet vom Konto von %s.\n
Sollte dies Nachricht Spam enthalten, kontaktieren Sie bitte den Webmaster unter %s

Die Nachricht im Detail:\n\n";
$txt['user_email_mailissend'] = ' - Email versendet.';
//END Begin User Email System Text Strings

// OB - Googlebot - Begin

// Boardindex Strings
$txt['ob_googlebot_modname'] = 'Googlebot & Spider';
$txt['ob_googlebot_spider'] = 'Spider';
$txt['ob_googlebot_spiders'] = 'Spider';
$txt['ob_googlebot_spiders_last_active'] = 'Spider aktiv in letzten ' . $modSettings['lastActive'] . ' Minuten';

// ModSettings
$txt['ob_googlebot_count_all_instances'] = 'Z&auml;hle alle Spider';
$txt['ob_googlebot_display_all_instances'] = 'Anzeige der Spider <div class="smalltext">("' . $txt['ob_googlebot_count_all_instances'] . '" muss aktiviert sein)</div>';
$txt['ob_googlebot_display_agent'] = 'Anzeige UserAgent als Name';
$txt['ob_googlebot_display_own_list'] = 'Anzeige der Spider in einer Liste';
$txt['ob_googlebot_count_most_online'] = 'Anzeige in "Am meisten Online"';
$txt['ob_googlebot_redirect_phpsessid'] = 'Redirect PHPSESSID URLs';

// Stats
$txt['ob_googlebot_stats_lastvisit'] = 'Google letzter Besuch dieser Seite ';

// Permissions
$txt['permissiongroup_googlebot'] = $txt['ob_googlebot_modname'];
$txt['permissionname_googlebot_view'] = 'Googlebot & Spiders ansehen';

// OB - Googlebot - End
