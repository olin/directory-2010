<?php
if(!isset($_REQUEST['t'])){ die("Please specify a timestamp as parameter <strong>t</strong> in the GET request."); }
$t = floor($_REQUEST['t']);

$dtstart = date('Ymd',$t);
$dtend = date('Ymd',strtotime("+1 day",$t));
$title = "Olin Password Expires";

header("Content-Type: text/calendar");
header('Content-Disposition: attachment; filename="'.$title.'.ics"');
?>
BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
BEGIN:VEVENT
CLASS:PRIVATE
DESCRIPTION:Just a reminder that your Olin network password expires on <?php print date("l F j, Y",$t);;?>.\n\nYou can change it on your laptop (on-campus\; VPN won't work) or from anywhere using Webmail.  See http://it.olin.edu/documents/Network/Change_Network_Password.pdf for more instructions.\n
DTEND;VALUE=DATE:<?php print $dtend."\n"; ?>
DTSTART;VALUE=DATE:<?php print $dtstart."\n"; ?>
PRIORITY:5
SEQUENCE:0
SUMMARY;LANGUAGE=en-us:<?php print $title."\n"; ?>
TRANSP:TRANSPARENT
BEGIN:VALARM
TRIGGER:-PT10080M
ACTION:DISPLAY
DESCRIPTION:Reminder
END:VALARM
END:VEVENT
END:VCALENDAR
