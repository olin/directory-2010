<?php
$verifyURL = (@$absoluteBase)."/join/verify/".(@$token);
?>
<p>Welcome to OlinDirectory, and thanks for signing up!  To create your account, pick a password, and edit your profile, just click this link:</p>
<p><a style="color:#336699;" target="_blank" href="<?php print @$verifyURL?>"><?php print @$verifyURL?></a></p>
<p style="font-style: italic; color: #777777; font-size: 90%; margin-left: 1em; margin-bottom: 2em;">(If you don't want to create an account, you can safely ignore this message.)</p>
<p>Thanks for using OlinDirectory, and remember to come back to update your information whenever it changes!</p>
<p>- Admin</p>
