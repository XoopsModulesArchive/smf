<?php
// Version: 1.1; PersonalMessage

$txt[143] = 'Private Mitteilungen';
$txt[148] = 'Senden';
$txt[150] = 'An';
$txt[1502] = 'Bcc';
$txt[316] = 'Posteingang';
$txt[320] = 'Postausgang';
$txt[321] = 'Neue Mitteilung';
$txt[411] = 'L&#246;sche Mitteilung';
// Don't translate "PMBOX" in this string.
$txt[412] = 'Alle Mitteilungen in Ihrem PMBOX l&#246;schen';
$txt[413] = 'Sind Sie sicher, dass Sie alle Mitteilungen l&#246;schen m&#246;chten?';
$txt[535] = 'Empf&#228;nger';
// Don't translate the word "SUBJECT" here, as it is used to format the message - use numeric entities as well.
$txt[561] = 'Neue Private Mitteilung: SUBJECT';
// Don't translate SENDER or MESSAGE in this language string; they are replaced with the corresponding text - use numeric entities too.
$txt[562] = 'Sie haben eine Private Mitteilung von SENDER im Forum ' . $context['forum_name'] . ' erhalten.' . "\n\n" . 'WICHTIG: Das ist nur eine Benachrichtigung - bitte antworten Sie nicht auf diese E-Mail!' . "\n\n" . 'Die Nachricht, die an Sie gesendet wurde:' . "\n\n" . 'MESSAGE';
$txt[748] = '(mehrere Empf&#228;nger als \'username1, username2\')';
// Use numeric entities in the below string.
$txt['instant_reply'] = 'Auf diese Mitteilung antworten:';

$txt['smf249'] = 'Sind Sie sicher, dass Sie die ausgew&#228;hlten Privaten Mitteilungen l&#246;schen m&#246;chten?';

$txt['sent_to'] = 'Senden an';
$txt['reply_to_all'] = 'Allen antworten';

$txt['pm_capacity'] = 'Kapazit&#228;t';
$txt['pm_currently_using'] = '%s Mitteilungen, %s%% voll.';

$txt['pm_error_user_not_found'] = 'Kann Mitglied \'%s\' nicht finden.';
$txt['pm_error_ignored_by_user'] = 'Mitglied \'%s\' hat Ihre Mitteilungen geblockt.';
$txt['pm_error_data_limit_reached'] = 'Mitteilung konnte wegen des max. Limit nicht an \'%s\' gesendet werden.';
$txt['pm_successfully_sent'] = 'Mitteilung erfolgreich an \'%s\' gesendet.';
$txt['pm_too_many_recipients'] = 'Sie k&#246;nnen keine Privaten Mitteilungen an mehr wie %d Empf&#228;nger gleichzeitig verschicken.';
$txt['pm_too_many_per_hour'] = 'Sie haben das Limit von %d Privaten Mitteilungen pro Stunde erreicht.';
$txt['pm_send_report'] = 'Report senden';
$txt['pm_save_outbox'] = 'Kopie im Ausgang speichern';
$txt['pm_undisclosed_recipients'] = 'Verdeckter Empf&#228;nger';

$txt['pm_read'] = 'Lesen';
$txt['pm_replied'] = 'Antwort an';

$txt['pm_prune'] = 'Mitteilungen bereinigen';
$txt['pm_prune_desc1'] = 'Alle Privaten Mitteilungen &#228;lter als';
$txt['pm_prune_desc2'] = 'Tage l&#246;schen.';
$txt['pm_prune_warning'] = 'Sind Sie sicher, dass Sie Ihre Mitteilungen bereinigen m&#246;chten?';

$txt['pm_actions_title'] = 'Weitere Funktionen';
$txt['pm_actions_delete_selected'] = 'Markierte Mitteilungen entfernen';
$txt['pm_actions_filter_by_label'] = 'Nach Label filtern';
$txt['pm_actions_go'] = 'Los';

$txt['pm_apply'] = '&#220;bernehmen';
$txt['pm_manage_labels'] = 'Labels verwalten';
$txt['pm_labels_delete'] = 'Sind Sie sicher, dass Sie die ausgew&#228;hlten Labels l&#246;schen m&#246;chten?';
$txt['pm_labels_desc'] = 'Hier k&#246;nnen Sie Labels zu Ihren Privaten Mitteilungen hinzuf&#252;gen, editieren und l&#246;schen.';
$txt['pm_label_add_new'] = 'Neues Label hinzuf&#252;gen';
$txt['pm_label_name'] = 'Name des Label';
$txt['pm_labels_no_exist'] = 'Sie haben noch keine Labels erstellt!';

$txt['pm_current_label'] = 'Label';
$txt['pm_msg_label_title'] = 'Mitteilung kennzeichnen';
$txt['pm_msg_label_apply'] = 'Label hinzuf&#252;gen';
$txt['pm_msg_label_remove'] = 'Label entfernen';
$txt['pm_msg_label_inbox'] = 'Posteingang';
$txt['pm_sel_label_title'] = 'Ausgew&#228;hlte kennzeichnen';
$txt['labels_too_many'] = '%s Mitteilungen haben schon die max. Anzahl an erlaubten Labels!';

$txt['pm_labels'] = 'Labels';
$txt['pm_messages'] = 'Mitteilungen';
$txt['pm_preferences'] = 'Einstellungen';

$txt['pm_is_replied_to'] = 'Sie haben diese Mitteilung weitergeleitet oder schon darauf geantwortet.';

// Reporting messages.
$txt['pm_report_to_admin'] = 'Administrator informieren';
$txt['pm_report_title'] = 'Private Mitteilung melden';
$txt['pm_report_desc'] = 'Hier k&#246;nnen Sie Private Mitteilungen den Administratoren des Forums melden. Bitte f&#252;gen Sie eine kurze Beschreibung an, warum Sie diese Mitteilung melden m&#246;chten. Die Beschreibung wird mit der Originalnachricht versendet.';
$txt['pm_report_admins'] = 'An folgenden Administrator melden';
$txt['pm_report_all_admins'] = 'An alle Administratoren melden';
$txt['pm_report_reason'] = 'Grund f&#252;r die Meldung der Mitteilung';
$txt['pm_report_message'] = 'Mitteilung melden';

// Important - The following strings should use numeric entities.
$txt['pm_report_pm_subject'] = '[MELDUNG] ';
// In the below string, do not translate "{REPORTER}" or "{SENDER}".
$txt['pm_report_pm_user_sent'] = '{REPORTER} hat die untenstehende Mitteilung, die von {SENDER} gesendet wurde, mit folgendem Grund gemeldet:';
$txt['pm_report_pm_other_recipients'] = 'Andere Empf&#228;nger der Meldung:';
$txt['pm_report_pm_hidden'] = '%d versteckte Empf&#228;nger';
$txt['pm_report_pm_unedited_below'] = 'Der Originaltext der gemeldeten Mitteilung lautet wie folgt:';
$txt['pm_report_pm_sent'] = 'Gesendet:';

$txt['pm_report_done'] = 'Vielen Dank f&#252;r das Melden der Mitteilung. Sie sollten in K&#252;rze von den Administratoren eine Antwort erhalten.';
$txt['pm_report_return'] = 'Zur&#252;ck zum Posteingang';

$txt['pm_search_title'] = 'Private Mitteilungen durchsuchen';
$txt['pm_search_bar_title'] = 'Private Mitteilungen durchsuchen';
$txt['pm_search_text'] = 'Suche nach';
$txt['pm_search_go'] = 'Suchen';
$txt['pm_search_advanced'] = 'Erweiterte Suche';
$txt['pm_search_user'] = 'nach Mitglied';
$txt['pm_search_match_all'] = '&#220;bereinstimmung aller W&#246;rter';
$txt['pm_search_match_any'] = '&#220;bereinstimmung eines Wortes';
$txt['pm_search_options'] = 'Optionen';
$txt['pm_search_post_age'] = 'Alter';
$txt['pm_search_show_complete'] = 'Zeige vollst&#228;ndige Mitteilung in Suchergebnis';
$txt['pm_search_subject_only'] = 'Suche nur nach Betreff und Autor';
$txt['pm_search_between'] = 'Zwischen';
$txt['pm_search_between_and'] = 'und';
$txt['pm_search_between_days'] = 'Tagen';
$txt['pm_search_order'] = 'Ergebnisse sortieren nach';
$txt['pm_search_choose_label'] = 'Labels zum Suchen ausw&#228;hlen oder alle durchsuchen';

$txt['pm_search_results'] = 'Suchresultate';
$txt['pm_search_none_found'] = 'Keine Mitteilungen gefunden';

$txt['pm_search_orderby_relevant_first'] = 'Gr&#246;&#223;te Relevanz zuerst';
$txt['pm_search_orderby_recent_first'] = 'Neueste Mitteilungen zuerst';
$txt['pm_search_orderby_old_first'] = '&#196;lteste Mitteilungen zuerst';

$txt['pm_visual_verification_label'] = 'Verifizierung';
$txt['pm_visual_verification_desc'] = 'Bitte geben Sie den Code aus dem Bild ein, um diese Mitteilung zu versenden.';
$txt['pm_visual_verification_listen'] = 'H&#246;ren Sie sich die Buchstaben an';
