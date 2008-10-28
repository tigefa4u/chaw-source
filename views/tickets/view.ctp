<?php
$script = '
$(document).ready(function(){
	converter = new Showdown.converter("' . $this->webroot . '");
	//$("#Preview").html(jQuery.trim($("#Preview").text()));
	$("#TicketDescription").bind("keyup", function() {
		$("#Preview").html(jQuery.trim($(this).val()));
	});
	$(".modify").click(function() {
		$("#modify").show();
	});
	$(".close").click(function() {
		$("#modify").hide();
	});
	$(".body").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));
?>
<h2>
	<?php echo ucwords($ticket['Ticket']['type']);?> Ticket
	(<em><?php echo $ticket['Ticket']['status'];?></em>)
</h2>
<div class="tickets view">
	<h3 class="title">
		<?php echo $ticket['Ticket']['title'];?>
		<?php if (!empty($CurrentUser->id)): ?>
			<em>(<a href="#modify" class="modify">edit</a>)</em>
		<?php endif; ?>
	</h3>

	<div id="Preview" class="description">
		<?php echo $html->clean($ticket['Ticket']['description']); ?>
	</div>

	<div class="ticket edit">

		<?php if (!empty($CurrentUser->id)): ?>

			<?php echo $form->create(array('action' => 'modify'));?>

				<div id="modify" style="display:none">
					<fieldset class="main">
						<legend>
							<?php __('Modify Ticket');?>
							<em>(<a href="#" class="close">close</a>)</em>
						</legend>

						<?php
							echo $form->input('id');
							echo $form->input('title');
							echo $form->input('description');
						?>
					</fieldset>

					<fieldset class="options">
						<legend>Tags</legend>
						<?php
							echo $form->textarea('tags');
						?>
						comma separated
					</fieldset>

					<div class="help">
						<?php echo $this->element('markdown_help'); ?>
					</div>

				</div>

		<?php endif; ?>

			<?php foreach ((array)$ticket['Comment'] as $comment): ?>

				<div class="comment">
					<span class="date">
						<?php echo $time->timeAgoInWords($comment['created']);?>
					</span>
					<span class="user">
						by <?php echo $comment['User']['username'];?>
					</span>
					<div class="body">
						<?php echo $html->clean($comment['body']);?>
					</div>
				</div>

			<?php endforeach; ?>

		<?php if (!empty($CurrentUser->id)): ?>

				<fieldset class="main">
			 		<legend>
						<?php __('Comment');?>
					</legend>
					<?php
						echo $form->input('status');
						echo $form->textarea('comment');
					?>
				</fieldset>

				<fieldset class="options">
					<legend>Options</legend>
					<?php
						echo $form->input('version_id');
						echo $form->input('type');
						echo $form->input('priority');
					?>
				</fieldset>

				<div class="submit">
					<?php echo $form->submit('Submit', array('div' => false));?>
				</div>


			<?php echo $form->end();?>

		<?php endif; ?>
	</div>

</div>