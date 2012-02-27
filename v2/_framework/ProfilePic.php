<?php
$uid = str_replace('.','',$_REQUEST['u']);
$file = "../_headshots/uid/$uid.jpg";
if(!file_exists($file)){ $file="../_headshots/unknown.jpg"; }

//no-cache headers
header('Content-Type: image/jpeg');
header('Content-Disposition: inline; filename='.basename($file));
header('Content-Transfer-Encoding: binary');
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header('Pragma: no-cache');
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header('Content-Length: ' . filesize($file));

//output file contents
readfile($file);
flush();
exit;
?>