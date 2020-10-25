<?php
// Version: 1.1.2; index

global $forum_copyright, $forum_version, $webmaster_email;

// Locale (strftime, pspell_new) and spelling. (pspell_new, can be left as '' normally.)
// For more information see:
//   - http://www.php.net/function.pspell-new
//   - http://www.php.net/function.setlocale
// Again, SPELLING SHOULD BE '' 99% OF THE TIME!!  Please read this!
$txt['lang_locale'] = 'de_DE';
$txt['lang_dictionary'] = 'de';
$txt['lang_spelling'] = '';

// Character set and right to left?
$txt['lang_character_set'] = 'ISO-8859-1';
$txt['lang_rtl'] = false;

$txt['days'] = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
$txt['days_short'] = ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'];
// Months must start with 1 => 'January'. (or translated, of course.)
$txt['months'] = [1 => 'Januar', 'Februar', 'M&#228;rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
$txt['months_titles'] = [1 => 'Januar', 'Februar', 'M&#228;rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
$txt['months_short'] = [1 => 'Jan', 'Feb', 'M&#228;r', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'];

$txt['newmessages0'] = 'ist neu';
$txt['newmessages1'] = 'sind neu';
$txt['newmessages3'] = 'Neu';
$txt['newmessages4'] = ',';

$txt[2] = 'Administrator';

$txt[10] = 'Speichern';

$txt[17] = '&#196;ndern';
$txt[18] = $context['forum_name'] . ' - Index';
$txt[19] = 'Mitglieder';
$txt[20] = 'Boardname';
$txt[21] = 'Beitr&#228;ge';
$txt[22] = 'Letzter Beitrag';

$txt[24] = '(Kein Betreff)';
$txt[26] = 'Beitr&#228;ge';
$txt[27] = 'Profil anzeigen';
$txt[28] = 'Gast';
$txt[29] = 'Autor';
$txt[30] = 'am';
$txt[31] = 'L&#246;schen';
$txt[33] = 'Neues Thema';

$txt[34] = 'Einloggen';
// Use numeric entities in the below string.
$txt[35] = 'Benutzername';
$txt[36] = 'Passwort';

$txt[40] = 'Benutzername nicht vorhanden.';

$txt[62] = 'Moderator';
$txt[63] = 'Thema l&#246;schen';
$txt[64] = 'Themen';
$txt[66] = 'Beitrag &#228;ndern';
$txt[68] = 'Name';
$txt[69] = 'E-Mail';
$txt[70] = 'Betreff';
$txt[72] = 'Text';

$txt[79] = 'Profil';

$txt[81] = 'Passwort w&#228;hlen';
$txt[82] = 'Passwort wiederholen';
$txt[87] = 'Position';

$txt[92] = 'Profil anzeigen von';
$txt[94] = 'Alle';
$txt[95] = 'Beitr&#228;ge';
$txt[96] = 'Webseite';
$txt[97] = 'Registrieren';

$txt[101] = 'Themen-Index';
$txt[102] = 'News';
$txt[103] = '&#220;bersicht';
$txt[99103] = 'Startseite';

$txt[104] = 'Thema schlie&#223;en/&#246;ffnen';
$txt[105] = 'Schreiben';
$txt[106] = 'Ein Fehler ist aufgetreten!';
$txt[107] = 'von';
$txt[108] = 'Ausloggen';
$txt[109] = 'Begonnen von';
$txt[110] = 'Antworten';
$txt[111] = 'Letzter Beitrag';
$txt[114] = 'Administrator-Login';
// Use numeric entities in the below string.
$txt[118] = 'Thema';
$txt[119] = 'Hilfe';
$txt[121] = 'Beitrag l&#246;schen';
$txt[125] = 'Benachrichtigen';
$txt[126] = 'M&#246;chten Sie eine Benachrichtigung per E-Mail, wenn eine Antwort zu diesem Thema geschrieben wird?';
$txt[130] = "Lieben Gru&#223;,\ndas " . $context['forum_name'] . ' Team.';
// Use numeric entities in the below string.
$txt[131] = 'Benachrichtigen';
$txt[132] = 'Thema verschieben';
$txt[133] = 'Verschieben nach';
$txt[139] = 'Seiten';
$txt[140] = 'Aktive Benutzer in den letzten ' . $modSettings['lastActive'] . ' Minuten';
$txt[144] = 'Private Mitteilungen';
$txt[145] = 'Zitieren';
$txt[146] = 'Antworten';

$txt[151] = 'Keine Nachrichten...';
$txt[152] = 'Sie haben';
$txt[153] = 'Nachrichten';
$txt[154] = 'Nachricht l&#246;schen';

$txt[158] = 'Benutzer Online';
$txt[159] = 'Private Mitteilung';
$txt[160] = 'Gehe zu';
$txt[161] = 'Los';
$txt[162] = 'Sind Sie sicher, dass Sie dieses Thema l&#246;schen m&#246;chten?';
$txt[163] = 'Ja';
$txt[164] = 'Nein';

$txt[166] = 'Suchergebnisse';
$txt[167] = 'Ende der Ergebnisse';
$txt[170] = 'Keine &#220;bereinstimmungen gefunden';
$txt[176] = 'am';

$txt[182] = 'Suche';
$txt[190] = 'Alle';

$txt[193] = 'Zur&#252;ck';
$txt[194] = 'Passwort erinnern';
$txt[195] = 'Thema gestartet von';
$txt[196] = 'Titel';
$txt[197] = 'Beitrag von';
$txt[200] = 'Liste aller registrierten Mitglieder';
$txt[201] = 'Herzlich Willkommen';
$txt[208] = 'Administrator-Center';
$txt[211] = 'Letzte &#196;nderung';
$txt[212] = 'M&#246;chten Sie die E-Mail Benachrichtigung zu diesem Thema deaktivieren?';

$txt[214] = 'Neueste Beitr&#228;ge';

$txt[227] = 'Ort';
$txt[231] = 'Geschlecht';
$txt[233] = 'Registrierungsdatum';

$txt[234] = 'Anzeigen der neuesten Beitr&#228;ge';
$txt[235] = 'ist das zuletzt ge&#228;nderte Thema';

$txt[238] = 'M&#228;nnlich';
$txt[239] = 'Weiblich';

$txt[240] = 'Ung&#252;ltiges Zeichen im Benutzernamen.';

$txt['welcome_guest'] = 'Willkommen <b>' . $txt[28] . '</b>. Bitte <a href="' . $scripturl . '?action=login">einloggen</a> oder <a href="' . $scripturl . '?action=register">registrieren</a>.';
$txt['welcome_guest_activate'] = '<br>Haben Sie Ihre <a href="' . $scripturl . '?action=activate">Aktivierungs E-Mail</a> &#252;bersehen?';
$txt['hello_member'] = 'Hallo';
// Use numeric entities in the below string.
$txt['hello_guest'] = 'Willkommen';
$txt[247] = 'Hallo';
$txt[248] = 'Willkommen';
$txt[249] = 'Bitte';
$txt[250] = 'Zur&#252;ck';
$txt[251] = 'Bitte w&#228;hlen Sie ein Ziel';

// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt[279] = 'Autor';

$txt[287] = 'Smiley';
$txt[288] = '&#196;rgerlich';
$txt[289] = 'L&#228;chelnd';
$txt[290] = 'Lachend';
$txt[291] = 'Traurig';
$txt[292] = 'Zwinkernd';
$txt[293] = 'Grinsend';
$txt[294] = 'Schockiert';
$txt[295] = 'Cool';
$txt[296] = 'Huch';
$txt[450] = 'Augen rollen';
$txt[451] = 'Zunge';
$txt[526] = 'Verlegen';
$txt[527] = 'Schweigend';
$txt[528] = 'Unentschlossen';
$txt[529] = 'K&#252;sschen';
$txt[530] = 'Weinen';

$txt[298] = 'Moderator';
$txt[299] = 'Moderatoren';

$txt[300] = 'Alle Themen im Board als gelesen markieren';
$txt[301] = 'Aufrufe';
$txt[302] = 'Neu';

$txt[303] = 'Mitglieder anzeigen';
$txt[305] = 'Anzeigen';
$txt[307] = 'E-Mail';

$txt[308] = 'Mitglieder anzeigen';
$txt[309] = 'von';
$txt[310] = 'Mitglieder insgesamt';
$txt[311] = 'bis';
$txt[315] = 'Passwort vergessen?';

$txt[317] = 'Datum';
// Use numeric entities in the below string.
$txt[318] = 'Von';
$txt[319] = 'Betreff';
$txt[322] = 'Neue Nachrichten abholen';
$txt[324] = 'An';

$txt[330] = 'Themen';
$txt[331] = 'Mitglieder';
$txt[332] = 'Mitgliederliste';
$txt[333] = 'Neue Beitr&#228;ge';
$txt[334] = 'Keine neuen Beitr&#228;ge';

$txt['sendtopic_send'] = 'Senden';

$txt[371] = 'Zeitverschiebung';
$txt[377] = 'oder';

$txt[398] = 'Keine &#220;bereinstimmungen gefunden';

$txt[418] = 'Benachrichtigung';

$txt[430] = '%s, Sie sind aus diesem Forum verbannt!';

$txt[452] = 'Alle gelesen';

$txt[454] = 'Hei&#223;es Thema (mehr als ' . $modSettings['hotTopicPosts'] . ' Antworten)';
$txt[455] = 'Sehr hei&#223;es Thema (mehr als ' . $modSettings['hotTopicVeryPosts'] . ' Antworten)';
$txt[456] = 'Thema geschlossen';
$txt[457] = 'Normales Thema';
$txt['participation_caption'] = 'Themen auf die Sie geantwortet haben';

$txt[462] = 'Los';

$txt[465] = 'Drucken';
$txt[467] = 'Profil';
$txt[468] = 'Zusammenfassung';
$txt[470] = 'Nicht verf&#252;gbar';
$txt[471] = 'Nachricht';
$txt[473] = 'Dieser Name ist bereits in Verwendung.';

$txt[488] = 'Mitglieder insgesamt';
$txt[489] = 'Beitr&#228;ge insgesamt';
$txt[490] = 'Themen insgesamt';

$txt[497] = 'Sitzungsl&#228;nge in Minuten';

$txt[507] = 'Vorschau';
$txt[508] = 'Immer eingeloggt bleiben';

$txt[511] = 'Gespeichert';
// Use numeric entities in the below string.
$txt[512] = 'IP';

$txt[513] = 'ICQ';
$txt[515] = 'WWW';

$txt[525] = 'von';

$txt[578] = 'Stunden';
$txt[579] = 'Tage';

$txt[581] = ', unser neuestes Mitglied.';

$txt[582] = 'Suchen nach';

$txt[603] = 'AIM';
// In this string, please use +'s for spaces.
$txt['aim_default_message'] = 'Hallo.+Sind+Sie+online?';
$txt[604] = 'YIM';

$txt[616] = 'Nicht vergessen, das Forum ist im \'Wartungsmodus\'!';

$txt[641] = 'Gelesen';
$txt[642] = 'mal';

$txt[645] = 'Forum-Statistiken';
$txt[656] = 'Neuestes Mitglied';
$txt[658] = 'Kategorien insgesamt';
$txt[659] = 'Letzter Beitrag';

$txt[660] = 'Sie haben';
$txt[661] = 'Klicken Sie';
$txt[662] = 'hier';
$txt[663] = 'um sie zu sehen.';

$txt[665] = 'Boards insgesamt';

$txt[668] = 'Seite drucken';

$txt[679] = 'Es muss eine g&#252;ltige E-Mail Adresse sein.';

$txt[683] = 'Ich bin ein Freak!!';
$txt[685] = $context['forum_name'] . ' - Info-Center';

$txt[707] = 'Thema senden';

$txt['sendtopic_title'] = 'Senden Sie das Thema &quot;%s&quot; einem Freund.';
// Use numeric entities in the below three strings.
$txt['sendtopic_dear'] = 'Hallo %s,';
$txt['sendtopic_this_topic'] = 'Sehen Sie sich bitte folgendes Thema an: %s, am ' . $context['forum_name'] . '. Klicken Sie dazu auf den Link';
$txt['sendtopic_thanks'] = 'Danke';
$txt['sendtopic_sender_name'] = 'Ihr Name';
$txt['sendtopic_sender_email'] = 'Ihre E-Mail Adresse';
$txt['sendtopic_receiver_name'] = 'Name des Empf&#228;ngers';
$txt['sendtopic_receiver_email'] = 'E-Mail Adresse des Empf&#228;ngers';
$txt['sendtopic_comment'] = 'Kommentar hinzuf&#252;gen';
// Use numeric entities in the below string.
$txt['sendtopic2'] = 'Ein Kommentar wurde zu diesem Thema hinzugef&#252;gt';

$txt[721] = 'E-Mail Adresse nicht anzeigen (empfohlen)?';

$txt[737] = 'Alle markieren';

// Use numeric entities in the below string.
$txt[1001] = 'Datenbankfehler';
$txt[1002] = 'Bitte versuchen Sie es nochmal. Sollte der Fehler wieder auftreten, informieren Sie bitte den Administrator.';
$txt[1003] = 'Datei';
$txt[1004] = 'Zeile';
// Use numeric entities in the below string.
$txt[1005] = 'SMF hat einen Datenbankfehler entdeckt und versucht ihn automatisch zu reparieren. Wenn Sie erneut Probleme haben sollten oder weiterhin diese E-Mails erhalten, kontaktieren Sie bitte Ihren Serveranbieter.';
$txt['database_error_versions'] = '<b>Achtung:</b> Ihre Datenbank scheint veraltet zu sein! Ihre Dateien haben die Version '
                                       . $forum_version
                                       . ', wogegen die Datenbank die Version '
                                       . $modSettings['smfVersion']
                                       . ' hat. Es wird dringend empfohlen, die neueste Version der upgrade.php auszuf&#252;hren.';
$txt['template_parse_error'] = 'Template Parse Error!';
$txt['template_parse_error_message'] = 'Ein Fehler ist im Template-System des Forums aufgetreten! Dieses Problem sollte nur tempor&#228;r auftreten, bitte versuchen Sie es sp&#228;ter nochmal. Sollten Sie die Fehlermeldung weiterhin erhalten, kontaktieren Sie bitte den Administrator.<br><br>Sie k&#246;nnen versuchen die Seite zu <a href="javascript:location.reload();">aktualisieren</a>.';
$txt['template_parse_error_details'] = 'Ein Problem trat beim Laden des <tt><b>%1$s</b></tt> Templates oder der Sprachdatei auf. Bitte &#252;berpr&#252;fen Sie die Syntax und versuchen Sie es erneut. Bitte beachten Sie, dass einzelne Anf&#252;hrungszeichen (<tt>\'</tt>) oft mit einem Slash (<tt>\\</tt>) auskommentiert werden m&#252;ssen. Um n&#228;here Informationen von PHP zu diesem Fehler zu erhalten, versuchen Sie <a href="'
                                       . $boardurl
                                       . '%1$s">die Seite direkt aufzurufen</a>.<br><br>Sie k&#246;nnen auch versuchen, die Seite zu <a href="javascript:location.reload();">aktualisieren</a> oder das <a href="'
                                       . $scripturl
                                       . '?theme=1">Standard-Theme</a> zu benutzen.';

$txt['smf10'] = '<b>Heute</b> um ';
$txt['smf10b'] = '<b>Gestern</b> um ';
$txt['smf20'] = 'Neue Umfrage';
$txt['smf21'] = 'Frage';
$txt['smf23'] = 'Abstimmen';
$txt['smf24'] = 'Stimmen insgesamt';
$txt['smf25'] = 'Shortcuts: Alt+S f&#252;r das Absenden oder Alt+P f&#252;r die Vorschau';
$txt['smf29'] = 'Ergebnisse anzeigen';
$txt['smf30'] = 'Umfrage schlie&#223;en';
$txt['smf30b'] = 'Umfrage &#246;ffnen';
$txt['smf39'] = 'Umfrage editieren';
$txt['smf43'] = 'Umfrage';
$txt['smf47'] = '1 Tag';
$txt['smf48'] = '1 Woche';
$txt['smf49'] = '1 Monat';
$txt['smf50'] = 'Immer';
$txt['smf52'] = 'Einloggen mit Benutzername, Passwort und Sitzungsl&#228;nge';
$txt['smf53'] = '1 Stunde';
$txt['smf56'] = 'VERSCHOBEN';
$txt['smf57'] = 'Bitte geben Sie einen kurzen Hinweis ein, <br>warum das Thema verschoben wird.';
$txt['smf60'] = 'Sie haben zu wenige Beitr&#228;ge geschrieben, um das Karma zu &#228;ndern - Sie brauchen mindestens ';
$txt['smf62'] = 'Sie k&#246;nnen nicht wiederholt abstimmen. Bitte warten Sie ';
$txt['smf82'] = 'Board';
$txt['smf88'] = 'in';
$txt['smf96'] = 'Fixiertes Thema';

$txt['smf138'] = 'L&#246;schen';

$txt['smf199'] = 'Ihre Privaten Mitteilungen';

$txt['smf211'] = 'KB';

$txt['smf223'] = '[Weitere Statistiken]';

// Use numeric entities in the below three strings.
$txt['smf238'] = 'Code';
$txt['smf239'] = 'Zitat von';
$txt['smf240'] = 'Zitat';

$txt['smf251'] = 'Thema teilen';
$txt['smf252'] = 'Themen zusammenf&#252;hren';
$txt['smf254'] = 'Betreff f&#252;r das neue Thema';
$txt['smf255'] = 'Nur diesen Beitrag trennen.';
$txt['smf256'] = 'Thema bis und inkl. diesem Beitrag aufteilen.';
$txt['smf257'] = 'Beitr&#228;ge ausw&#228;hlen, welche geteilt werden sollen.';
$txt['smf258'] = 'Neues Thema';
$txt['smf259'] = 'Thema erfolgreich in zwei Themen aufgeteilt.';
$txt['smf260'] = 'Urspr&#252;ngliches Thema';
$txt['smf261'] = 'Bitte w&#228;hlen Sie die Beitr&#228;ge aus, die Sie trennen m&#246;chten.';
$txt['smf264'] = 'Themen erfolgreich zusammengef&#252;hrt.';
$txt['smf265'] = 'Neu zusammengef&#252;hrtes Thema';
$txt['smf266'] = 'Thema, welches zusammengef&#252;hrt werden soll';
$txt['smf267'] = 'Ziel-Board';
$txt['smf269'] = 'Ziel-Thema';
$txt['smf274'] = 'Sind Sie sicher, dass Sie folgende Themen zusammenf&#252;hren m&#246;chten';
$txt['smf275'] = 'mit';
$txt['smf276'] = 'Diese Funktion wird die Beitr&#228;ge von zwei Themen zu einem Thema zusammenf&#252;hren. Die Beitr&#228;ge werden zeitlich sortiert sein, d.h. der &#228;lteste Beitrag wird der erste im zusammengef&#252;hrten Thema sein.';

$txt['smf277'] = 'Thema fixieren';
$txt['smf278'] = 'Fixierung aufheben';
$txt['smf279'] = 'Thema schlie&#223;en';
$txt['smf280'] = 'Thema &#246;ffnen';

$txt['smf298'] = 'Erweiterte Suche';

$txt['smf299'] = 'GROSSES SICHERHEITSRISIKO:';
$txt['smf300'] = 'Sie haben folgende Datei(en) nicht gel&#246;scht: ';

$txt['smf301'] = 'Seite erstellt in ';
$txt['smf302'] = ' Sekunden mit ';
$txt['smf302b'] = ' Zugriffen.';

$txt['smf315'] = 'Benutzen Sie diese Funktion, um Moderatoren/Administratoren &#252;ber einen missbr&#228;uchlich oder falsch geschriebenen Beitrag zu informieren.<br><i>Bitte beachten Sie, dass Ihre E-Mail Adresse zum betreffenden Moderator gesendet wird, wenn Sie diese Funktion benutzen.</i>';

$txt['online2'] = 'Online';
$txt['online3'] = 'Offline';
$txt['online4'] = 'Private Mitteilung (Online)';
$txt['online5'] = 'Private Mitteilung (Offline)';
$txt['online8'] = 'Status';

$txt['topbottom4'] = 'Nach oben';
$txt['topbottom5'] = 'Nach unten';

$forum_copyright = '<a href="http://www.simplemachines.org/" title="Simple Machines Forum" target="_blank">Powered by ' . $forum_version . '</a> |
<a href="http://www.simplemachines.org/about/copyright.php" title="Free Forum Software" target="_blank">SMF © 2006, Simple Machines LLC</a>';

$txt['calendar3'] = 'Geburtstage:';
$txt['calendar4'] = 'Ereignisse:';
$txt['calendar3b'] = 'Zuk&#252;nftige Geburtstage:';
$txt['calendar4b'] = 'Zuk&#252;nftige Ereignisse:';
// Prompt for holidays in the calendar, leave blank to just display the holiday's name.
$txt['calendar5'] = '';
$txt['calendar9'] = 'Monat:';
$txt['calendar10'] = 'Jahr:';
$txt['calendar11'] = 'Tag:';
$txt['calendar12'] = 'Ereignis-Titel:';
$txt['calendar13'] = 'Erstellen in:';
$txt['calendar20'] = 'Ereignis editieren';
$txt['calendar21'] = 'Dieses Ereignis l&#246;schen?';
$txt['calendar22'] = 'Ereignis l&#246;schen';
$txt['calendar23'] = 'Ereignis erstellen';
$txt['calendar24'] = 'Kalender';
$txt['calendar37'] = 'Link zum Kalender';
$txt['calendar43'] = 'Ereignis verlinken';
$txt['calendar47'] = 'Zuk&#252;nftige Ereignisse';
$txt['calendar47b'] = 'Heutige Ereignisse';
$txt['calendar51'] = 'Woche';
$txt['calendar54'] = 'Anzahl der Tage:';
$txt['calendar_how_edit'] = 'Wie &#228;ndert man diese Ereignisse?';
$txt['calendar_link_event'] = 'Ereignis zum Beitrag verlinken:';
$txt['calendar_confirm_delete'] = 'Sind Sie sicher, dass Sie dieses Ereignis l&#246;schen m&#246;chten?';
$txt['calendar_linked_events'] = 'Verlinkte Ereignisse';

$txt['moveTopic1'] = 'Einen Umleitungshinweis angeben';
$txt['moveTopic2'] = 'Titel des Themas &#228;ndern';
$txt['moveTopic3'] = 'Neuer Titel';
$txt['moveTopic4'] = 'Titel jedes Themas &#228;ndern';

$txt['theme_template_error'] = 'Kann das \'%s\' Template nicht laden.';
$txt['theme_language_error'] = 'Kann die \'%s\' Sprachdatei nicht laden.';

$txt['parent_boards'] = 'Untergeordnete Boards';

$txt['smtp_no_connect'] = 'Kann nicht zu SMTP Server verbinden';
$txt['smtp_port_ssl'] = 'SMTP Port Einstellung ist nicht korrekt; sie sollte auf 465 f&#252;r SSL Server stehen.';
$txt['smtp_bad_response'] = 'Konnte Antwortcodes des E-Mail-Servers nicht empfangen';
$txt['smtp_error'] = 'Probleme beim Versenden der E-Mail. Fehler: ';
$txt['mail_send_unable'] = 'Die E-Mail konnte nicht an \'%s\' versendet werden.';

$txt['mlist_search'] = 'Mitglieder suchen';
$txt['mlist_search2'] = 'Erneut suchen';
$txt['mlist_search_email'] = 'Nach E-Mail Adresse suchen';
$txt['mlist_search_messenger'] = 'Nach Messenger Spitzname suchen';
$txt['mlist_search_group'] = 'Nach Position suchen';
$txt['mlist_search_name'] = 'Nach Namen suchen';
$txt['mlist_search_website'] = 'Nach Webseite suchen';
$txt['mlist_search_results'] = 'Suchergebnisse f&#252;r';

$txt['attach_downloaded'] = 'runtergeladen';
$txt['attach_viewed'] = 'angeschaut';
$txt['attach_times'] = 'Mal';

$txt['MSN'] = 'MSN';

$txt['settings'] = 'Einstellungen';
$txt['never'] = 'Nie';
$txt['more'] = 'mehr';

$txt['hostname'] = 'Hostname';
$txt['you_are_post_banned'] = 'Entschuldigung %s, Ihnen ist das Schreiben in diesem Forum verboten worden.';
$txt['ban_reason'] = 'Grund';

$txt['tables_optimized'] = 'Tabellen der Datenbank optimiert';

$txt['add_poll'] = 'Umfrage hinzuf&#252;gen';
$txt['poll_options6'] = 'Sie d&#252;rfen nur %s Optionen w&#228;hlen.';
$txt['poll_remove'] = 'Umfrage entfernen';
$txt['poll_remove_warn'] = 'Sind Sie sicher, dass Sie die Umfrage vom Thema entfernen m&#246;chten?';
$txt['poll_results_expire'] = 'Die Resultate werden angezeigt, wenn die Umfrage geschlossen wird';
$txt['poll_expires_on'] = 'Umfrage schlie&#223;t';
$txt['poll_expired_on'] = 'Umfrage geschlossen';
$txt['poll_change_vote'] = 'Abstimmung &#228;ndern';
$txt['poll_return_vote'] = 'Abstimmungsoptionen';

$txt['quick_mod_remove'] = 'Markierte Themen entfernen';
$txt['quick_mod_lock'] = 'Markierte Themen &#246;ffnen/schlie&#223;en';
$txt['quick_mod_sticky'] = 'Markierte Themen fixieren/aufheben';
$txt['quick_mod_move'] = 'Markierte Themen verschieben';
$txt['quick_mod_merge'] = 'Markierte Themen zusammenf&#252;hren';
$txt['quick_mod_markread'] = 'Markierte Themen als gelesen markieren';
$txt['quick_mod_go'] = 'Los';
$txt['quickmod_confirm'] = 'Sind Sie sicher, dass Sie das tun m&#246;chten?';

$txt['spell_check'] = 'Rechtschreibung pr&#252;fen';

$txt['quick_reply_1'] = 'Schnellantwort';
$txt['quick_reply_2'] = 'Bei der <i>Schnellantwort</i> k&#246;nnen Sie Bulletin Board Code und Smileys wie im normalen Beitrag benutzen.';
$txt['quick_reply_warning'] = 'Warnung: Das Thema ist momentan geschlossen!<br>Nur Administratoren und Moderatoren k&#246;nnen antworten.';

$txt['notification_enable_board'] = 'Sind Sie sicher, dass Sie Benachrichtigungen &#252;ber neue Themen in diesem Board aktivieren m&#246;chten?';
$txt['notification_disable_board'] = 'Sind Sie sicher, dass Sie Benachrichtigungen &#252;ber neue Themen in diesem Board deaktivieren m&#246;chten?';
$txt['notification_enable_topic'] = 'Sind Sie sicher, dass Sie Benachrichtigungen &#252;ber neue Beitr&#228;ge in diesem Thema aktivieren m&#246;chten?';
$txt['notification_disable_topic'] = 'Sind Sie sicher, dass Sie Benachrichtigungen &#252;ber neue Beitr&#228;ge in diesem Thema deaktivieren m&#246;chten?';

$txt['rtm1'] = 'Moderator informieren';

$txt['unread_topics_visit'] = 'Neue ungelesene Themen';
$txt['unread_topics_visit_none'] = 'Keine ungelesenen Themen seit dem letzten Besuch gefunden. <a href="' . $scripturl . '?action=unread;all">Klicken Sie hier, um alle ungelesenen Themen zu suchen.</a>.';
$txt['unread_topics_all'] = 'Alle ungelesenen Themen';
$txt['unread_replies'] = 'Aktualisierte Themen';

$txt['who_title'] = 'Wer ist online';
$txt['who_and'] = ' und ';
$txt['who_viewing_topic'] = ' betrachten dieses Thema.';
$txt['who_viewing_board'] = ' betrachten dieses Board.';
$txt['who_member'] = 'Mitglieder';

$txt['powered_by_php'] = 'Powered by PHP';
$txt['powered_by_mysql'] = 'Powered by MySQL';
$txt['valid_html'] = 'Pr&#252;fe HTML 4.01';
$txt['valid_xhtml'] = 'Pr&#252;fe XHTML 1.0';
$txt['valid_css'] = 'Pr&#252;fe CSS';

$txt['guest'] = 'Gast';
$txt['guests'] = 'G&#228;ste';
$txt['user'] = 'Mitglied';
$txt['users'] = 'Mitglieder';
$txt['hidden'] = 'Versteckte';
$txt['buddy'] = 'Buddy';
$txt['buddies'] = 'Buddies';
$txt['most_online_ever'] = 'Am meisten online (gesamt)';
$txt['most_online_today'] = 'Am meisten online (heute)';

$txt['merge_select_target_board'] = 'W&#228;hlen Sie das Ziel-Board des zusammengef&#252;hrten Themas';
$txt['merge_select_poll'] = 'W&#228;hlen Sie die Umfrage, welche das zusammengf&#252;hrte Thema haben soll';
$txt['merge_topic_list'] = 'W&#228;hlen Sie die Themen, die zusammengef&#252;hrt werden sollen';
$txt['merge_select_subject'] = 'W&#228;hlen Sie den Titel des zusammengef&#252;hrten Themas';
$txt['merge_custom_subject'] = 'Neuer Titel';
$txt['merge_enforce_subject'] = '&#196;ndere Titel aller Beitr&#228;ge';
$txt['merge_include_notifications'] = 'Inklusive Benachrichtigungen?';
$txt['merge_check'] = 'Zusammenf&#252;hren?';
$txt['merge_no_poll'] = 'Keine Umfrage';

$txt['response_prefix'] = 'Re: ';
$txt['current_icon'] = 'Aktuelles Symbol';

$txt['smileys_current'] = 'Ausgew&#228;hltes Smiley-Set';
$txt['smileys_none'] = 'Keine Smileys';
$txt['smileys_forum_board_default'] = 'Forum/Board Standard';

$txt['search_results'] = 'Suchergebnisse';
$txt['search_no_results'] = 'Keine Ergebnisse gefunden';

$txt['totalTimeLogged1'] = 'Insgesamt eingeloggt: ';
$txt['totalTimeLogged2'] = ' Tage, ';
$txt['totalTimeLogged3'] = ' Stunden und ';
$txt['totalTimeLogged4'] = ' Minuten';
$txt['totalTimeLogged5'] = 'T ';
$txt['totalTimeLogged6'] = 'S ';
$txt['totalTimeLogged7'] = 'M';

$txt['approve_thereis'] = 'Es gibt';
$txt['approve_thereare'] = 'Es gibt';
$txt['approve_member'] = 'ein Mitglied, ';
$txt['approve_members'] = 'Mitglieder, ';
$txt['approve_members_waiting'] = 'welche(s) eine Genehmigung erwarten/erwartet.';

$txt['notifyboard_turnon'] = 'M&#246;chten Sie eine Benachrichtigungs E-Mail, wenn jemand ein neues Thema in diesem Board schreibt?';
$txt['notifyboard_turnoff'] = 'M&#246;chten Sie keine Benachrichtigung mehr, wenn jemand ein neues Thema in diesem Board schreibt?';

$txt['activate_code'] = 'Ihr Aktivierungscode ist';

$txt['find_members'] = 'Suche Mitglieder';
$txt['find_username'] = 'Name, Benutzername oder E-Mail Adresse';
$txt['find_buddies'] = 'Nur die Buddies zeigen?';
$txt['find_wildcards'] = 'Wildcards erlauben: *,?';
$txt['find_no_results'] = 'Kein Ergebnis gefunden';
$txt['find_results'] = 'Ergebnis';
$txt['find_close'] = 'Schlie&#223;en';

$txt['unread_since_visit'] = 'Ungelesene Beitr&#228;ge seit Ihrem letzten Besuch.';
$txt['show_unread_replies'] = 'Ungelesene Antworten zu Ihren Beitr&#228;gen.';

$txt['change_color'] = 'Farbe &#228;ndern';

$txt['quickmod_delete_selected'] = 'Ausgew&#228;hlte l&#246;schen';

// In this string, don't use entities. (&amp;, etc.)
$txt['show_personal_messages'] = 'Sie haben eine oder mehrere neue Private Mitteilungen erhalten.\\nMöchten Sie diese lesen?';

$txt['previous_next_back'] = '&laquo; vorheriges';
$txt['previous_next_forward'] = 'n&#228;chstes &raquo;';

$txt['movetopic_auto_board'] = '[BOARD]';
$txt['movetopic_auto_topic'] = '[THEMEN LINK]';
$txt['movetopic_default'] = 'Dieses Thema wurde verschoben nach ' . $txt['movetopic_auto_board'] . ".\n\n" . $txt['movetopic_auto_topic'];

$txt['upshrink_description'] = 'Ein- oder Ausklappen der Kopfzeile';

$txt['mark_unread'] = 'Als ungelesen markieren';

$txt['ssi_not_direct'] = 'Bitte greifen Sie nicht direkt mit der URL auf die SSI.php zu. Benutzen Sie stattdessen den Pfad (%s) oder f&#252;gen Sie \'?ssi_function=irgendwas\' der URL hinzu.';
$txt['ssi_session_broken'] = 'SSI.php konnte die Sitzung nicht laden! Das kann zu Problemen mit dem Ausloggen und anderen Funktionen f&#252;hren - bitte &#252;berpr&#252;fen Sie, ob SSI.php in Ihren Skripts vor jeglichem(!) anderen Code aufgerufen wird!';

// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['preview_title'] = 'Vorheriger Beitrag';
$txt['preview_fetch'] = 'Lade Vorschau...';
$txt['preview_new'] = 'Neue Nachricht';
$txt['error_while_submitting'] = 'Fehler beim Schreiben des Beitrags.';

$txt['split_selected_posts'] = 'Ausgew&#228;hlte Beitr&#228;ge';
$txt['split_selected_posts_desc'] = 'Die unten stehenden Beitr&#228;ge werden zusammen ein neues Thema bilden.';
$txt['split_reset_selection'] = 'Auswahl l&#246;schen';

$txt['modify_cancel'] = 'Abbrechen';
$txt['mark_read_short'] = 'Alle gelesen';

$txt['pm_short'] = 'Meine Mitteilungen';
$txt['hello_member_ndt'] = 'Hallo';

$txt['ajax_in_progress'] = 'Lade...';
