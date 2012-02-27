<?php
//Scripts to include in the page
$javascript->link('lib/jquery-1.4.3.min.js', false);
//useful library functions
$javascript->link('lib/utils.js', false);
//focus on first non-empty text/password field
$javascript->codeBlock("setupStandardInputFocus(true);", array('inline'=>false));
?>

<h1>Sign In</h1>

<p>If you don't have an account, <?php echo $html->link('create one now',array('controller'=>'join_requests')); ?>.</p>

<div class="signin form">
<?php echo $this->Form->create('Account');?>
	<fieldset>
	<?php
		$flashAuth = $session->flash('auth');
		if($flashAuth){
			echo $flashAuth.'<br />';
		}else{
			echo '<div>Please sign in to continue.</div><br />';
		}
		echo $this->Form->input('authName',array('label'=>'Olin Email'));
		echo $this->Form->input('authPassword',array('type'=>'password','label'=>'Password'));
		?>
		<div class="submit">
		<?php echo $this->Form->button('Sign In', array('type'=>'submit')); ?>
		<p class="ResetRequest" style="display:block;float:left;margin-left:-2.2em;">
			<?php echo $this->Html->link(__('Forgot your password?', true), array('controller' => 'reset_requests', 'action' => 'index')); ?>
		</p>
		</div>
		<?php
	?>
	</fieldset>
<?php echo $this->Form->end(); ?>
</div>



