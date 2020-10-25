<?php
// Version: 1.1; ManagePermissions

$txt['permissions_title'] = 'Berechtigungen editieren';
$txt['permissions_modify'] = '&#196;ndern';
$txt['permissions_access'] = 'Zugriff';
$txt['permissions_allowed'] = 'Erlaubt';
$txt['permissions_denied'] = 'Verboten';

$txt['permissions_switch'] = 'Wechseln zu';
$txt['permissions_global'] = 'Global';
$txt['permissions_local'] = 'Lokal';

$txt['permissions_groups'] = 'Berechtigungen der Mitgliedergruppen';
$txt['permissions_all'] = 'alle';
$txt['permissions_none'] = 'keine';
$txt['permissions_set_permissions'] = 'Berechtigungen speichern';

$txt['permissions_with_selection'] = 'Mit der Auswahl';
$txt['permissions_apply_pre_defined'] = 'Definiertes Berechtigungsprofil verwenden';
$txt['permissions_select_pre_defined'] = 'W&#228;hlen Sie ein definiertes Profil';
$txt['permissions_copy_from_board'] = 'Berechtigungen von diesem Board kopieren';
$txt['permissions_select_board'] = 'W&#228;hlen Sie ein Board';
$txt['permissions_like_group'] = 'Setze Berechtigungen wie diese Gruppe';
$txt['permissions_select_membergroup'] = 'W&#228;hlen Sie eine Mitgliedergruppe';
$txt['permissions_add'] = 'F&#252;ge Berechtigung hinzu';
$txt['permissions_remove'] = 'L&#246;schen Sie eine Berechtigung';
$txt['permissions_deny'] = 'Verbieten Sie Berechtigung';
$txt['permissions_select_permission'] = 'W&#228;hlen Sie eine Berechtigung';

// All of the following block of strings should not use entities, instead use \\" for &quot; etc.
$txt['permissions_only_one_option'] = 'Sie können nur eine Aktion zum editieren der Berechtigung wählen';
$txt['permissions_no_action'] = 'Keine Aktion ausgewählt';
$txt['permissions_deny_dangerous'] = 'Sie verbieten eine oder mehrere Berechtigungen.\\nDies kann unter Umständen unvorhergesehene Folgen nach sich ziehen, wenn Sie nicht kontrolliert haben, ob ein Mitglied \\"fälschlicherweise\\" in der betreffenden Gruppe ist.\\n\\nSind Sie sicher, dass Sie fortfahren möchten?';

$txt['permissions_boards'] = 'Berechtigungen der Boards';

$txt['permissions_modify_group'] = 'Gruppe &#228;ndern';
$txt['permissions_general'] = 'Generelle Berechtigungen';
$txt['permissions_board'] = 'Globale Board Berechtigungen';
$txt['permissions_commit'] = '&#196;nderungen speichern';
$txt['permissions_modify_local'] = 'Lokale Berechtigungen &#228;ndern';
$txt['permissions_on'] = 'im Board';
$txt['permissions_local_for'] = 'Lokale Berechtigungen f&#252;r die Gruppe';
$txt['permissions_option_on'] = 'E';
$txt['permissions_option_off'] = 'X';
$txt['permissions_option_deny'] = 'V';
$txt['permissions_option_desc'] = 'F&#252;r jede Berechtigung k&#246;nnen Sie \'Erlauben\' (E), \'Nicht erlauben\' (X) oder <span style="color: red;">\'Verbieten\' (V)</span> ausw&#228;hlen.<br><br>Beachten Sie: Wenn Sie eine Berechtigung verbieten, kann kein Mitglied - weder Moderator noch andere - in dieser Gruppe die betreffende Funktion nutzen.<br>Deshalb benutzen Sie diese Option mit gr&#246;&#223;ter Sorgfalt und nur wenn es <b>n&#246;tig</b> ist. \'Nicht erlauben\' verbietet nur so lange, bis es an anderer Stelle erlaubt wird.';

$txt['permissiongroup_general'] = 'Generell';
$txt['permissionname_view_stats'] = 'Forum-Statistiken anschauen';
$txt['permissionhelp_view_stats'] = 'Die Forum-Statistiken fassen alle Statistiken wie Mitgliederanzahl, t&#228;glich geschriebene Beitr&#228;ge und mehrere Top 10 Statistiken zusammen. Wenn Sie diese Berechtigung aktivieren, wird der Link (\'Weitere Statistiken\') im unteren Teil des Board-Index hinzugef&#252;gt.';
$txt['permissionname_view_mlist'] = 'Mitgliederliste anschauen';
$txt['permissionhelp_view_mlist'] = 'Die Mitgliederliste zeigt alle Mitglieder des Forums an. Sie kann sortiert und durchsucht werden und ist vom Board-Index und innerhalb der Statistiken verlinkt, wenn Sie dort auf die Mitgliederanzahl klicken.';
$txt['permissionname_who_view'] = '\'Wer ist online\' anschauen';
$txt['permissionhelp_who_view'] = '\'Wer ist online\' zeigt alle Mitglieder, die aktuell online sind und was sie gerade machen. Diese Berechtigung funktioniert nur, wenn Sie die Funktion in den \'Forum-Einstellungen\' aktiviert haben. Sie k&#246;nnen die Aktionen anschauen, wenn Sie auf den Link \'Wer ist online\' im Board-Index klicken.';
$txt['permissionname_search_posts'] = 'Nach Beitr&#228;gen und Themen suchen';
$txt['permissionhelp_search_posts'] = 'Die Berechtigung \'Suche\' erlaubt dem Benutzer alle Boards zu durchsuchen, auf die er zugreifen darf. Wenn diese Berechtigung aktiviert ist, befindet sich der \'Suchen\' Button in der Menuleiste.';
$txt['permissionname_karma_edit'] = 'Karma anderer Benutzer ver&#228;ndern';
$txt['permissionhelp_karma_edit'] = 'Karma ist eine Funktion, die den Beliebtheitsgrad eines Benutzers anzeigt. Um sie benutzen zu k&#246;nnen, m&#252;ssen Sie es in den \'Forum-Einstellungen\' aktiviert haben. Diese Berechtigung erlaubt es einer Mitgliedergruppe eine Wertung abzugeben und steht G&#228;sten nicht zur Verf&#252;gung.';

$txt['permissiongroup_pm'] = 'Private Mitteilungen';
$txt['permissionname_pm_read'] = 'Private Mitteilungen lesen';
$txt['permissionhelp_pm_read'] = 'Diese Berechtigung erlaubt dem Benutzer seine Privaten Mitteilungen zu lesen. Ohne diese Berechtigung kann er nicht auf diesen Bereich zugreifen.';
$txt['permissionname_pm_send'] = 'Private Mitteilungen senden';
$txt['permissionhelp_pm_send'] = 'Diese Berechtigung erlaubt dem Benutzer das Senden von Privaten Mitteilungen. Ben&#246;tigt \'Private Mitteilungen lesen\'.';

$txt['permissiongroup_calendar'] = 'Kalender';
$txt['permissionname_calendar_view'] = 'Kalender anschauen';
$txt['permissionhelp_calendar_view'] = 'Der Kalender zeigt f&#252;r jeden Monat die Geburtstage, Ereignisse und Ferien an. Diese Berechtigung erlaubt den Zugriff auf den Kalender, blendet den entsprechenden Button in der Menuleiste ein und zeigt im Board-Index eine Liste mit den aktuellen und kommenden Geburtstagen, Ereignissen und Ferien. Der Kalender muss dazu unter \'Kalender verwalten\' aktiviert werden.';
$txt['permissionname_calendar_post'] = 'Ereignisse im Kalender erstellen';
$txt['permissionhelp_calendar_post'] = 'Ereignisse sind Themen, die zu einem bestimmten Tag oder Zeitraum verlinkt werden. Ereignisse k&#246;nnen nur im Kalender erstellt werden, wenn der Benutzer die Berechtigung hat, neue Beitr&#228;ge zu erstellen.';
$txt['permissionname_calendar_edit'] = 'Ereignisse editieren';
$txt['permissionhelp_calendar_edit'] = 'Ereignisse sind Themen, die zu einem bestimmten Tag oder Zeitraum verlinkt werden. Sie k&#246;nnen editiert werden, indem man auf den kleinen Stern (*) neben dem Ereignis-Titel im Kalender klickt. Um sie editieren zu k&#246;nnen, ben&#246;tigt der Benutzer ausreichend Rechte zum &#228;dern des ersten Beitrags des betreffenden Themas.';
$txt['permissionname_calendar_edit_own'] = 'Eigene Ereignisse';
$txt['permissionname_calendar_edit_any'] = 'Jedes Ereignis';

$txt['permissiongroup_maintenance'] = 'Forum Administration';
$txt['permissionname_admin_forum'] = 'Forum und Datenbank administrieren';
$txt['permissionhelp_admin_forum'] = 'Diese Berechtigung erlaubt dem Benutzer das:<ul><li>&#196;ndern der Einstellungen des Forums, der Datenbank und der Themes</li><li>Verwalten von Paketen</li><li>Benutzen der Wartungsfunktionen</li><li>Anschauen der Fehler- bzw. Moderatoren-Protokollen</li></ul> Benutzen Sie diese Berechtigung mit Vorsicht, da sie sehr m&#228;chtig ist.';
$txt['permissionname_manage_boards'] = 'Boards und Kategorien verwalten';
$txt['permissionhelp_manage_boards'] = 'Diese Berechtigung erlaubt das Erstellen, &#196;ndern und L&#246;schen von Boards oder Kategorien.';
$txt['permissionname_manage_attachments'] = 'Anh&#228;nge und Benutzerbilder verwalten';
$txt['permissionhelp_manage_attachments'] = 'Diese Berechtigung erlaubt den Zugriff auf die Verwaltung der Dateianh&#228;nge, wo alle Anh&#228;nge und Benutzerbilder aufgelistet und entfernt werden k&#246;nnen.';
$txt['permissionname_manage_smileys'] = 'Smileys verwalten';
$txt['permissionhelp_manage_smileys'] = 'Diese Berechtigung erlaubt den Zugriff auf die Smiley Verwaltung, wo Sie Smileys bzw. Smiley-Sets hinzuf&#252;gen, &#228;ndern und l&#246;schen k&#246;nnen.';
$txt['permissionname_edit_news'] = 'News &#228;ndern';
$txt['permissionhelp_edit_news'] = 'Diese Berechtigung erlaubt das &#196;ndern der News-Meldungen. Um sie zu benutzen, muss die News Funktion in den Einstellungen aktiviert sein.';

$txt['permissiongroup_member_admin'] = 'Mitglieder Administration';
$txt['permissionname_moderate_forum'] = 'Mitglieder verwalten';
$txt['permissionhelp_moderate_forum'] = 'Diese Berechtigung beinhaltet alle wichtigen Funktionen der Mitgliederverwaltung:<ul><li>Zugriff auf die Registrierungsverwaltung</li><li>Zugriff auf den Mitglieder anzeigen/l&#246;schen Bildschirm</li><li>erweiterte Profil Informationen, inkl. IP/Benutzer beobachten und versteckten Online Status</li><li>Aktivierung von Zug&#228;ngen</li><li>Benachrichtigungen &#252;ber Zustimmungen erhalten und aktivieren von Zug&#228;ngen</li><li>immun gegen Ignorierfunktion der Privaten Mitteilungen</li><li>weitere kleine Funktionen</li></ul>';
$txt['permissionname_manage_membergroups'] = 'Mitgliedergruppen verwalten und zuordnen';
$txt['permissionhelp_manage_membergroups'] = 'Diese Berechtigung erlaubt das Editieren von Mitgliedergruppen und zuordnen von Benutzern zu anderen Mitgliedergruppen.';
$txt['permissionname_manage_permissions'] = 'Berechtigungen verwalten';
$txt['permissionhelp_manage_permissions'] = 'Diese Berechtigung erlaubt das &#196;ndern aller Berechtigungen einer Mitgliedergruppe bzw. eines Boards (global und lokal).';
$txt['permissionname_manage_bans'] = 'Bann-Liste verwalten';
$txt['permissionhelp_manage_bans'] = 'Diese Berechtigung erlaubt das Hinzuf&#252;gen oder L&#246;schen von Benutzernamen, IP-Adressen, Hostnamen und E-Mail Adressen zu einer Liste von gebannten Benutzern. Sie erlaubt weiterhin das Anschauen bzw. L&#246;schen von Protokolleintr&#228;gen der gebannten Benutzer, die probieren sich wieder einzuloggen.';
$txt['permissionname_send_mail'] = 'Forum E-Mail an Mitglieder senden';
$txt['permissionhelp_send_mail'] = 'Sendet eine E-Mail oder wahlweise eine Private Mitteilung an alle Mitglieder oder an eine bestimmte Gruppe. Ben&#246;tigt die Berechtigung \'Private Mitteilungen senden\'.';

$txt['permissiongroup_profile'] = 'Benutzerprofile';
$txt['permissionname_profile_view'] = 'Profil und Statistiken anschauen';
$txt['permissionhelp_profile_view'] = 'Diese Berechtigung erlaubt das Anklicken des Benutzernamens und somit das Betrachten des Profils, der Statistiken und den Beitr&#228;gen des Benutzers.';
$txt['permissionname_profile_view_own'] = 'Eigenes Profil';
$txt['permissionname_profile_view_any'] = 'Alle Profile';
$txt['permissionname_profile_identity'] = 'Benutzerkonto-Einstellungen';
$txt['permissionhelp_profile_identity'] = 'Benutzerkonto-Einstellungen sind die einfachsten Einstellungen im Profil des Benutzers wie Passwort, E-Mail, Mitgliedergruppe und bevorzugte Sprache.';
$txt['permissionname_profile_identity_own'] = 'Eigenes Profil';
$txt['permissionname_profile_identity_any'] = 'Alle Profile';
$txt['permissionname_profile_extra'] = 'Erweiterte Profil-Einstellungen';
$txt['permissionhelp_profile_extra'] = 'Erweiterte Profil-Einstellungen inklusive Optionen f&#252;r das Benutzerbild, Theme-Einstellungen, Benachrichtigungen und Private Mitteilungen.';
$txt['permissionname_profile_extra_own'] = 'Eigenes Profil';
$txt['permissionname_profile_extra_any'] = 'Alle Profile';
$txt['permissionname_profile_title'] = 'Pers&#246;nlichen Titel &#228;ndern';
$txt['permissionhelp_profile_title'] = 'Der pers&#246;nliche Titel wird im Beitrag unterhalb des Namens angezeigt (wenn f&#252;r diesen Benutzer vorhanden).';
$txt['permissionname_profile_title_own'] = 'Eigenes Profil';
$txt['permissionname_profile_title_any'] = 'Alle Profile';
$txt['permissionname_profile_remove'] = 'Benutzerkonto l&#246;schen';
$txt['permissionhelp_profile_remove'] = 'Diese Berechtigung erlaubt dem Benutzer sein Benutzerkonto zu l&#246;schen, wenn sie auf \'Eigenes Profil\' gestellt ist.';
$txt['permissionname_profile_remove_own'] = 'Eigenes Benutzerkonto';
$txt['permissionname_profile_remove_any'] = 'Jedes Benutzerkonto';
$txt['permissionname_profile_server_avatar'] = 'W&#228;hlen Sie ein Benutzerbild vom Server';
$txt['permissionhelp_profile_server_avatar'] = 'Aktivieren Sie die Option, um den Benutzern die M&#246;glichkeit zu geben, ein auf diesem Server gespeichertes Benutzerbild auszuw&#228;hlen.';
$txt['permissionname_profile_upload_avatar'] = 'Benutzerbild auf diesen Server hochladen';
$txt['permissionhelp_profile_upload_avatar'] = 'Erlaubt den Benutzern das Hochladen ihrer eigenen Benutzerbilder auf diesen Server.';
$txt['permissionname_profile_remote_avatar'] = 'Externes Benutzerbild w&#228;hlen';
$txt['permissionhelp_profile_remote_avatar'] = 'Externe Benutzerbilder k&#246;nnen die Geschwindigkeit des Forums herabsetzen, so dass es sinnvoll ist, bestimmten Gruppen dieses Recht zu entziehen. ';

$txt['permissiongroup_general_board'] = 'Generell';
$txt['permissionname_moderate_board'] = 'Board moderieren';
$txt['permissionhelp_moderate_board'] = 'Diese Berechtigung gibt dem Moderator weitere Funktionen, die ihn zu einem richtigen Moderator zu machen. Das umfasst die M&#246;glichkeit auf geschlossene Themen zu antworten, die Ablaufzeit von Umfragen zu &#228;ndern und die Umfragenergebnisse jederzeit einzusehen.';

$txt['permissiongroup_topic'] = 'Themen';
$txt['permissionname_post_new'] = 'Neues Thema erstellen';
$txt['permissionhelp_post_new'] = 'Diese Berechtigung erlaubt dem Benutzer neue Themen zu starten. Sie erlaubt jedoch nicht, auf Themen zu antworten.';
$txt['permissionname_merge_any'] = 'Themen zusammenf&#252;hren';
$txt['permissionhelp_merge_any'] = 'F&#252;hrt zwei oder mehr Themen zusammen. Die Reihenfolge der Beitr&#228;ge basiert auf der Zeit, wann diese geschrieben worden sind, d.h. der &#228;lteste Beitrag ist am Schluss des neuen Themas. Der Benutzer kann nur Themen in Boards zusammenf&#252;hren, zu denen er auch Zugriff hat. Um gleichzeitig mehrere Themen zusammen zu f&#252;hren, muss die Funktion Schnellmoderation im Profil aktiviert sein.';
$txt['permissionname_split_any'] = 'Thema teilen';
$txt['permissionhelp_split_any'] = 'Teilt ein Thema in zwei einzelne Themen.';
$txt['permissionname_send_topic'] = 'Thema an ein Freund senden';
$txt['permissionhelp_send_topic'] = 'Diese Berechtigung erlaubt es dem Benutzer, einem Freund einen Hinweis auf ein Thema per E-Mail zu senden. Dazu muss er die E-Mail Adresse eingeben und kann einen Nachricht dazu verfassen.';
$txt['permissionname_make_sticky'] = 'Thema fixieren';
$txt['permissionhelp_make_sticky'] = 'Fixierte Themen werden immer als erste Themen in einem Board angezeigt. Sie sind n&#252;tzlich f&#252;r Ank&#252;ndigungen oder wichtige Hinweise.';
$txt['permissionname_move'] = 'Thema verschieben';
$txt['permissionhelp_move'] = 'Verschiebt ein Thema von einem Board in ein anderes. Die Benutzer k&#246;nnen nur die Boards als Ziel w&#228;hlen, auf welche sie Zugriff haben.';
$txt['permissionname_move_own'] = 'Eigenes Thema';
$txt['permissionname_move_any'] = 'Jedes Thema';
$txt['permissionname_lock'] = 'Themen schlie&#223;en';
$txt['permissionhelp_lock'] = 'Diese Berechtigung erlaubt dem Benutzer das Schlie&#223;en von Themen. Danach kann kein Benutzer mehr auf das Thema antworten, au&#223;er Benutzer mit \'Moderator\' Rechten.';
$txt['permissionname_lock_own'] = 'Eigene Themen';
$txt['permissionname_lock_any'] = 'Alle Themen';
$txt['permissionname_remove'] = 'Themen l&#246;schen';
$txt['permissionhelp_remove'] = 'Diese Berechtigung erlaubt das L&#246;schen ganzer Themen. Bitte beachten Sie, dass mit dieser Funktion keine einzelnen Beitr&#228;ge gel&#246;scht werden d&#252;rfen!';
$txt['permissionname_remove_own'] = 'Eigene Themen';
$txt['permissionname_remove_any'] = 'Alle Themen';
$txt['permissionname_post_reply'] = 'Antworten auf Themen schreiben';
$txt['permissionhelp_post_reply'] = 'Diese Berechtigung erlaubt das Antworten auf Themen.';
$txt['permissionname_post_reply_own'] = 'Eigene Themen';
$txt['permissionname_post_reply_any'] = 'Alle Themen';
$txt['permissionname_modify_replies'] = 'Antworten auf eigene Themen &#228;ndern';
$txt['permissionhelp_modify_replies'] = 'Diese Berechtigung erlaubt das nachtr&#228;gliche &#196;ndern aller Beitr&#228;ge in einem vom ihm erstellten Themen.';
$txt['permissionname_delete_replies'] = 'Antworten auf eigene Themen l&#246;schen';
$txt['permissionhelp_delete_replies'] = 'Diese Berechtigung erlaubt es dem Benutzer, alle Antworten auf ein von ihm erstelltes Thema zu l&#246;schen.';
$txt['permissionname_announce_topic'] = 'Thema ank&#252;ndigen';
$txt['permissionhelp_announce_topic'] = 'Diese Berechtigung erlaubt das Senden einer Ank&#252;ndigungs E-Mail &#252;ber ein Thema an alle Mitglieder oder an bestimmte Mitgliedergruppen.';

$txt['permissiongroup_post'] = 'Beitr&#228;ge';
$txt['permissionname_delete'] = 'Beitr&#228;ge l&#246;schen';
$txt['permissionhelp_delete'] = 'Erlaubt dem Benutzer Beitr&#228;ge zu l&#246;schen, erlaubt jedoch nicht, den ersten Beitrag eines Themas zu l&#246;schen.';
$txt['permissionname_delete_own'] = 'Eigener Beitrag';
$txt['permissionname_delete_any'] = 'Alle Beitr&#228;ge';
$txt['permissionname_modify'] = 'Beitr&#228;ge editieren';
$txt['permissionhelp_modify'] = 'Erlaubt das Editieren von Beitr&#228;gen';
$txt['permissionname_modify_own'] = 'Eigener Beitrag';
$txt['permissionname_modify_any'] = 'Alle Beitr&#228;ge';
$txt['permissionname_report_any'] = 'Beitr&#228;ge dem Moderator melden';
$txt['permissionhelp_report_any'] = 'Diese Berechtigung f&#252;gt einen Link zu jedem Beitrag hinzu, welcher es dem Benutzer erlaubt, diesen einem Moderator zu melden. Bei einer Meldung erhalten alle Moderatoren des Boards diese Nachricht mit einer Beschreibung des Benutzers und dem Link zum entsprechenden Beitrag.';

$txt['permissiongroup_poll'] = 'Umfragen';
$txt['permissionname_poll_view'] = 'Umfrage anschauen';
$txt['permissionhelp_poll_view'] = 'Diese Berechtigung erlaubt dem Benutzer das Anschauen einer Umfrage. Ohne diese Berechtigung sieht der Benutzer nur das Thema ohne die Umfrage.';
$txt['permissionname_poll_vote'] = 'Abstimmen in Umfragen';
$txt['permissionhelp_poll_vote'] = 'Diese Berechtigung erlaubt einem registrierten Benutzer das Abgeben einer Stimme bei Umfragen. Die Funktion gilt nicht f&#252;r G&#228;ste.';
$txt['permissionname_poll_post'] = 'Umfragen erstellen';
$txt['permissionhelp_poll_post'] = 'Diese Berechtigung erlaubt dem Benutzer eine neue Umfrage zu erstellen.';
$txt['permissionname_poll_add'] = 'Umfragen zu Themen hinzuf&#252;gen';
$txt['permissionhelp_poll_add'] = 'F&#252;gt einen Umfrage zu einem existierenden Thema hinzu. Diese Berechtigung erfordert die M&#246;glichkeit, den ersten Beitrag eines Themas zu &#228;ndern.';
$txt['permissionname_poll_add_own'] = 'Eigene Themen';
$txt['permissionname_poll_add_any'] = 'Alle Themen';
$txt['permissionname_poll_edit'] = 'Umfragen editieren';
$txt['permissionhelp_poll_edit'] = 'Diese Berechtigung erlaubt die &#196;nderung einer bestehenden Umfrage sowie das Zur&#252;cksetzen der Stimmen auf Null. Um die Anzahl der max. abzugebenen Stimmen und die Laufzeit der Umfrage zu &#228;ndern, muss der Benutzer \'Moderator\' Rechte besitzen.';
$txt['permissionname_poll_edit_own'] = 'Eigene Umfrage';
$txt['permissionname_poll_edit_any'] = 'Alle Umfragen';
$txt['permissionname_poll_lock'] = 'Umfragen schlie&#223;en';
$txt['permissionhelp_poll_lock'] = 'Verhindert das weitere Abstimmen in der betreffenden Umfrage.';
$txt['permissionname_poll_lock_own'] = 'Eigene Umfragen';
$txt['permissionname_poll_lock_any'] = 'Alle Umfragen';
$txt['permissionname_poll_remove'] = 'Umfragen l&#246;schen';
$txt['permissionhelp_poll_remove'] = 'Diese Berechtigung erlaubt das L&#246;schen einer Umfrage.';
$txt['permissionname_poll_remove_own'] = 'Eigene Umfrage';
$txt['permissionname_poll_remove_any'] = 'Alle Umfragen';

$txt['permissiongroup_notification'] = 'Benachrichtigungen';
$txt['permissionname_mark_any_notify'] = 'Benachrichtigung bei neuen Antworten';
$txt['permissionhelp_mark_any_notify'] = 'Diese Berechtigung erlaubt das Einstellen von Benachrichtigungen f&#252;r gew&#252;nschte Themen.';
$txt['permissionname_mark_notify'] = 'Benachrichtigung bei neuen Themen';
$txt['permissionhelp_mark_notify'] = 'Diese Berechtigung erlaubt das Aktivieren von Benachrichtigungen bei Boards, sobald ein neues Thema in diesem geschrieben wurde.';

$txt['permissiongroup_attachment'] = 'Dateianh&#228;nge';
$txt['permissionname_view_attachments'] = 'Dateianh&#228;nge anschauen';
$txt['permissionhelp_view_attachments'] = 'Dateianh&#228;nge sind Dateien, die an einen Beitrag angef&#252;gt worden sind. Diese Funktion kann im Bereich \'Dateianh&#228;nge verwalten\' aktiviert und eingestellt werden. Dateianh&#228;nge sind nicht direkt anw&#228;hlbar und k&#246;nnen f&#252;r die Benutzer blockiert werden, die diese Berechtigung nicht besitzen.';
$txt['permissionname_post_attachment'] = 'Dateianh&#228;nge erstellen';
$txt['permissionhelp_post_attachment'] = 'Dateianh&#228;nge sind Dateien, die an einen Beitrag angef&#252;gt worden sind. Ein Beitag kann mehrere Dateianh&#228;nge enthalten.';

$txt['permissionicon'] = '';

$txt['permission_settings_title'] = 'Einstellungen';
$txt['groups_manage_permissions'] = 'Gruppen, die Berechtigungen verwalten d&#252;rfen';
$txt['permission_settings_submit'] = 'Speichern';
$txt['permission_settings_enable_deny'] = 'Verbieten von Berechtigungen aktivieren';
// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['permission_disable_deny_warning'] = 'Das Deaktivieren dieser Option &#228;ndert die \\\'Verboten\\\'-Berechtigung in \\\'Nicht erlauben\\\'.';
$txt['permission_by_membergroup_desc'] = 'Hier k&#246;nnen Sie die globalen Berechtigungen f&#252;r jede Mitgliedergruppe einstellen. Diese Berechtigungen gelten in allen Boards, wo sie nicht von lokalen Berechtigungen &#252;berschrieben werden, welche im Bereich \'Berechtigungen der Boards\' erstellt werden k&#246;nnen.';
$txt['permission_by_board_desc'] = 'Hier k&#246;nnen Sie einstellen, ob ein Board die globalen Berechtigungen benutzt oder ein eigenes Schema verwendet. Sollten Sie lokale Berechtigungen verwenden, k&#246;nnen Sie im betreffenden Board f&#252;r jede Mitgliedergruppe individuelle Berechtigungen angeben.';
$txt['permission_settings_desc'] = 'W&#228;hlen Sie hier, wer die Erlaubnis zum &#228;ndern der Berechtigungen hat und wie komplex das Berechtigungssystem sein soll.';
$txt['permission_settings_enable_postgroups'] = 'Berechtigungen f&#252;r beitragsbasierende Gruppen aktivieren';
// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['permission_disable_postgroups_warning'] = 'Das Deaktivieren dieser Option entfernt die gesetzten Berechtigungen der beitragsbasierenden Gruppen.';
$txt['permission_settings_enable_by_board'] = 'Erweiterte Board Berechtigungen aktivieren';
// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['permission_disable_by_board_warning'] = 'Das Deaktivieren dieser Einstellung entfernt alle Board Berechtigungen.';
