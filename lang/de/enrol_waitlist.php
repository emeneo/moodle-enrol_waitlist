<?php
/**
 * *************************************************************************
 * *                  Waitlist Enrol                                      **
 * *************************************************************************
 * @copyright   emeneo.com                                                **
 * @link        emeneo.com                                                **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************
*/
$string['customwelcomemessage'] = 'Begrüßungstext';
$string['defaultrole'] = 'Rolle im Kurs';
$string['defaultrole_desc'] = 'Wählen Sie eine Rolle aus, die Nutzer/innen bei der Selbsteinschreibung
zugewiesen werden soll';
$string['enrolenddate'] = 'Einschreibeschluss';
$string['enrolenddate_help'] = 'Wenn diese Option aktiviert ist, können Nutzer/innen sich bis zum
angegebenen Zeitpunkt selbst einschreiben.';
$string['enrolenddaterror'] = 'Der Einschreibeschluss muss später als der -beginn sein';
$string['enrolme'] = 'Einschreiben';
$string['enrolperiod'] = 'Teilnahmedauer';
$string['enrolperiod_desc'] = 'Die standardmäßige Teilnahmedauer ist der Zeitraum, während dem die
Einschreibung moglich ist. Wenn diese Option deaktiviert ist, ist die standardmäßige
Teilnahmedauer unbegrenzt.';
$string['enrolperiod_help'] = 'Die Teilnahmedauer ist der Zeitraum, während dem die Einschreibung moglich
ist, beginnend mit dem Moment der Nutzereinschreibung. Wenn diese Option
deaktiviert ist, ist die standardmäßige Teilnahmedauer unbegrenzt.';
$string['enrolstartdate'] = 'Einschreibebeginn';
$string['enrolstartdate_help'] = 'Wenn diese Option aktiviert ist, können Nutzer/innen sich ab diesem
Zeitpunkt selbst in den Kurs einschreiben.';
$string['groupkey'] = 'Einschreibeschlüssel für Gruppen';
$string['groupkey_desc'] = 'Standardmäßig einen Einschreibeschlüssel für Gruppen benutzen.';
$string['groupkey_help'] = 'Ergänzend zur Zugriffssteuerung über einen Einschreibeschlüssel für den Kurs
können zusätzliche Einschreibeschlüssel für Gruppen festgelegt werden, die
bei der Kurseinschreibung automatisch alle Nutzer/innen einer bestimmten
Gruppe zuweisen. Um Einschreibeschlüssel für Gruppen verwenden zu können,
muss ein Einschreibeschlüssel für den Kurs vergeben sein, den aber
eigentlich niemand kennen muss. Der Einschreibeschlüssel für die jeweilige
Gruppe wird in den Gruppeneinstellungen festgelegt.';
$string['longtimenosee'] = 'Inaktiven Nutzer abmelden';
$string['longtimenosee_help'] = 'Wenn Personen lange Zeit nicht mehr auf einen Kurs zugegriffen haben, werden
sie automatisch abgemeldet. Dieser Parameter legt die maximale
Inaktivitätsdauer fest.';
$string['maxenrolled'] = 'Einschreibungen max.';
$string['maxenrolled_help'] = 'Diese Option legt die Maximalzahl möglicher Nutzer/innen mit
Selbsteinschreibung fest. 0= unbeschränkt.';
$string['maxenrolledreached'] = 'Die maximale Anzahl der erlaubten Nutzer/innen mit Selbsteinschreibung ist
bereits erreicht.';
$string['password'] = 'Einschreibeschlüssel';
$string['password_help'] = 'Ein Einschreibeschlüssel erlaubt den Kurszugriff ausschließlich für
diejenigen, die den Einschreibeschlüssel kennen. Wenn das Feld leer bleibt,
können sich alle Nutzer/innen im Kurs einschreiben. Wenn ein
Einschreibeschlüssel angegeben ist, müssen alle Nutzer/innen
notwendigerweise bei der Kurseinschreibung den Einschreibeschlüssel
eingeben. Beachten Sie, dass Nutzer/innen den Einschreibeschlüssel nur
einmal bei der Kurseinschreibung eingeben müssen und danach dauerhaft
eingeschriebene Kursteilnehmer/innen sind. ';
$string['passwordinvalid'] = 'Falscher Einschreibeschlüssel. Bitte versuchen Sie es erneut.';
$string['passwordinvalidhint'] = 'Falscher Einschreibeschlüssel 
<br />
(Hinweis: Das erste Zeichen ist  \'{$a}\')';
$string['pluginname'] = 'Selbsteinschreibung';
$string['pluginname_desc'] = 'Das Plugin Selbsteinschreibung erlaubt Nutzer/innen zu wählen, in welchen
Kursen sie teilnehmen möchten. Die Kurse können mit einem
Einschreibeschlüssel gesichert sein. Intern wird die Selbsteinschreibung
über das Plugin Manuelle Einschreibung abgewickelt, welches im Kurs
notwendigerweise ebenfalls aktiviert sein muss.';
$string['requirepassword'] = 'Einschreibeschlüssel notwendig';
$string['requirepassword_desc'] = 'Die Verwendung eines Einschreibeschlüssels ist notwendig. Mit dieser
Einstellung wird in neuen Kursen ein Einschreibeschlüssel gesetzt und in
bestehenden Kursen das Löschen des Einschreibeschlüssels verhindert.';
$string['role'] = 'Rolle im Kurs';
$string['waitlist:config'] = 'Wartelisteneinschreibung konfigurieren';
$string['waitlist:manage'] = 'Eingeschriebene Nutzer/innen verwalten';
$string['waitlist:unenrol'] = 'Nutzer/innen aus dem Kurs abmelden';
$string['waitlist:unenrolwaitlist'] = 'Warteliste aus dem Kurs abmelden';
$string['sendcoursewelcomemessage'] = 'Begrüßungstext versenden';
$string['sendcoursewelcomemessage_help'] = 'Wenn diese Option aktiviert ist, erhalten alle Nutzer/innen einen
Begrüßungstext per E-Mail, sobald sie sich selbst in einen Kurs
einschreiben.';
$string['showhint'] = 'Hinweis anzeigen';
$string['showhint_desc'] = 'Erstes Zeichen des Zugangsschlüssels zeigen.';
$string['status'] = 'Wartelisteneinschreibung';
$string['status_desc'] = 'Nutzer/innen erlauben, sich standardmäßig selbst in Kurse einzuschreiben';
$string['status_help'] = 'Diese Einstellung legt fest, ob Nutzer/innen sich selbst in einem Kurs
einschreiben (und mit entsprechender Berechtigung auch wieder abmelden)
dürfen.';
$string['unenrolwaitlistconfirm'] = 'Möchten Sie sich wirklich selbst aus dem Kurs \'{$a}\' abmelden?';
$string['usepasswordpolicy'] = 'Kennwortregeln benutzen';
$string['usepasswordpolicy_desc'] = 'Die allgemeinen Kennwortregeln gelten auch für die Einschreibeschlüssel.';
$string['welcometocourse'] = 'Willkommen zu {$a}';
$string['welcometocoursetext'] = '<p>Willkommen im Kurs {$a->coursename}!</p>
<p>Start: {$a->startdate}</p>
<p>Info: </p>
<p>{$a->summary}</p>';
$string['confirmation'] = 'Wenn Sie fortfahren, werden Sie in diesen Kurs eingeschrieben.<br><br>Sind Sie sicher, dass Sie sich einschreiben wollen?';
$string['confirmationfull'] = '<strong>Sie sind nun in diesen Kurs eingeschrieben.</strong> Wenn Sie fortfahren, werden Sie automatisch in der Warteliste aufgenommen und via Email informiert, falls Sie auf einen frei gewordenen Platz nachrücken.<br>';
$string['confirmation_yes'] = 'Ja';
$string['confirmation_no'] = 'Nein';
$string['waitlistinfo'] = '<b>Sie sind nun in diesen Kurs eingeschrieben</b>. <br/><br/>Vielen Dank für Ihre Anfrage. Sie sind in der Warteliste aufgenommen und werden via Email informiert, falls Sie auf einen frei gewordenen Platz nachrücken.';
$string['waitlist:unenrolself'] = 'Aus dem Kurs austragen';
$string['lineinfo'] = '<br>Anzahl der Personen vor Ihnen auf der Warteliste: ';
$string['lineconfirm'] = '<br>Sind Sie sicher, dass Sie fortfahren wollen?';
 
