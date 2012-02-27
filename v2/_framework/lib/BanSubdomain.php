<?php
if($_SERVER['HTTP_HOST']!='acl.olin.edu'){
	$protocol = ($_SERVER['HTTPS']=='on'?'https':'http');
	$url = "$protocol://acl.olin.edu/directory".$_SERVER['PHP_SELF'];
	$url = preg_replace('/\/index.php$/','/',$url);
	header("Location: $url");
	die("Please update your bookmarks to use this URL: <a href=\"$url\">$url</a>");
	}
?>
