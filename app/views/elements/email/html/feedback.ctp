<?php
$name = @$data['Feedback']['name'];
$email = @$data['Feedback']['email'];
$feedback = @$data['Feedback']['text'];
$feedback = str_replace("\n","<br />\n",htmlspecialchars($feedback));
$feedbackID = @$data['Feedback']['id'];
?>

<p>New feedback from <?php print htmlspecialchars($name);?> (<?php print htmlspecialchars($email);?>)</p>
<p class="Quoted" style="margin: 1em; background: #eee; padding: 1em; border: 1px solid #999; -webkit-border-radius: 0.5em; -moz-border-radius: 0.5em; border-radius: 0.5em;">
<?php print $feedback; ?>
</p>
<p>(<a href="<?php print @$absoluteBase;?>/admin/Feedbacks/view/<?php print @$feedbackID;?>">view in admin interface</a>)</p>
