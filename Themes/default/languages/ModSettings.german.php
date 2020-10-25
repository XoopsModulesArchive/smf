<?php
// Version: 1.1; ModSettings

$txt['smf3'] = 'Hier k&#246;nnen Sie die Einstellungen von speziellen Funktionen des Forums &#228;ndern. F&#252;r weitere Optionen schauen Sie bitte in den <a href="'
               . $scripturl
               . '?action=theme;sa=settings;th='
               . $settings['theme_id']
               . ';sesc='
               . $context['session_id']
               . '">Theme-Einstellungen</a> nach. <b>Klicken Sie auf die Hilfe-Symbole f&#252;r weitere Informationen zu den Funktionen.</b>';

$txt['mods_cat_features'] = 'Standardfunktionen';
$txt['pollMode'] = 'Umfrage-Modus';
$txt['smf34'] = 'Umfragen deaktivieren';
$txt['smf32'] = 'Umfragen aktivieren';
$txt['smf33'] = 'Umfragen als Themen anzeigen';
$txt['allow_guestAccess'] = 'G&#228;sten das Anschauen des Forums erlauben';
$txt['userLanguage'] = 'Individuelle Benutzersprache erlauben';
$txt['allow_editDisplayName'] = 'Benutzern erlauben, den angezeigten Namen zu &#228;ndern?';
$txt['allow_hideOnline'] = 'Nicht-Administratoren erlauben, den Online-Status zu verstecken?';
$txt['allow_hideEmail'] = 'Benutzern erlauben, ihre E-Mail Adresse zu verstecken? (au&#223;er vor dem Administrator)';
$txt['guest_hideContacts'] = 'Verstecke Kontaktangaben der Mitglieder vor G&#228;sten';
$txt['titlesEnable'] = 'Pers&#246;nliche Titel aktivieren';
$txt['enable_buddylist'] = 'Buddy-Liste aktivieren';
$txt['default_personalText'] = 'Pers&#246;nlicher Standard-Text';
$txt['max_signatureLength'] = 'Max. Zeichen in Signaturen<div class="smalltext">(0 f&#252;r kein Limit)</div>';
$txt['number_format'] = 'Standard-Zahlenformat';
$txt['time_format'] = 'Voreingestelltes Zeitformat';
$txt['time_offset'] = 'Globale Zeitverschiebung <div class="smalltext">(&#196;nderung deaktiviert die indiv. Zeitverschiebung aller Benutzer)</div>';
$txt['failed_login_threshold'] = 'Fehlerhafte Loginversuche';
$txt['lastActive'] = 'Zeit der Benutzeranzeige unter \'Wer ist online\' (in Minuten)';
$txt['trackStats'] = 'Statistiken aktivieren';
$txt['hitStats'] = 'Seitenaufrufe z&#228;hlen (setzt aktivierte Statistiken voraus)';
$txt['enableCompressedOutput'] = 'Komprimierte Ausgabe aktivieren';
$txt['databaseSession_enable'] = 'Datenbankgest&#252;tzte Sitzungen aktivieren';
$txt['databaseSession_loose'] = 'Browser darf Seiten zwischenspeichern';
$txt['databaseSession_lifetime'] = 'Zeit bevor eine Sitzung abl&#228;uft (in Sekunden)';
$txt['enableErrorLogging'] = 'Error Log aktivieren';
$txt['cookieTime'] = 'G&#252;ltigkeitsdauer des Login-Cookies (in Minuten)';
$txt['localCookies'] = 'Lokales Speichern der Cookies aktivieren<div class="smalltext">(SSI kann Probleme verursachen, wenn diese Option aktiv ist.)</div>';
$txt['globalCookies'] = 'Subdomain unabh&#228;ngige Cookies verwenden?<div class="smalltext">Achtung: Lokale Cookies m&#252;ssen abgeschaltet sein!</div>';
$txt['securityDisable'] = 'Passwortabfrage f&#252;r Administrator deaktivieren';
$txt['send_validation_onChange'] = 'Neues Passwort per E-Mail zusenden, wenn ein Benutzer die E-Mail Adresse &#228;ndert?';
$txt['approveAccountDeletion'] = 'Zustimmung des Administrators ben&#246;tigt, wenn ein Mitglied sein Benutzerkonto l&#246;scht?';
$txt['autoOptDatabase'] = 'Tabellen alle X Tage optimieren?<div class="smalltext">(0 zum deaktivieren)</div>';
$txt['autoOptMaxOnline'] = 'Max. Benutzer f&#252;r Optimierung<div class="smalltext">(0 f&#252;r keine Grenze)</div>';
$txt['autoFixDatabase'] = 'Automatisches Reparieren defekter Tabellen';
$txt['allow_disableAnnounce'] = 'Deaktivieren von Ank&#252;ndigungen erlauben?';
$txt['disallow_sendBody'] = 'Beitragstext in Benachrichtigungen verbieten?';
$txt['modlog_enabled'] = 'Aktionen der Moderatoren &#252;berwachen';
$txt['queryless_urls'] = 'Zeige URLs ohne &quot;?&quot; (testen mit Hilfe-Symbol)<div class="smalltext"><b>Nur Apache-Server!</b></div>';
$txt['max_image_width'] = 'Max. Breite geposteter Bilder (0 = deaktiviert das Limit)';
$txt['max_image_height'] = 'Max. H&#246;he geposteter Bilder (0 = deaktiviert das Limit)';
$txt['mail_type'] = 'Mail Typ';
$txt['mail_type_default'] = '(PHP-Standard)';
$txt['smtp_host'] = 'SMTP Server';
$txt['smtp_port'] = 'SMTP Port';
$txt['smtp_username'] = 'SMTP Username';
$txt['smtp_password'] = 'SMTP Passwort';
$txt['enableReportPM'] = 'Aktiviert das Melden von Privaten Mitteilungen';
$txt['max_pm_recipients'] = 'Max. Anzahl von Empf&#228;ngern in einer Privaten Mitteilung <div class="smalltext">(0 f&#252;r kein Limit, Administratoren sind ausgenommen)</div>';
$txt['pm_posts_verification'] = 'Anzahl der Beitr&#228;ge, unter welcher die Benutzer beim Versenden von Privaten Mitteilungen einen Code eingeben m&#252;ssen.<div class="smalltext">(0 f&#252;r kein Limit, Administratoren sind ausgenommen)</div>';
$txt['pm_posts_per_hour'] = 'Anzahl der Privaten Mitteilungen, welche die Benutzer pro Stunde versenden d&#252;rfen<div class="smalltext">(0 f&#252;r kein Limit, Moderatoren sind ausgenommen)</div>';

$txt['mods_cat_layout'] = 'Layout';
$txt['compactTopicPagesEnable'] = 'Kompakte Seitenanzeige aktivieren';
$txt['smf235'] = 'Anzahl benachbarter Seiten anzeigen:';
$txt['smf236'] = 'zeigt folgendes an';
$txt['todayMod'] = '&quot;Heute/Gestern&quot; Anzeige aktivieren';
$txt['smf290'] = 'Deaktiviert';
$txt['smf291'] = 'Nur Heute';
$txt['smf292'] = 'Heute &amp; Gestern';
$txt['topbottomEnable'] = 'Seitenende/-anfang Buttons aktivieren';
$txt['onlineEnable'] = 'Online/Offline Status in Beitr&#228;gen und Privaten Mitteilungen anzeigen';
$txt['enableVBStyleLogin'] = 'vB Style Login aktivieren';
$txt['defaultMaxMembers'] = 'Anzahl pro Seite in der Mitgliederliste';
$txt['timeLoadPageEnable'] = 'Zeitmessung im Forum aktivieren';
$txt['disableHostnameLookup'] = 'Hostnamen-Suche deaktivieren?';
$txt['who_enabled'] = '\'Wer ist online\' aktivieren?';

$txt['smf293'] = 'Karma';
$txt['karmaMode'] = 'Karma-Modus';
$txt['smf64'] = 'Karma deaktivieren|Karma Total aktivieren|Karma Positiv/Negativ aktivieren';
$txt['karmaMinPosts'] = 'Min. Anzahl an Beitr&#228;gen, um Karma &#228;ndern zu d&#252;rfen';
$txt['karmaWaitTime'] = 'Wartezeit (in Stunden)';
$txt['karmaTimeRestrictAdmins'] = 'Administratoren auf Wartezeit beschr&#228;nken';
$txt['karmaLabel'] = 'Karma: Anzeigenname';
$txt['karmaApplaudLabel'] = 'Karma: positive Stimme';
$txt['karmaSmiteLabel'] = 'Karma: negative Stimme';

$txt['caching_information'] = '<div align="center"><b><u>Wichtig! Lesen Sie diesen Abschnitt, bevor Sie die Funktion aktiviern.</b></u></div><br>
	SMF unterst&#252;tzt das cachen (zwischenspeichern) durch die Benutzung von Beschleuniger-Programmen. Momentan werden folgende Beschleuniger unterst&#252;tzt:<br>
	<ul>
		<li>APC</li>
		<li>eAccelerator</li>
		<li>Turck MMCache</li>
		<li>MemCache</li>
		<li>Zend Platform/Performance Suite (nicht Zend Optimizer)</li>
	</ul>
	Das Zwischenspeichern funktioniert nur auf Ihrem Server, wenn PHP mit einem der genannten Beschleunigern kompiliert wurde oder MemCache vorhanden ist.<br><br>
	SMF benutzt das Zwischenspeichern auf verschiedenen Leveln. Je h&#246;her das Level gew&#228;hlt wird, desto mehr Prozessorleistung wird daf&#252;r ben&#246;tigt. Wenn das Zwischenspeichern auf Ihrem Server aktiviert wurde, versuchen Sie mit Level 1 zu beginnen.
	<br><br>
	Sollten Sie MemCache benutzen, beachten Sie bitte, dass Sie die Server-Einstellungen in das folgende Feld eintragen m&#252;ssen. Es sollten durch Komma getrennte Werte eingegeben werden, wie das folgende Beispiel zeigt:<br>
	&quot;server1,server2,server3:port,server4&quot;<br><br>
	Sollte kein spezieller Port angegebn sein, benutzt SMF den Port 11211. SMF versucht die Auslastung auf allen Servern zu verteilen, wenn das Forum auf mehreren gleichzeitig l&#228;uft.
	<br><br>
	%s
	<hr>';

$txt['detected_no_caching'] = '<b style="color: red;">SMF konnte keinen kompatiblen Beschleuniger auf Ihrem Server finden.</b>';
$txt['detected_APC'] = '<b style="color: green">SMF hat APC auf Ihrem Server gefunden.';
$txt['detected_eAccelerator'] = '<b style="color: green">SMF hat eAccelerator auf Ihrem Server gefunden.';
$txt['detected_MMCache'] = '<b style="color: green">SMF hat MMCache auf Ihrem Server gefunden.';
$txt['detected_Zend'] = '<b style="color: green">SMF hat Zend auf Ihrem Server gefunden.';

$txt['cache_enable'] = 'Cache Level';
$txt['cache_off'] = 'Kein Cache';
$txt['cache_level1'] = 'Level 1 Cache';
$txt['cache_level2'] = 'Level 2 Cache (nicht empfehlenswert)';
$txt['cache_level3'] = 'Level 2 Cache (nicht empfehlenswert)';
$txt['cache_memcached'] = 'MemCache-Einstellungen';
