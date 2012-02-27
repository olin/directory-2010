<div class="accounts view">
<h2><?php  __('Account');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('AuthType'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['authType']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('AuthName'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['authName']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Email Addr'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['email']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Permissions'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['permissions']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('FirstName'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['firstName']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('LastName'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['lastName']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $account['Account']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit This Account', true), array('action' => 'edit', $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('Delete This Account+UserDetails', true), array('action' => 'delete', $account['Account']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $account['Account']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List All Accounts', true), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Account', true), array('action' => 'add')); ?> </li>
	</ul>
</div>

		<div class="related">
		<h3><?php __('Related User Details');?></h3>
	<?php if (!empty($account['UserDetail'])):?>
		<dl>	<?php $i = 0; $class = ' class="altrow"';?>
			<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Account Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['account_id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Nickname');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['nickname'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('IsAway');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['isAway'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ClassYearEntry');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['classYearEntry'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ClassYearExpected');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['classYearExpected'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('CampusMailbox');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['campusMailbox'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Building Id');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['building_id'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('CampusRoom');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['campusRoom'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('PhoneMobile');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['phoneMobile'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ImAOL');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['imAOL'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ImGTalk');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['imGTalk'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ImICQ');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['imICQ'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ImMSN');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['imMSN'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('ImSkype');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['imSkype'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['created'];?>
&nbsp;</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified');?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
	<?php echo $account['UserDetail']['modified'];?>
&nbsp;</dd>
		</dl>
	<?php endif; ?>
		<div class="actions">
			<ul>
				<li><?php echo $this->Html->link(__('Edit User Detail', true), array('controller' => 'user_details', 'action' => 'edit', $account['UserDetail']['account_id'])); ?></li>
			</ul>
		</div>
	</div>
	