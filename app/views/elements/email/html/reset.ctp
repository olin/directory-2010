<?php
$resetURL = (@$absoluteBase)."/account/reset/verify/".(@$token);
?>
<p>If you've forgotten your password and would like to reset it, just click this link:</p>
<p><a style="color:#336699;" target="_blank" href="<?php print @$resetURL?>"><?php print @$resetURL?></a></p>
<p style="font-style: italic; color: #777777; font-size: 90%; margin-left: 1em; margin-bottom: 2em;">(If you don't want to reset your password, you can safely ignore this message.)</p> 
<p>Thanks for using OlinDirectory, and remember to come back to update your information whenever it changes!</p>
<p>- Admin</p>
