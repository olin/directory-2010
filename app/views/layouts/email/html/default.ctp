<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="margin: 0; padding: 0 background: #ffffff; font-size: 11pt; color: #222222; font-family: 'Quicksand', 'Century Gothic', Verdana, Tahoma, Geneva, sans-serif;">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>OlinDirectory : <?php echo @$title;?></title>
</head>
<body bgcolor="#ffffff" style="margin: 0; padding: 0; background: #ffffff; font-size: 11pt; color: #222222; font-family: 'Quicksand', 'Century Gothic', Verdana, Tahoma, Geneva, sans-serif;">
<div style="padding: 0;margin: 0;width:100%;" >

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 0;border: none; margin: 0;width:100%; font-family: 'Quicksand', 'Century Gothic', Verdana, Tahoma, Geneva, sans-serif;">
<tr class="Header" style="padding:0;">
<td style="padding: 0.2em; margin: 0;" valign="middle" align="left" width="155"><a target="_blank" href="<?php print $absoluteBase;?>/"><img src="<?php print $absoluteBase;?>/img/email/logo.png" border="0" width="152" height="32" /></a></td>
<td style="padding: 0.2em; margin: 0;" valign="middle"><h2 style="text-transform: lowercase;margin: 0;padding: 0;font-weight: normal;color: #222222;"><?php print @$title;?></h2></td>
</tr>
<tr style="padding:0;">
<td colspan="2" style="padding: 0.5em; padding-top:0; margin: 0;">

<?php if(isset($smtp_errors)){ ?>
<pre style="margin:0.5em; border:1px solid #aaa; background: #ddd;">
<strong>SMTP Errors:</strong>
<?php print $smtp_errors; ?>
</pre>
<?php } ?>

<?php echo $content_for_layout; ?>
</td></tr>
<tr class="Footer" style="padding:0;">
<td colspan="2" style="padding: 0.5em 1em; margin: 0; font-size: 80%; font-style: italic; color: #777777; padding: 1em;">
	<p style="margin: 0;">
	This message was sent to <?php echo @$email; ?> by <a style="color:#666666;font-style:normal;" target="_blank" href="<?php print $absoluteBase;?>">OlinDirectory</a>.
	To stop receiving mail, please <a style="color:#666666;font-style:normal;" target="_blank" href="<?php print $absoluteBase;?>/account/signin">sign in</a>
	and modify your account settings.  (<a style="color:#666666;font-style:normal;" target="_blank" href="<?php print $absoluteBase;?>/help#privacy">more info about privacy</a>)
	</p>
</td></tr>
</table>
</div>
</body>
</html>