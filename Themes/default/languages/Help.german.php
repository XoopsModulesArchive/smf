<?php
// Version: 1.1; Help

global $helptxt;

$helptxt = [];

$txt[1006] = 'Fenster schlie&#223;en';

$helptxt['manage_boards'] = '
	<b>Boards verwalten</b><br>
	Hier k&#246;nnen Sie die &quot;Kategorien&quot; und &quot;Boards&quot; verwalten. Kategorien sind die
	oberste Ebene eines Forums. Beispiel: Wenn Sie eine Seite betreiben, die Informationen
	f&#252;r &quot;Sport&quot;, &quot;Autos&quot; und &quot;Musik&quot; bereitstellt, w&#228;ren das die Kategorien,
	die Sie erstellen. Innerhalb dieser Kategorien k&#246;nnen Sie &quot;Boards&quot; einf&#252;gen.<br>
	Hier ein Beispiel:<br>
	<ul>
		<li>
			<b>Sport</b>
			&nbsp;- Eine &quot;Kategorie&quot;
		</li>
		<ul>
			<li>
				<b>Baseball</b>
				&nbsp;- Ein Board innerhalb der Kategorie &quot;Sport&quot;
			</li>
			<ul>
				<li>
					<b>Statistiken</b>
					&nbsp;- Ein untergeordnetes Board des Boards &quot;Baseball&quot;
				</li>
			</ul>
			<li>
				<b>Fu&#223;ball</b>
				&nbsp;- Ein Board innerhalb der Kategorie &quot;Sport&quot;</li>
		</ul>
	</ul>
	Kategorien erlauben Ihnen, das Forum in Themen aufzuteilen (&quot;Autos,
	Sport&quot;) und die &quot;Boards&quot; dienen den Mitgliedern dazu, ihre Beitr&#228;ge
	dort hinein zu schreiben. Wenn sich ein Benutzer f&#252;r Porsche interessiert, w&#252;rde
	er seinen Beitrag in &quot;Autos -> Porsche&quot; schreiben. Kategorien erlauben den
	Mitgliedern, ihre Interessen schnell zu finden: Anstelle von &quot;Fahrzeugen&quot; kann man
	zwischen &quot;Autos&quot; und &quot;Motorr&#228;dern&quot; w&#228;hlen. So wei&#223; der Benutzer, dass er
	nach &quot;Porsche Carrera S4&quot; nicht in der &quot;Motorrad-Kategorie&quot; suchen muss,
	sondern bei den Autos.<br>
	Wie oben erw&#228;hnt, ist ein Board ein differenziertes Thema innerhalb eines groben Bereichs.
	Um &#252;ber den &quot;Porsche Carrera S4&quot; zu diskutieren, gehen Sie einfach in die Kategorie
	&quot;Autos&quot;, w&#228;hlen das Board &quot;Porsche&quot; und schreiben Ihre Meinung hinein.<br>
	Administrative Funktionen in diesem Men&#252; sind das Hinzuf&#252;gen von Boards in jeder
	Kategorie, die &#196;nderung der Reihenfolge sowie das komplette L&#246;schen eines Boards bzw. Kategorie.';

$helptxt['edit_news'] = '<b>News editieren</b><br>
	Hier k&#246;nnen Sie den Text &#228;ndern, welcher in den News auf dem Board-Index angezeigt wird.
	Sie k&#246;nnen alle Informationen hineinschreiben die Sie m&#246;chten (z.B. &quot;Verpassen Sie nicht die
	Besprechung &#252;ber das neue SMF Forum diesen Freitag !&quot;). Jede News-Meldung sollte in eine eigene Textbox
	und wird in einer zuf&#228;lligen Reihenfolge angezeigt.';

$helptxt['view_members'] = '
	<ul>
		<li>
			<b>Mitglieder anzeigen</b><br>
			Hier k&#246;nnen Sie alle Mitglieder sehen, welche in Ihrem Forum registriert sind.
			Sie k&#246;nnen auf den Namen eines Mitglieds klicken, um weitere Informationen &#252;ber ihn/sie abzurufen (Homepage, Alter u.s.w.).
			Als Administrator haben Sie weiterhin die M&#246;glichkeit, diese Angaben zu &#228;ndern bzw. das Benutzerkonto zu l&#246;schen.<br><br>
		</li>
		<li>
			<b>Erwartete Genehmigungen</b><br>
			Dieser Button wird nur angezeigt, wenn Sie die Genehmigung aller neuen Registrierungen durch den Administrator aktiviert haben.
			Jeder Benutzer der sich registriert wird erst Mitglied des Forums sein, wenn der Administrator das Benutzerkonto genehmigt hat.
			Der Bereich listet alle Mitglieder inkl. E-Mail und IP-Adresse auf, die auf ihre Genehmigung warten. Sie k&#246;nnen
			w&#228;hlen, ob Sie das Mitglied genehmigen oder ablehnen m&#246;chten, indem Sie das kleine K&#228;stchen neben dem Mitglied
			w&#228;hlen und in der Drop-Down Box die entsprechende Aktion aussuchen. Sollten Sie ein Mitglied ablehnen,
			k&#246;nnen Sie es wahlweise mit oder ohne Benachrichtigung l&#246;schen.<br><br>
		</li>
		<li>
			<b>Erwartete Aktivierungen</b><br>
			Dieser Button wird nur angezeigt, wenn Sie die Aktivierung der Benutzerkonten eingeschaltet haben. Von hier
			aus k&#246;nnen Sie alle Mitglieder ansehen, die Ihr Benutzerkonto noch nicht aktiviert haben. Sie haben die M&#246;glichkeit,
			die Mitglieder zu genehmigen, abzulehnen oder an die Aktivierung zu erinnern. Wie oben schon erw&#228;hnt, kann das
			L&#246;schen mit oder ohne Benachrichtigung erfolgen.<br><br>
		</li>
	</ul>';

$helptxt['ban_members'] = '<b>Mitglieder bannen</b><br>
	SMF bietet die M&#246;glichkeit, bestimmte Mitglieder zu &quot;bannen&quot;, weil sie aufgrund von Spam u.a. gegen die Nutzungsbedingungen
	versto&#223;en haben. Als Administrator k&#246;nnen Sie in jedem Beitrag die IP-Adresse des Benutzers sehen und diese einfach in die
	Bann-Liste eintragen, so dass der betreffende Benutzer nicht mehr unter dieser IP-Adresse schreiben kann.<br>Sie haben auch die M&#246;glichkeit, die Leute durch die Eingabe ihrer E-Mail-Adresse zu bannen.';

$helptxt['modsettings'] = '<b>Forum-Einstellungen</b><br>
	Hier k&#246;nnen Sie die Einstellungen spezieller Funktionen &#228;ndern, die in SMF integriert sind.';

$helptxt['number_format'] = '<b>Standard-Zahlenformat</b><br>
	Hier k&#246;nnen Sie das Zahlenformat bestimmen, welches im Forum angezeigt wird. Hier ein Beispiel:<br>
	<div style="margin-left: 2ex;">1,234.00</div><br>
	Das \'Komma\' ist das Tausender Trennzeichen, der \'Punkt\' das Dezimal Trennzeichen und abschlie&#223;end die Anzahl der Nullen als Rundungsstellen.';

$helptxt['time_format'] = '<b>Voreingestelltes Zeitformat</b><br>
	Hier k&#246;nnen Sie einzustellen, wie das Datum und die Zeit angezeigt werden sollen. Die Vorgabe folgt den
	PHP-Richtlinien und ist im folgenden beschrieben (mehr Details unter <a href="http://www.php.net/manual/function.strftime.php">php.net</a>).<br>
	<br>
	Die folgenden Zeichen sind bei der Einstellung zu verwenden (Gro&#223;-/Kleinschreibung beachten!): <br><br>
	<span class="smalltext">
	&nbsp;&nbsp;%a - abgek&#252;rzter Name des Wochentags<br>
	&nbsp;&nbsp;%A - voller Name des Wochentags<br>
	&nbsp;&nbsp;%b - abgek&#252;rzter Monatsname<br>
	&nbsp;&nbsp;%B - voller Monatsname<br>
	&nbsp;&nbsp;%d - Tag des Monats (01 bis 31) <br>
	&nbsp;&nbsp;%D<b>*</b> - das gleiche wie %m/%d/%y <br>
	&nbsp;&nbsp;%e<b>*</b> - Tag des Monats (1 bis 31)<br>
	&nbsp;&nbsp;%H - Stunde einer 24-Stunden Uhr (von 00 bis 23) <br>
	&nbsp;&nbsp;%I - Stunde einer 12-Stunden Uhr (von 01 bis 12) <br>
	&nbsp;&nbsp;%m - aktueller Monat als Zahl (01 to 12) <br>
	&nbsp;&nbsp;%M - Minute als Zahl <br>
	&nbsp;&nbsp;%p - &quot;am&quot; oder &quot;pm&quot; zur eingestellten Zeit hinzuf&#252;gen<br>
	&nbsp;&nbsp;%R<b>*</b> - Zeit in 24-Stunden Anzeige <br>
	&nbsp;&nbsp;%S - Sekunde als Dezimalzahl <br>
	&nbsp;&nbsp;%T<b>*</b> - aktuelle Zeit, gleichwertig zu %H:%M:%S <br>
	&nbsp;&nbsp;%y - 2-stelliges Jahr (00 to 99) <br>
	&nbsp;&nbsp;%Y - 4-stelliges Jahr<br>
	&nbsp;&nbsp;%Z - Zeit-Zone, Name oder Abk&#252;rzung der Zeit-Zone <br>
	&nbsp;&nbsp;%% - ein \'%\' Zeichen <br>
	<br>
	<i>* Funktioniert nicht auf Windows basierenden Servern</i></span>';

$helptxt['live_news'] = '<b>Aktuelles von Simple Machines...</b><br>
	Diese Box zeigt aktuelle Meldungen von <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a>.
	Sie sollten hier regelm&#228;&#223;ig wegen Updates, neuen Versionen und wichtigen Informationen vom Simple Machines Team vorbeischauen.';

$helptxt['registrations'] = '<b>Registrierungs-Management</b><br>
	Hier finden Sie alle Funktionen, die Sie zum Verwalten von neuen Mitgliedern ben&#246;tigen. Es gibt drei Bereiche, welche je nach Einstellungen des Forums sichtbar sind:<br><br>
	<ul>
		<li>
			<b>Neues Mitglied registrieren</b><br>
			Hier k&#246;nnen Sie neue Mitglieder registrieren. Diese Funktion ist n&#252;tzlich bei deaktivierter
			Registrierung oder wenn der Administrator ein Testkonto erstellen m&#246;chte. Wenn die Aktivierung
			der Benutzerkonten eingeschaltet ist, erhalten die Mitglieder eine E-Mail mit dem Aktivierungslink,
			welchem sie folgen m&#252;ssen bevor sie sich erstmalig einloggen k&#246;nnen. Alternativ k&#246;nnen Sie dem neuen Mitglied
			auch eine E-Mail mit dem Passwort an die angegebene Adresse schicken.<br><br>
		</li>
		<li>
			<b>Nutzungsbedingungen editieren</b><br>
			Diese Funktion erlaubt das Editieren der Nutzungsbedingungen, welcher bei einer Neuregistrierung angezeigt werden.
			Sie k&#246;nnen zu dem Originaltext neue Bedingungen hinzuf&#252;gen oder l&#246;schen.
		</li>
		<li>
			<b>Reservierte Namen</b><br>
			Diese Funktion erlaubt das Hinzuf&#252;gen von W&#246;rtern oder Namen, die nicht von anderen Benutzern verwendet werden d&#252;rfen.<br><br>
		</li>
		<li>
			<b>Einstellungen</b><br>
			Dieser Bereich wird nur sichtbar sein, wenn Sie die Berechtigung zum administrieren des Forums haben. Hier k&#246;nnen Sie unter anderem Einstellungen zur Registrierungsmethode un der Altersbegrenzung machen.
		</li>
	</ul>';

$helptxt['modlog'] = '<b>Moderatoren-Protokoll</b><br>
	Hier k&#246;nnen die Administratoren alle Aktionen der Moderatoren verfolgen. Damit Moderatoren keine Hinweise zu ihren
	Aktionen l&#246;schen k&#246;nnen, ist es erst 24 Stunden danach m&#246;glich, diese Eintr&#228;ge zu entfernen. Die \'Objekte\'
	Spalte listet alle Details zu der betreffenden Aktion auf.';
$helptxt['error_log'] = '<b>Fehlerprotokoll anzeigen</b><br>
	Das Fehlerprotokoll listet alle Fehler, die von Benutzern im Forum produziert worden sind, nach Datum sortiert auf. Um den neuesten Fehler
	zuerst anzuzeigen, klicken Sie auf den kleinen schwarzen Pfeil neben dem Datum. Weiterhin k&#246;nnen Sie die Fehlermeldungen
	nach der Art des Fehlers filtern, indem Sie auf die Grafik neben der entsprechenden Angabe klicken (z.B. filtern nach Mitglied).
	Wenn ein Filter aktiv ist, werden nur die &#252;bereinstimmenden Fehler angezeigt.';
$helptxt['theme_settings'] = '<b>Theme-Einstellungen</b><br>
	Hier k&#246;nnen Sie die Einstellungen jedes einzelnen Themes ver&#228;ndern. Die Einstellungen betreffen u.a. das Theme-Verzeichnis
	und die URL Informationen, sowie viele Einstellungen zum Layout. Die meisten Themes enthalten eine Vielzahl von konfigurierbaren Optionen,
	welche es erlauben, das Theme den pers&#246;nlichen W&#252;nschen anzupassen.';
$helptxt['smileys'] = '<b>Smileys &amp; Beitragssymbole</b><br>
	Hier k&#246;nnen Sie Smileys oder Smiley-Sets hinzuf&#252;gen oder l&#246;schen. Beachten Sie, dass jeder Smiley aus einem Set auch in den anderen sichtbar ist, da es sonst bei Verwendung verschiedener Sets zu Verwirrungen kommen k&#246;nnte.<br><br>
	Sie k&#246;nnen hier ebenfalls die Beitragssymbole ver&#228;ndern, wenn Sie diese in den Einstellungen aktiviert haben.';
$helptxt['calendar'] = '<b>Kalender verwalten</b><br>
	Hier k&#246;nnen Sie die Einstellungen des Kalenders ver&#228;ndern sowie Feiertage hinzuf&#252;gen bzw. l&#246;schen.';

$helptxt['serversettings'] = '<b>Server-Einstellungen</b><br>
	Hier k&#246;nnen Sie die Zentral- bzw. Zusatzkonfiguration Ihres Forums einstellen. Dieser Bereich enth&#228;lt Einstellungen zur Datenbank,
	die Verzeichnispfade sowie andere wichtige Einstellungen zu E-Mail und dem Cache. Bitte handeln Sie hier mit erh&#246;hter Vorsicht,
	da Ihr Forum unter Umst&#228;nden ncht mehr funktionieren kann.';

$helptxt['topicSummaryPosts'] = 'Anzahl der Beitr&#228;ge, die beim Antwort-Bildschirm in der Zusammenfassung angezeigt werden.';
$helptxt['enableAllMessages'] = 'Stellen Sie hier die <em>maximale</em> Anzahl an Beitr&#228;gen ein, bei denen der "Alle"-Beitr&#228;ge Link angezeigt werden soll. Stellen Sie diesen Wert auf weniger als die &quot;Anzahl der Themen pro Seite im Themen-Index&quot; ein, wird dieser Link nie angezeigt. Wird der Wert zu hoch angesetzt, kann das Forum sehr verlangsamt werden.';
$helptxt['enableStickyTopics'] = 'Fixierte Themen sind Themen, welche an erster Stelle im Themen-Index angezeigt werden. Sie werden meistens f&#252;r wichtige
  Nachrichten verwendet. Nur Administratoren und Moderatoren k&#246;nnen Themen fixieren.';
$helptxt['allow_guestAccess'] = 'Sollten Sie diese Option deaktivieren, k&#246;nnen G&#228;ste des Forums nur noch grundlegende Funktionen benutzen (Einloggen, Registrieren, Passwort Erinnerung etc.). Die Option hat nicht die gleichen Auswirkungen wie G&#228;sten den Zugriff auf die Boards zu verbieten.';
$helptxt['userLanguage'] = 'Erm&#246;glicht dem Benutzer die Auswahl einer individuellen Sprache im Forum. Betrifft nicht die Standardeinstellung.';
$helptxt['trackStats'] = 'Statistiken aktivieren:<br>Erlaubt den Benutzern verschiedene Statistiken zu sehen, z.B. die neuesten Eintr&#228;ge, die meistbesuchten Themen, die neuesten Themen und viele andere.<hr>
		Seitenaufrufe Aufrufe:<br>F&#252;gt den Statistiken eine weitere Spalte mit den Seitenaufrufen des Forum hinzu.';
$helptxt['titlesEnable'] = 'Erlaubt den Mitgliedern sich selbst einen frei definierbaren &quot;Titel&quot; zu geben, welcher unter dem Namen angezeigt
		wird.<br><i>Beispiel:</i><br>Daniel D.<br>SMF-&#220;bersetzer';
$helptxt['topbottomEnable'] = 'F&#252;gt einen &quot;nach unten&quot; bzw. &quot;nach oben&quot; Button hinzu, welcher ein schnelles navigieren zwischen Seitenanfang und Seitenende erm&#246;glicht.';
$helptxt['onlineEnable'] = 'Ein Bild zeigt den Online-Status des Benutzers an (Online/Offline).';
$helptxt['todayMod'] = 'Zeigt &quot;Heute&quot; oder &quot;Gestern&quot; statt des Datums an.';
$helptxt['enablePreviousNext'] = 'Zeigt einen Link zum n&#228;chsten bzw. vorherigen Thema an.';
$helptxt['pollMode'] = 'Aktiviert oder deaktiviert die Umfragen. Sollten Sie die Umfragen deaktivieren, werden alle existierenden
    Umfragen auf dem Themen-Index versteckt. Sollen die Themen weiterhin angezeigt werden, dann w&#228;hlen Sie &quot;Umfragen als
    Themen anzeigen&quot;.<br><br>Um zu bestimmen, wer Umfragen starten/l&#246;schen bzw. in ihnen abstimmen kann, stellen Sie die
    entsprechenden Berechtigungen ein. Beachten Sie dies bitte , wenn die Umfragen nicht funktionieren sollten.';
$helptxt['enableVBStyleLogin'] = 'Zeigt ein kleines Einloggen-Feld am unteren Ende der Seite an.';
$helptxt['enableCompressedOutput'] = 'Aktiviert die komprimierte Daten&#252;bertragung, um Bandbreite zu sparen. Erfordert ein installiertes \'zlib\' auf dem Server.';
$helptxt['databaseSession_enable'] = 'Verwendet die Datenbank zum Speichern von Sitzungen und ist das Beste f&#252;r eine ausgeglichene Belastung des Servers. Die Option hilft bei den sogenannten \'Timeouts\' und macht das Forum unter Umst&#228;nden schneller.';
$helptxt['databaseSession_loose'] = 'Reduziert die Bandbreite, welche Ihr Forum ben&#246;tigt, jedoch wird bei einem Klick auf den Zur&#252;ck-Button im Browser die vorherige Seite nicht neu geladen, die \'Neue Beitr&#228;ge\' Symbole und andere Werte werden daher nicht aktualisiert.';
$helptxt['databaseSession_lifetime'] = 'Anzahl der Sekunden f&#252;r die L&#228;nge einer Datenbanksitzung. Sollte eine Sitzung eine Zeit lang nicht gebraucht werden, wird sie als &quot;verloren gegangen&quot; bezeichnet. Empfohlen wird mindestens der Wert 2400.';
$helptxt['enableErrorLogging'] = 'Erfasst alle Fehlermeldungen im Forum (z.B. fehlerhaftes Einloggen etc.).';
$helptxt['allow_disableAnnounce'] = 'Diese Option erlaubt den Benutzern, die Benachrichtigung bei neuen Ank&#252;ndigungen zu deaktivieren.';
$helptxt['disallow_sendBody'] = 'Verhindert das Mitschicken des Textes aus Antworten oder neuen Beitr&#228;gen in den Benachrichtigungs E-Mails.<br>Oftmals antworten Mitglieder auf diese E-Mails, so dass der Webmaster eine Flut an E-Mails erh&#228;lt.';
$helptxt['compactTopicPagesEnable'] = 'Zeigt nur eine bestimmte Anzahl der Seitennummern an.<br><i>Beispiel:</i>
		&quot;3&quot; f&#252;r: 1 ... 4 [5] 6 ... 9 <br>
		&quot;5&quot; f&#252;r: 1 ... 3 4 [5] 6 7 ... 9';
$helptxt['timeLoadPageEnable'] = 'Zeigt unten auf jeder Seite die Zeit in Sekunden an, die SMF f&#252;r das Erstellen gebraucht hat.';
$helptxt['removeNestedQuotes'] = 'Zeigt nur das Zitat des betreffenden Beitrags an und keine weiteren.';
$helptxt['simpleSearch'] = 'Zeigt eine vereinfachte Suchemaske an.';
$helptxt['max_image_width'] = 'Erlaubt die Angabe einer maximalen Bildergr&#246;&#223;e. Bilder die kleiner sind, werden dadurch nicht beeintr&#228;chtigt.';
$helptxt['mail_type'] = 'Diese Option erlaubt das W&#228;hlen zwischen den Standardeinstellungen von PHP oder den SMTP-Einstellungen. PHP unterst&#252;tzt keine Authentifizierung mit SMTP (wie viele Hosts es heutzutage erfordern), so dass Sie nur SMTP aktivieren m&#252;ssen, wenn Sie es benutzen m&#246;chten. SMTP kann in der Nutzung langsamer sein und manche Server nehmen keine Benutzernamen und Kennw&#246;rter an.<br><br>Sie m&#252;ssen keine SMTP Daten eingeben, wenn Sie den PHP-Standard benutzen.';
$helptxt['attachmentEnable'] = 'Dateianh&#228;nge sind Dateien, welche die Mitglieder hochladen und einem Beitrag anh&#228;ngen k&#246;nnen.<br><br>
		<b>Dateitypen pr&#252;fen</b>:<br> Pr&#252;ft die Dateiendung vor dem Anh&#228;ngen an den Beitrag.<br>
		<b>Erlaubte Dateitypen</b>:<br> Geben Sie hier die erlaubten Dateiendungen an.<br>
		<b>Upload-Pfad f&#252;r Dateianh&#228;nge</b>:<br> Der Pfad zu Ihrem Upload-Verzeichnis.<br>(Beispiel: /home/sites/yoursite/www/forum/attachments)<br>
		<b>Max. Gr&#246;&#223;e des Upload-Verzeichnis</b> (in KB):<br> W&#228;hlen Sie die max. Gr&#246;&#223;e des Upload-Verzeichnis auf dem Server.<br>
		<b>Max. Gr&#246;&#223;e der Dateianh&#228;nge pro Beitrag</b> (in KB):<br> Geben Sie die max. Dateigr&#246;&#223;e an, die alle Dateianh&#228;nge in einem Beitrag haben d&#252;rfen. Sollte dieser Wert kleiner sein als &quot;Max. Gr&#246;&#223;e pro Dateianhang&quot;, wird dieser Wert die Gr&#246;&#223;e bestimmen.<br>
		<b>Max. Gr&#246;&#223;e pro Dateianhang</b> (in KB):<br> Gibt die max. Dateigr&#246;&#223;e pro Dateianhang an.<br>
		<b>Max. Anzahl der Dateianh&#228;nge pro Beitrag</b>:<br> Gibt die max. Anzahl der Dateianh&#228;nge pro Beitrag an, die ein Mitglied anh&#228;ngen darf.<br>
		<b>Dateianhang im Beitrag als Bild anzeigen</b>:<br> Wenn die hochgeladene Datei ein Bild ist, wird es unterhalb des Beitrags angezeigt.<br>
		<b>Bilder in der Gr&#246;&#223;e &#228;ndern, wenn sie unter dem Beitrag angezeigt werden</b>:<br> Wenn die vorstehende Option aktiviert ist, wird ein seperates, kleineres Bild f&#252;r die Vorschau abgespeichert, was die Datenmenge reduziert.<br>
		<b>Max. Breite und H&#246;he der Vorschau</b>:<br> Diese Option wird nur in Verbindung mit &quot;Bilder in der Gr&#246;&#223;e &#228;ndern, wenn sie unter dem Beitrag angezeigt werden&quot; benutzt. Es gibt die max. Werte an, welche die Vorschau haben kann. Die Vorschau wird zudem proportional ver&#228;ndert.';
$helptxt['karmaMode'] = 'Karma zeigt die Beliebtheit eines Mitglieds im Forum an. Sie k&#246;nnen die Anzahl der Beitr&#228;ge festlegen, ab der Karma genutzt werden darf,
		die Zeit zwischen zwei Abstimmungen und ob der Administrator auch von diesem Zeitlimit erfasst werden soll.';
// Old information!
$helptxt['cal_enabled'] = 'Der Kalender kann genutzt werden, um Geburtstage oder andere Ereignisse anzuzeigen.<hr>
		<b>Tag als Link zu neuem Ereignis anzeigen:</b><br>Erlaubt es, ein neues Ereignis zu erstellen wenn der Benutzer auf die Tageszahl klickt.<br>
		<b>Die Woche mit Montag beginnen:</b><br>Montag als Wochenanfang anzeigen.<br>
		<b>Wochennummer zeigen:</b><br>Zeigt die x. Woche an.<br>
		<b>Feiertage im Index anzeigen</b><br>
		<b>Geburtstage im Index anzeigen</b><br>
		<b>Ereignisse im Index anzeigen</b><br>
		<b>Min. Jahr:</b><br>Bestimmt das &quot;erste&quot; Jahr im Kalender.<br>
		<b>Max. Jahr:</b><br>Bestimmt das &quot;letzte&quot; Jahr im Kalender.<br>
		<b>Titel-Farbe:</b><br>Bestimmt die Farbe des aktuellen Monats.<br>
		<b>Farbe des aktuellen Tages:</b><br>Bestimmt die Farbe des aktuellen Tages.<br>
		<b>Geburtstagsfarbe:</b><br>Bestimmt die Farbe des Textes &quot;Geburtstag&quot;<br>
		<b>Ereignisfarbe:</b><br>Bestimmt die Farbe des Textes &quot;Ereignis&quot;<br>
		<b>Urlaubsfarbe:</b><br>Bestimmt die Farbe des Wortes &quot;Urlaub&quot;.<br>
		<b>Ereignisse d&#252;rfen &#252;ber mehrere Tage gehen:</b><br>Aktivieren Sie diese Option, so das Ereignisse &#252;ber mehrere Tage gehen k&#246;nnen<br>
		<b>Max. Ereignisdauer:</b><br>Bestimmt die Anzahl der Tage, die ein Ereignis maximal dauern kann<br>
		<b>Alle Mitglieder k&#246;nnen schreiben:</b><br>Bestimmt, ob alle Mitglieder Ereignisse schreiben d&#252;rfen<br>
		<b>Mitgliedergruppen die schreiben d&#252;rfen:</b><br>Bestimmt die Mitgliedergruppen, die Ereignisse schreiben d&#252;rfen<br>
		<b>Mitglieder die Ereignisse anlegen d&#252;rfen:</b><br>Bestimmt die Mitglieder, die Ereignisse anlegen d&#252;rfen<br>
		<b>Board in welches geschrieben werden soll:</b><br>W&#228;hlen Sie das Board, in welches die Ereignisse geschrieben werden sollen';
$helptxt['localCookies'] = 'SMF benutzt Cookies, um die Login-Informationen auf dem Computer zu speichern.
	Cookies k&#246;nnen global (meineseite.de) oder lokal (meineseite.de/pfad/zum/forum) gespeichert werden.<br>
	Aktivieren Sie diese Option, wenn Sie automatisch ausgeloggt werden.<hr>
	Global gespeicherte Cookies sind weniger sicher, wenn sie auf auf einem Shared-Server benutzt werden (z.B. Tripod).<hr>
	Lokal gespeicherte Cookies funktionieren nicht au&#223;erhalb des Forum-Verzeichnis. Wenn das Forum unter www.meineseite.de/forum liegt, k&#246;nnen Dateien wie www.meineseite.de/index.php nicht auf die Cookie Informationen zugreifen.
	Wenn Sie die Datei SSI.php benutzen, werden globale Cookies zwingend (!) ben&#246;tigt.';
$helptxt['enableBBC'] = 'Erlaubt den Mitgliedern die Benutzung von Bulletin Board Code (BBC) im Forum, welcher den Text formatiert, Bilder einf&#252;gen kann und vieles mehr.';
$helptxt['time_offset'] = 'Nicht immer ist die Serverzeit gleich der Zeit, die vom Forum genutzt werden soll. Hier k&#246;nnen Sie die Zeitdifferenz in Stunden eintragen (positive/negative Zahl), welche den Unterschied zwischen dem Server und der Zeit im Forum machen.';
$helptxt['spamWaitTime'] = 'Tragen Sie hier ein Zeitintervall ein, das ein Benutzer zwischen zwei Beitr&#228;gen einhalten muss. Dies kann zum Verhindern des sogenannten &quot;Spammens&quot; beitragen.';
$helptxt['enablePostHTML'] = 'Erlaubt das Benutzen von h&#228;ufigen HTML Befehlen:
	<ul style="margin-bottom: 0;">
		<li>&lt;b&gt;, &lt;u&gt;, &lt;i&gt;, &lt;s&gt;, &lt;em&gt;, &lt;ins&gt;, &lt;del&gt;</li>
		<li>&lt;a href=&quot;&quot;&gt;</li>
		<li>&lt;img src=&quot;&quot; alt=&quot;&quot; /&gt;</li>
		<li>&lt;br /&gt;, &lt;hr /&gt;</li>
		<li>&lt;pre&gt;, &lt;blockquote&gt;</li>
	</ul>';

$helptxt['themes'] = 'Hier k&#246;nnen Sie das Standard- bzw. G&#228;ste-Theme w&#228;hlen, Verzeichnisse f&#252;r die Themes &#228;ndern, Themes editieren sowie individuelle Theme-Optionen einstellen.';
$helptxt['theme_install'] = 'Hier k&#246;nnen Sie neue Themes installieren, indem Sie ein schon vorhandenes Verzeichnis benutzen, ein Zip-Paket hochladen oder das vorhandene Theme kopierent.<br><br>Beachten Sie, dass das Verzeichnis bzw. das Zip-Paket die Datei <tt>theme_info.xml</tt> enthalten muss.';
$helptxt['enableEmbeddedFlash'] = 'Diese Option erlaubt den Benutzern, Flash in ihren Beitr&#228;gen zu nutzen (wie Bilder). Das kann ein Sicherheitsrisiko darstellen! BENUTZUNG ERFOLGT AUF EIGENE GEFAHR!';
$helptxt['xmlnews_enable'] = 'Diese Option erlaubt den Benutzern zu den <a href="'
                                         . $scripturl
                                         . '?action=.xml;sa=news">Letzten Neuigkeiten</a> zu verlinken. Es wird empfohlen, die Gr&#246;&#223;e der Neuesten Beitr&#228;ge/News zu begrenzen, da es zu falschen Darstellungen in manchen Programmen wie Trillian kommen kann.';
$helptxt['hotTopicPosts'] = '&#196;ndert die Zahl der Beitr&#228;ge, nach denen ein Thema den Status &quot;hei&#223;&quot; oder &quot;sehr hei&#223;&quot; erh&#228;lt.';
$helptxt['globalCookies'] = 'Erm&#246;glicht die Nutzung von Subdomains unabh&#228;ngigen Cookies. Ein Beispiel:<br>
	Ihre Seite hat die Domain http://www.simplemachines.org,<br>
	Ihr Forum hat die Domain http://forum.simplemachines.org,<br>
	Diese Einstellung erm&#246;glicht es, auf die Forum-Cookies Ihrer Seite zuzugreifen. <b>Aktivieren Sie diese Einstellung nicht, wenn es noch andere Subdomains gibt, die nicht unter Ihrer Kontrolle sind!!</b>';
$helptxt['securityDisable'] = '<i>Deaktiviert</i> die erneute Passwort&#252;berpr&#252;fung f&#252;r den Administratorbereich. NICHT EMPFEHLENSWERT!';
$helptxt['securityDisable_why'] = 'Das ist Ihr aktuelles Passwort (dasselbe, was Sie f&#252;r das Einloggen benutzen).<br><br>Warum Sie es eingeben sollten ? Damit Sie sich im Klaren dar&#252;ber sind, dass <b>Sie</b> die &#196;nderungen im Administrator-Center machen und daf&#252;r verantwortlich sind.';
$helptxt['emailmembers'] = 'In dieser Nachricht k&#246;nnen Sie folgende Variablen benutzen:<br>
	{$board_url} - URL zu Ihrem Forum.<br>
	{$current_time} - Die aktuelle Zeit.<br>
	{$member.email} - Die aktuelle E-Mail Adresse des Mitgliedes.<br>
	{$member.link} - Den aktuellen Link zum betreffenden Mitglied.<br>
	{$member.id} - Die aktuelle Benutzer ID.<br>
	{$member.name} - Den aktuellen Mitgliedsnamen (f&#252;r Private Mitteilungen).<br>
	{$latest_member.link} - Den Link zum neuesten Mitglied.<br>
	{$latest_member.id} - Die ID des neuesten Mitglieds.<br>
	{$latest_member.name} - Der Name des neuesten Mitglieds.';
$helptxt['attachmentEncryptFilenames'] = 'Verschl&#252;sselte Dateinamen erlauben die Nutzung eines gleichen Dateinamens, sicheres Hochladen einer .php Datei und erh&#246;ht die allgemeine Sicherheit. Andererseits macht es das Wiederherstellen der Datenbank nach einem gro&#223;en Crash schwieriger.';

$helptxt['failed_login_threshold'] = 'Geben Sie die Anzahl der erfolglosen Loginversuche an, bevor der Benutzer zum Passwort Erinnerungs-Bildschirm weitergeleitet wird.';
$helptxt['oldTopicDays'] = 'Ist diese Option aktiviert, wird dem Benutzer eine Warnung ausgegeben, wenn er in einem Thema schreiben m&#246;chte, in welchem seit der eigestellten Zeit (in Tagen) keiner mehr geantwortet hat. Setzen Sie den Wert auf 0, um die Funktion zu deaktivieren.';
$helptxt['edit_wait_time'] = 'Anzahl in Sekunden, bevor das Datum des letzten Editierens gespeichert wird.';
$helptxt['edit_disable_time'] = 'Anzahl in Minuten, nach denen ein Benutzer seine eigenen Beitr&#228;ge noch editieren darf. Setzen Sie den Wert auf 0, um die Funktion zu deaktivieren.<br><br><i>Beachten Sie: Diese Option hat keinen Effekt auf die Benutzer, welche Beitr&#228;ge anderer Mitglieder editieren d&#252;rfen.</i>';
$helptxt['enableSpellChecking'] = 'Aktiviert die Rechtschreibepr&#252;fung. Sie M&#220;SSEN die pspell Bibliothek auf dem Server installiert haben und PHP muss so konfiguriert sein, dass es selbige auch benutzt. Ihr Server '
                                     . (function_exists('pspell_new') ? 'HAT' : 'HAT NICHT')
                                     . ' diese Funktion.';
$helptxt['lastActive'] = 'Gibt die Anzahl in Minuten an, in welcher die Besucher auf dem Board-Index als aktiv gekennzeichnet werden. Standard sind 15 Minuten.';

$helptxt['autoOptDatabase'] = 'Diese Funktion optimiert die Datenbank alle angegebenen Tage. Geben Sie 1 ein, um die Datenbank t&#228;glich zu optimieren. Sie k&#246;nnen ebenfalls eine max. Zahl von Benutzern angeben die online sein d&#252;rfen, damit es keine Probleme mit der Geschwindigkeit gibt.';
$helptxt['autoFixDatabase'] = 'Diese Funktion repariert automatisch auftretende Fehler, wobei die Benutzer nichts davon merken werden. Das kann sinnvoll sein, andererseits ist das Forum solange funktionsunt&#252;chtig, bis Sie es selbst merken werden. Ihnen wird in dem Fall eine E-Mail zugesendet.';

$helptxt['enableParticipation'] = 'Zeigt ein ver&#228;ndertes Symbol vor den Themen, in denen man selbst geantwortet hat.';

$helptxt['db_persist'] = 'Erh&#246;ht die Geschwindigkeit zur Datenbank, indem eine Verbindung dauerhaft aufrecht erhalten wird. Wenn Sie einen dedizierten Server benutzen, k&#246;nnte es Probleme mit Ihrem Host geben.';

$helptxt['queryless_urls'] = 'Ver&#228;ndert das Format der URL\'s, so dass Suchmaschinen sie besser aufnehmen (z.B. index.php/topic,1.html).<br><br>Diese Option funktioniert ' . (false !== mb_strpos(php_sapi_name(), 'apache') ? '' : 'nicht') . ' mit Ihrem Server.';
$helptxt['countChildPosts'] = 'Aktivieren Sie diese Option, um die Beitr&#228;ge und Themen der untergeordneten Boards zum Total-Wert auf dem Board-Index zu addieren.<br><br>Das macht die Anzeige merklich langsamer, zeigt aber keine Boards mit \'0\' an, wenn diese untergeordnete Boards mit Beitr&#228;gen enthalten.';
$helptxt['fixLongWords'] = 'Diese Option verk&#252;rzt W&#246;rter einer bestimmten L&#228;nge (Autolenkrad = Autol...), so dass diese nicht das Layout des Forums zerst&#246;ren.';

$helptxt['who_enabled'] = 'Aktiviert die \'Wer ist online\' Funktion, welche dem Benutzer anzeigt, wer online ist und was er gerade macht.';

$helptxt['recycle_enable'] = '&quot;Wiederherstellung&quot; von gel&#246;schten Themen und Beitr&#228;gen in die entsprechenden Boards.';

$helptxt['enableReportPM'] = 'Diese Option erlaubt den Benutzern das Melden von Privaten Mitteilungen an die Administratoren. Sie soll helfen, Mi&#223;br&#228;uche des Privaten Mitteilungen Systems zu verfolgen bzw. zu verhindern.';
$helptxt['max_pm_recipients'] = 'Diese Option erlaubt die Vergabe einer max. Anzahl von Empf&#228;ngern, die ein Benutzer beim Schreiben einer Privaten Mitteilung eingeben kann. Dies soll helfen, dass sogenannte Spammen im Forum zu vermeiden. Benutzer mit der Berechtigung zum Senden von Newslettern sind von dieser Beschr&#228;nkung ausgeschlossen. Geben Sie 0 f&#252;r kein Limit ein.';
$helptxt['pm_posts_verification'] = 'Diese Option zwingt den Benutzer einen Code einzugeben, wenn er eine Private Mitteilung schreibt. Dies gilt nur f&#252;r Benutzer, welche weniger Beitr&#228;ge wie die hier angegebene Anzahl haben. Die Einstellung soll automatische Spam-Skripte an ihrer Funktion hindern.';
$helptxt['pm_posts_per_hour'] = 'Diese Option gibt die Anzahl der Privaten Mitteilungen vor, welche der Benutzer in einer Stunde versenden darf.Diese Einstellung gilt nicht f&#252;r Administratoren oder Moderatoren.';

$helptxt['default_personalText'] = 'Gibt den Text an, der als standardm&#228;&#223;iger &quot;Pers&#246;nlicher Text&quot; angezeigt wird.';

$helptxt['modlog_enabled'] = '&#220;berwacht alle Aktionen der Moderatoren.';

$helptxt['guest_hideContacts'] = 'Diese Option versteckt die E-Mail Adresse und die Messenger Angaben aller Mitglieder vor G&#228;sten.';

$helptxt['registration_method'] = 'Diese Option stellt verschiedene M&#246;glichkeiten der Registrierung zur Verf&#252;gung. Sie k&#246;nnen aus folgenden w&#228;hlen:<br><br>
	<ul>
		<li>
			<b>Sofortige Registrierung</b><br>
				Neue Mitglieder k&#246;nnen sich sofort einloggen und Beitr&#228;ge schreiben, nachdem Sie sich registriert haben.<br>
		</li><li>
			<b>Neue Mitglieder aktivieren</b><br>
				Nach der Registrierung erhalten neue Mitglieder eine E-Mail mit einem Aktivierungslink, welchen Sie anklicken m&#252;ssen, bevor sie das Forum nutzen k&#246;nnen.<br>
		</li><li>
			<b>Neue Mitglieder genehmigen</b><br>
				Alle neuen Mitglieder m&#252;ssen zuerst vom Administrator genehmigt werden, bevor sie das Forum nutzen k&#246;nnen.
		</li><li>
				<b>Registrierung deaktiviert:</b><br>
				Deaktiviert die Registrierung, so dass sich keine neuen Mitglieder im Forum registrieren k&#246;nnen.<br>
		</li>
	</ul>';
$helptxt['send_validation_onChange'] = 'Alle Mitglieder m&#252;ssen bei einer &#196;nderung der E-Mail Adresse diese best&#228;tigen, bevor sie ihr Benutzerkonto wieder nutzen k&#246;nnen.';
$helptxt['send_welcomeEmail'] = 'Allen Mitgliedern wird eine Willkommens E-Mail geschickt, wenn sie sich im Forum anmelden.';
$helptxt['password_strength'] = 'Diese Einstellung bestimmt die &quot;Qualit&#228;t&quot; des Passwortes aller Benutzer. Je komplexer das Passwort, desto schwieriger ist es zu erraten.
	Die verf&#252;gbaren Optionen sind:
	<ul>
		<li><b>Niedrig:</b> Das Passwort muss aus mindestens vier Zeichen bestehen.</li>
		<li><b>Mittel:</b> Das Passwort muss mindestens acht Zeichen haben und darf nicht aus Teilen des Benutzernamens oder der E-Mail Adresse bestehen.</li>
		<li><b>Hoch:</b> Hier gilt das gleiche wie bei Mittel, das Passwort muss aber weiterhin einen Mix aus Gro&#223;- und Kleinbuchstaben enthalten sowie mindestens eine Zahl.</li>
	</ul>';

$helptxt['coppaAge'] = 'Dieser Wert gibt das Mindestalter an, welches ein neues Mitglied haben muss, damit es das Forum benutzen darf.
	W&#228;hrend der Registrierung m&#252;ssen sie angeben, ob sie &#228;lter oder j&#252;nger als das Mindestalter sind. Entweder wird ihre Registrierung abgelehnt oder suspendiert (je nach Einstellung), bis die Genehmigung der Eltern vorhanden ist.
	Sollten Sie einen Wert von 0 eingeben, werden alle Funktionen bez&#252;glich der Alterbeschr&#228;nkung deaktiviert.';
$helptxt['coppaType'] = 'Sollte die Altersbeschr&#228;nkung aktiviert sein, bestimmt diese Option, was mit den Registrierungen der Benutzer passiert, die j&#252;nger als das angegebene Alter sind. Es gibt die folgenden zwei M&#246;glichkeiten:
	<ul>
		<li>
			<b>Registrierung anlehnen:</b><br>
				Die Registrierung neuer Mitglieder unterhalb des Mindestalters wird sofort abgelehnt.<br>
		</li><li>
			<b>Erfordert Genehmigung von Eltern/Erziehungsberechtigten</b><br>
				Die Benutzerkonten neuer Mitglieder unterhalb des Mindestalters werden auf &quot;Erwarte Genehmigung&quot; gesetzt und es wird ein Formular angezeigt, mit welchem die Eltern die Genehmigung f&#252;r die Mitgliedschaft im Forum erteilen m&#252;ssen.
				Ihnen werden ebenso die Kontaktm&#246;glichkeiten angezeigt, mit denen das Formular an den Administrator geschickt werden kann (per Post oder per Fax).
		</li>
	</ul>';
$helptxt['coppaPost'] = 'Die Textfelder mit den Kontaktangaben sind erforderlich, damit das Formular an den Administrator geschickt werden kann. Die Angaben werden allen neuen Mitgliedern angezeigt, die das Mindestalter noch nicht erreicht haben und sind f&#252;r die Genehmigung durch Eltern/Erziehungsberechtigte unbedingt notwendig. Es sollte mindestens eine Postadresse oder eine Faxnummer angeboten werden.';

$helptxt['allow_hideOnline'] = 'Aktivieren Sie diese Option, k&#246;nnen alle Mitglieder ihren Online-Status verstecken (au&#223;er vor Administratoren). Wenn Sie diese Option deaktivieren, k&#246;nnen nur Mitglieder den Status verstecken, welche die M&#246;glichkeit haben das Forum zu administrieren. Das Deaktivieren &#228;ndert keinen Status eines Mitglieds, es verhindert nur das Verstecken des Status in der Zukunft.';
$helptxt['allow_hideEmail'] = 'Wenn diese Option aktiviert ist, k&#246;nnen Mitglieder w&#228;hlen, ob sie ihre E-Mail Adresse vor andern Benutzern verstecken d&#252;rfen. Der Administrator hingegen kann alle E-Mail Adressen betrachten.';

$helptxt['latest_support'] = 'Dieser Bereich zeigt die h&#228;ufigsten Probleme und Fragen zur Ihrer Server-Konfiguration. Diese Informationen werden nicht gespeichert.<br><br>Sollte es bei &quot;Lade Support Informationen...&quot; stehen bleiben, kann Ihr Computer wahrscheinlich <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a> nicht kontaktieren.';
$helptxt['latest_packages'] = 'Hier k&#246;nnen Sie ein paar der beliebtesten und zuf&#228;llig ausgew&#228;hlten Modifikationen bzw. Pakete sehen, welche leicht und schnell zu installieren sind.<br><br>Wenn dieser Bereich nicht sichtbar ist, kann Ihr Computer wahrscheinlich <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a> nicht kontaktieren.';
$helptxt['latest_themes'] = 'Dieser Bereich zeigt die neuesten und beliebtesten Themes von<a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a> an. Sollte er nicht sichtbar sein, kann Ihr Copmuter wahrscheinlich <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a> nicht kontaktieren.';

$helptxt['secret_why_blank'] = 'Zu Ihrer Sicherheit wird die Antwort (genauso wie Ihr Passwort) zu Ihrer Frage verschl&#252;sselt, so dass SMF Ihnen nur sagen kann ob es richtig ist, jedoch nicht die Antwort oder das Passwort selbst nennen kann!';
$helptxt['moderator_why_missing'] = 'Da die Moderatoren von Board zu Board ausgew&#228;hlt werden, m&#252;ssen Sie diese im Bereich <a href="javascript:window.open(\'' . $scripturl . '?action=manageboards\'); self.close();">Boards verwalten</a> eintragen.';

$helptxt['permissions'] = 'Berechtigungen haben die Funktion, bestimmten Gruppen Aktionen zu erlauben oder zu verbieten<br><br>Sie k&#246;nnen mit Hilfe der Checkboxen mehrere Boards gleichzeitig &#228;ndern oder die Berechtigungen einer bestimmten Gruppe &#228;ndern, in dem Sie auf \'&#196;ndern\' klicken.';
$helptxt['permissions_board'] = 'Wenn ein Board auf \'Global\' gesetzt ist, hat es keine speziellen Berechtigungen. \'Lokal\' dagegen hat eigene Berechtigungen, welche sich von anderen Boards unterscheiden und somit unterschiedliche Berechtigungen zwischen den Boards erlaubt.';
$helptxt['permissions_quickgroups'] = 'Erlaubt das Verwenden der &quot;vordefinierten&quot; Berechtigungen - Standard bedeutet \'nichts spezielles\', Beschr&#228;nkt hei&#223;t \'wie ein Gast\', Moderator vergibt Rechte \'wie einem Moderator\' und \'Wartungsmodus\' bedeutet, dass die Berechtigungen einem Administrator sehr nahe kommen.';
$helptxt['permissions_deny'] = 'Das Verbieten von Berechtigungen erlaubt es Ihnen, bestimmte Funktionen gew&#228;hlten Mitgliedergruppen zu entziehen. Dazu erstellen Sie z.B. eine Mitgliedergruppe und verbieten dieser eine bestimmte Berechtigung.<br><br>Benutzen Sie diese Option mit gro&#223;er Vorsicht, da verbotene Berechtigungen den Mitgliedern nicht mehr zur Verf&#252;gung stehen, selbst wenn die Berechtigungen in einer anderen Gruppe erlaubt sind, der dieses Mitglied angeh&#246;rt.';
$helptxt['permissions_postgroups'] = 'Das Aktivieren der Berechtigungen f&#252;r beitragsbasierende Gruppen erlaubt das Zuweisen von Berechtigungen an Mitglieder mit einer bestimmten Beitragszahl. Diese Berechtigungen werden zu den bereits vorhanden <em>addiert</em>.';
$helptxt['permissions_by_board'] = 'Aktivieren Sie diese Option, k&#246;nnen Sie theoretisch in jedem Board f&#252;r jede Mitgliedergruppe eigene Berechtigungen setzen. Standardm&#228;&#223;ig benutzt ein Board die globalen Berechtigungen, mit Hilfe dieser Option k&#246;nnen Sie es jedoch zur Benutzung von eigenen (lokalen) Berechtigungen zwingen. Dies erlaubt eine sehr komplexe (!) Verwaltung Ihrer Berechtigungen.';
$helptxt['membergroup_guests'] = 'Die Mitgliedergruppe &quot;G&#228;ste&quot; enth&#228;lt alle Benutzer, die nicht eingeloggt sind.';
$helptxt['membergroup_regular_members'] = 'Die Mitgliedergruppe &quot;Normale Mitglieder&quot; bezeichnet alle Benutzer die eingeloggt sind, jedoch keiner prim&#228;re Mitgliedergruppe zugeordnet sind.';
$helptxt['membergroup_administrator'] = 'Der Administrator kann alles im Forum machen und jedes Board ansehen. Es gibt keine Berechtigungs-Einstellungen f&#252;r den Administrator.';
$helptxt['membergroup_moderator'] = 'Die Mitgliedergruppe &quot;Moderator&quot; ist eine spezielle Gruppe. Die zugewiesenen Berechtigungen und Einstellungen gelten <em>nur in den Boards, wo die betreffenden Mitglieder moderieren</em>. Au&#223;erhalb dieser Boards werden sie wie normale Benutzer behandelt.';
$helptxt['membergroups'] = 'In SMF gibt es zwei Arten von Gruppen, denen die Mitglieder zugeteilt sind:
	<ul>
		<li><b>Regul&#228;re Gruppen:</b> In eine regul&#228;re Gruppe werden Mitglieder nicht automatisch eingeteilt. Um ein Mitglied einer dieser Gruppen zuzuordnen, gehen Sie in das Profil des Benutzers und klicken auf &quot;Benutzerkonto&quot;. Von hier aus k&#246;nnen Sie nun das Mitglied verschiedenen Gruppen zuordnen.</li>
		<li><b>Beitragsbasierende Gruppen:</b> Im Gegenteil zu regul&#228;ren Gruppen k&#246;nnen Sie beitragsabh&#228;ngige Gruppen nicht zuordnen. Stattdessen werden Mitglieder diesen automatisch zugeordnet, wenn sie eine bestimmte Zahl an Beitr&#228;gen geschrieben haben.</li>
	</ul>';

$helptxt['calendar_how_edit'] = 'Sie k&#246;nnen die Ereignisse editieren, indem Sie auf den roten Stern (*) neben dem Namen klicken.';

$helptxt['maintenance_general'] = 'Hier k&#246;nnen Sie alle Tabellen in der Datenbank optimieren (sie werden kleiner und schneller), kontrollieren ob Sie die neueste Version haben, alle Fehler finden die das Board betreffen, die Forumswerte neu berechnen und Log-Dateien l&#246;schen.<br><br>Die letzten zwei Optionen sollten Sie nur bei Fehlern im Board anwenden, sind jedoch nicht sch&#228;dlich, wenn Sie diese trotzdem benutzen.';
$helptxt['maintenance_backup'] = 'Dieser Bereich erlaubt eine Sicherung von allen Beitr&#228;gen, Einstellungen, Mitgliedern und anderen Informationen Ihres Forums in einer (wom&#246;glich gro&#223;en) Datei.<br><br>Es ist empfehlenswert, die Daten regelm&#228;&#223;ig zu sichern - am besten w&#246;chentlich -, um die Datensicherheit zu erh&#246;hen.';
$helptxt['maintenance_rot'] = 'Erlaubt das <b>komplette</b> und <b>unwiderrufliche</b> L&#246;schen alter Themen. Es ist empfehlenswert, davor eine Sicherung Ihrer Daten zu machen f&#252;r den Fall, dass Sie etwas l&#246;schen, was Sie nicht wollten.<br><br>Nutzen Sie diese Option mit Vorsicht.';

$helptxt['avatar_allow_server_stored'] = 'Erlaubt den Mitgliedern ein Benutzerbild zu w&#228;hlen, welches auf Ihrem Server liegt. Diese Bilder sind normalerweise am selben Platz wie SMF, nur im &quot;avatar&quot;-Verzeichnis.<br>Tip: Wenn Sie Ordner im &quot;avatar&quot;-Verzeichnis erstellen, k&#246;nnen Sie dadurch &quot;Benutzerbild-Kategorien&quot; benutzen.';
$helptxt['avatar_allow_external_url'] = 'Erlaubt den Mitgliedern die Eingabe einer URL zu ihrem eigenen Benutzerbild. Der Nachteil dieser Option ist, dass zu gro&#223;e Bilder benutzt werden, welche dann den Aufbau des Forums zerst&#246;ren bzw. andere Sachen &#252;berlappen oder Inhalte enthalten, die nicht Ihrem Geschmack entsprechen.';
$helptxt['avatar_download_external'] = 'Aktivieren Sie diese Option, um das externe Benutzerbild herunterzuladen. Sollte das erfolgreich sein, wird es danach wie ein hochgeladenes Benutzerbild behandelt.';
$helptxt['avatar_allow_upload'] = 'Erlaubt den Mitgliedern das Hochladen eigener Profilbilder auf Ihren Server. Es bietet den Vorteil, dass Sie eine bessere Kontrolle &#252;ber die Bilder haben, sie schneller in der Gr&#246;&#223;e ge&#228;ndert werden k&#246;nnen und nicht von fremden, langsamen Servern geladen werden m&#252;ssen.<br><br>Der Nachteil ist, dass die Bilder Platz auf Ihrem Server wegnehmen, was man nicht untersch&#228;tzen sollte.';
$helptxt['avatar_download_png'] = 'PNGs sind gr&#246;&#223;er, bieten aber eine bessere Kompression. Sollte dies nicht aktiviert sein, werden stattdessen JPEG Bilder benutzt - welche meistens kleiner in der Gr&#246;&#223;e sind, jedoch eine schlechtere Qualit&#228;t bieten.';

$helptxt['disableHostnameLookup'] = 'Deaktiviert die Suche nach den Hostnamen, was manche Server sehr langsam macht. Beachten Sie, dass dies das Bannen von Mitgliedern uneffektiver macht.';

$helptxt['search_weight_frequency'] = 'Gewichtungsfaktoren werden benutzt, um die Relevanz eines Suchresultates zu bestimmen. Ver&#228;ndern Sie diese Gewichtungen, um die Resultate auf Ihr Forum abzustimmen. Das Forum einer News-Seite zum Beispiel ben&#246;tigt eine hohe Gewichtung auf \'Alter der neuesten &#220;bereinstimmung\'. Alle Werte sind relativ zueinander und sollten positive Zahlen sein.<br><br>Dieser Faktor z&#228;hlt die Anzahl der &#252;bereinstimmenden Beitr&#228;ge und teilt sie durch die gesamte Zahl der Beitr&#228;ge innerhalb eines Themas.';
$helptxt['search_weight_age'] = 'Gewichtungsfaktoren werden benutzt, um die Relevanz eines Suchresultates zu bestimmen. Ver&#228;ndern Sie diese Gewichtungen, um die Resultate auf Ihr Forum abzustimmen. Das Forum einer News-Seite zum Beispiel ben&#246;tigt eine hohe Gewichtung auf \'Alter der neuesten &#220;bereinstimmung\'. Alle Werte sind relativ zueinander und sollten positive Zahlen sein.<br><br>Dieser Faktor bewertet das Alter der neuesten &#220;bereinstimmung innerhalb eines Themas. Je neuer der Beitrag, desto h&#246;her ist die Bewertung.';
$helptxt['search_weight_length'] = 'Gewichtungsfaktoren werden benutzt, um die Relevanz eines Suchresultates zu bestimmen. Ver&#228;ndern Sie diese Gewichtungen, um die Resultate auf Ihr Forum abzustimmen. Das Forum einer News-Seite zum Beispiel ben&#246;tigt eine hohe Gewichtung auf \'Alter der neuesten &#220;bereinstimmung\'. Alle Werte sind relativ zueinander und sollten positive Zahlen sein.<br><br>Dieser Faktor basiert auf der Themengr&#246;&#223;e. Je mehr Beitr&#228;ge innerhalb eines Themas, desto h&#246;her ist die Bewertung.';
$helptxt['search_weight_subject'] = 'Gewichtungsfaktoren werden benutzt, um die Relevanz eines Suchresultates zu bestimmen. Ver&#228;ndern Sie diese Gewichtungen, um die Resultate auf Ihr Forum abzustimmen. Das Forum einer News-Seite zum Beispiel ben&#246;tigt eine hohe Gewichtung auf \'Alter der neuesten &#220;bereinstimmung\'. Alle Werte sind relativ zueinander und sollten positive Zahlen sein.<br><br>Dieser Faktor schaut nach dem Vorhandensein einer Suchanfrage innerhalb des Betreffs eines Themas.';
$helptxt['search_weight_first_message'] = 'Gewichtungsfaktoren werden benutzt, um die Relevanz eines Suchresultates zu bestimmen. Ver&#228;ndern Sie diese Gewichtungen, um die Resultate auf Ihr Forum abzustimmen. Das Forum einer News-Seite zum Beispiel ben&#246;tigt eine hohe Gewichtung auf \'Alter der neuesten &#220;bereinstimmung\'. Alle Werte sind relativ zueinander und sollten positive Zahlen sein.<br><br>Dieser Faktor schaut nach der &#220;bereinstimmung des Suchbegriffs im ersten Beitrag eines Themas.';
$helptxt['search_weight_sticky'] = 'Gewichtungsfaktoren werden benutzt, um die Relevanz eines Suchresultates zu bestimmen. Ver&#228;ndern Sie diese Gewichtungen, um die Resultate auf Ihr Forum abzustimmen. Das Forum einer News-Seite zum Beispiel ben&#246;tigt eine hohe Gewichtung auf \'Alter der neuesten &#220;bereinstimmung\'. Alle Werte sind relativ zueinander und sollten positive Zahlen sein.<br><br>Dieser Faktor kontrolliert, ob das gefundene Theme fixiert ist und weist ihm bei Bedarf eine h&#246;here Relevanz zu.';
$helptxt['search'] = 'Hier k&#246;nnen Sie die Einstellungen f&#252;r die Suche ver&#228;ndern.';
$helptxt['search_why_use_index'] = 'Ein Such-Index kann die Geschwindigkeit der Suche im Forum merklich erh&#246;hen. Gerade bei einer gro&#223;en Anzahl an Beitr&#228;gen kann die Suche ohne einen Index sehr lange dauern und die Datenbank stark belasten. Wenn Ihr Forum mehr wie 50.000 Beitr&#228;ge enthalten, sollten Sie die Erstellung eines Index in Erw&#228;gung ziehen, um hohe Belastungsspitzen zu vermeiden.<br><br>Beachten Sie bitte, dass ein Index unter Umst&#228;nden einiges an Platz einnehmen kann. Der Volltext-Index ist ein integrierter Index von MySQL, welcher relativ kompakt ist (ungef&#228;hr die gleiche Gr&#246;&#223;e wie die Beitragstabelle), einige W&#246;rter jedoch nicht indiziert und durchaus langsame Abfragen produzieren kann. Der benutzerdefinierte Index ist meistens gr&#246;&#223;er (je nach Konfiguration bis zur 3-fachen Gr&#246;&#223;e der Beitragstabelle), hat jedoch Geschwindigkeitsvorteile und ist relativ stabil.';

$helptxt['see_admin_ip'] = 'IP-Adressen werden Administratoren und Moderatoren zur besseren Moderation bzw. Verfolgung angezeigt. Beachten Sie, dass IP-Adressen einen Benutzer nicht eindeutig identifizieren und bei den meisten Leuten nach einiger Zeit wechseln.<br><br>Mitglieder k&#246;nnen Ihre eigenen IP-Adressen sehen.';
$helptxt['see_member_ip'] = 'Ihre IP-Adresse wird nur Ihnen und Moderatoren angezeigt. Beachten Sie, dass diese Daten nicht die Person identifizieren, da IP-Adressen h&#228;ufig nach einer gewissen Zeit wechseln.<br><br>Sie k&#246;nnen keine IP-Adressen anderer Benutzer sehen und diese k&#246;nnen Ihre nicht sehen.';

$helptxt['ban_cannot_post'] = 'Die \'Kann nicht schreiben\' Einschr&#228;nkung erlaubt dem gebannten Benutzer nur noch das Lesen des Forums. Er kann keine neuen Themen erstellen oder auf existierende antworten, Pers&#246;nliche Mitteilungen versenden oder bei Umfragen abstimmen. Der Benutzer kann aber weiterhin die Themen und Pers&#246;nlichen Mitteilungen lesen.<br><br>Dem gebannten Benutzer wird eine Warnmeldung angezeigt.';

$helptxt['posts_and_topics'] = '
	<ul>
		<li>
			<b>Beitrags-Einstellungen</b><br>
			Hier k&#246;nnen Sie die Einstellungen bez&#252;glich des Schreibens und der Ansicht von Beitr&#228;gen &#228;ndern. Auch die Rechtschreibepr&#252;fung l&#228;sst sich hier aktivieren.
		</li><li>
			<b>Bulletin Board Code</b><br>
			De-/Aktivieren Sie hier die Code-Tags f&#252;r das Schreiben von Beitr&#228;gen. W&#228;hlen Sie weiterhin, welche Codes erlaubt sind und welche nicht.
		</li><li>
			<b>Zensierte W&#246;rter</b>
			Um die Ausdr&#252;cke in Ihrem Forum unter Kontrolle zu halten, k&#246;nnen Sie bestimmte W&#246;rter zensieren. Diese Funktion ersetzt das betroffene Wort mit einer - von Ihnen gew&#228;hlten - vern&#252;nftigen Variante.
		</li><li>
			<b>Themen-Einstellungen</b>
			Hier k&#246;nnen Sie Einstellungen bez&#252;glich der Themen ver&#228;ndern: Anzahl der Themen pro Seite, fixierte Themen de-/aktivieren, Anzahl der Beitr&#228;ge ab der ein Thema hei&#223; oder sehr hei&#223; ist etc.
		</li>
	</ul>';
