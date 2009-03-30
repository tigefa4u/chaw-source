<?php
$html->css('highlight/idea', null, null, false);
$javascript->link('highlight.pack', false);

$script = '
hljs.initHighlightingOnLoad();

$(document).ready(function(){
	$("#Preview").html(converter.makeHtml(jQuery.trim($("#Preview").text())));
	$("#TicketDescription").bind("keyup", function() {
		$("#Preview").html(converter.makeHtml($(this).val()));
		hljs.initHighlighting.called = false;
		hljs.initHighlighting();
	});
	$("#TicketComment").bind("keyup", function() {
		$("#CommentPreviewWrapper").show();
		$("#CommentPreview").html(converter.makeHtml($(this).val()));
		hljs.initHighlighting.called = false;
		hljs.initHighlighting();
	});
	$(".modify").click(function() {
		$("#modify").show();
		$(".comments").hide();
		$("#TicketTitle").parent().before($("fieldset.prop"));
	});
	$(".close").click(function() {
		$("#modify").hide();
		$(".comments").show();
		$("#TicketComment").after($("fieldset.prop"));

	});
	$(".body").each(function () {
		$(this).html(converter.makeHtml(jQuery.trim($(this).text())))
	});
});
';
$javascript->codeBlock($script, array('inline' => false));

$canEdit = !empty($canUpdate) || (!empty($CurrentUser->id) && $CurrentUser->id == $ticket['Reporter']['id']);

if ($session->check('Ticket.back')) {
	echo $html->tag('div', $html->link('back', $session->read('Ticket.back')), array('class' => 'page-navigation'));
}

?>
<h2>
	<?php echo strtoupper(Inflector::humanize($ticket['Ticket']['type']));?> <?php __('Ticket') ?>
	(<em><?php echo $ticket['Ticket']['status'];?></em>)
</h2>
<div class="tickets">

	<div class="view">

		<h3 class="title">
			<?php echo $ticket['Ticket']['title'];?>
			<?php if (!empty($canEdit)): ?>
				<em>(<a href="#modify" class="modify"><?php __('edit') ?></a>)</em>
			<?php endif; ?>
		</h3>

		<div id="Preview" class="description">
			<?php echo h($ticket['Ticket']['description']); ?>
		</div>

		<span class="date">
			<?php echo $time->timeAgoInWords($ticket['Ticket']['created'], 'm-d-y');?>
		</span>

		<span class="reporter">
			<strong><?php __('reported by') ?>:</strong> <?php echo $ticket['Reporter']['username'];?>
		</span>

	</div>

	<div class="edit">

		<?php if (!empty($CurrentUser->id)): ?>

			<?php echo $form->create(array('action' => 'modify', 'url'=> array($form->value('Ticket.number'), 'id'=> false)));?>

				<?php if (!empty($canEdit)):?>
					<div id="modify" style="display:none">
						<fieldset class="main">
							<legend>
								<?php __('Modify Ticket');?>
								<em>(<a href="#" class="close">close</a>)</em>
							</legend>
							<fieldset class="options">
								<?php
									echo $form->input('owner', array('empty' => true));
									echo $form->input('type');
									echo $form->input('priority');
									if (!empty($versions)) {
										echo $form->input('version_id');
									}
								?>
							</fieldset>
							<?php
								echo $form->input('title',array('label'=>  __('Title',true)));
								echo $form->input('description',array('label'=> __('Description',true)));
							?>
						</fieldset>

						<fieldset class="tags options">
							<legend><?php __('Tags') ?></legend>
							<?php
								echo $form->textarea('tags');
							?>
							<?php __('comma separated') ?>
						</fieldset>

						<div class="help">
							<?php echo $this->element('markdown_help'); ?>
						</div>

					</div>
				<?php endif; ?>

		<?php endif; ?>

			<div class="comments">
				<?php foreach ((array)$ticket['Comment'] as $comment): ?>

					<div class="comment" id="c<?php echo $comment['id']?>">
						<span class="date">
							<?php echo $time->timeAgoInWords($comment['created'], 'm-d-y');?>
						</span>
						<span class="user">
							by <?php echo $comment['User']['username'];?>
						</span>

					<?php if(!empty($this->params['isAdmin'])):?>
						<span class="admin">
							<?php echo $html->link('delete', array(
								'controller' => 'comments', 'action' => 'delete', $comment['id']
							))?>
						</span>
					<?php endif; ?>

						<div class="body">
							<?php
								if (!empty($comment['changes'])) {
									echo $chaw->changes($comment['changes']);
								}
								if (!empty($comment['reason'])) {
									echo h($comment['reason']);
								}
								echo h($comment['body']);

							?>
						</div>
					</div>

				<?php endforeach; ?>
			</div>

		<?php if (!empty($CurrentUser->id)): ?>

				<div class="comments">
					<div id="CommentPreviewWrapper" class="comment" style="display:none">
						<h3 class="clearfix"><?php __('Preview') ?></h3>

						<span class="date">
							<?php echo $time->timeAgoInWords(date('Y-m-d H:i:s', strtotime('1 sec')));?>
						</span>
						<span class="user">
							by <?php echo $CurrentUser->username;?>
						</span>
						<div id="CommentPreview" class="body"></div>
					</div>
				</div>

				<fieldset class="comments main">
			 		<legend>
						<?php __('Comment');?>
					</legend>
					<fieldset class="options">
					<?php
						if ($ticket['Ticket']['status'] == 'closed') {
							echo $form->checkbox('event', array(
								'value' => 'reopen',
							));
							echo $form->label('event', __('reopen', true));
						} else if (!empty($canUpdate)) {
							echo $form->input('event', array(
								'label'=> __('Action', true), 'empty' => true
							));
							if (in_array($ticket['Ticket']['status'], array('pending', 'approved', 'in progress'))) {
								echo $form->input('resolution', array(
									'label'=> __('Reason', true),
									'empty' => true
								));
							}
						}
					?>
					</fieldset>

					<?php
						echo $form->input('id');
						echo $form->textarea('comment');
					?>

				</fieldset>


				<div class="comments help">
					<?php echo $this->element('markdown_help', array('short' => true)); ?>
				</div>


				<div class="submit">
					<input type="submit" value="<?php __('Submit') ?>">
				</div>

			<?php echo $form->end();?>

		<?php endif; ?>
	</div>

</div>