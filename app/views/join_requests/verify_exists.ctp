<h2>Join OlinDirectory</h2> 

<div class="Wrapped">
<p class="Warning">You already have an account (<?php print @$email;?>)</p>
<p>Even though you asked us to sign you up, it seems you already have an account here.
You can go ahead and <?php echo $html->link('sign in',array('controller'=>'account','action'=>'signin'))?>.
If you forgot your password, <?php echo $html->link('reset it',array('controller'=>'reset_requests','action'=>'index'))?>.
</div>
