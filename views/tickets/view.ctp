<?php
$this->set('showdown', true);
$html->css('highlight/idea', null, array('inline' => false));
$html->script('highlight.pack', array('inline' => false));

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
		$("fieldset.comments > legend").after($("fieldset.prop"));

	});
});
';
$html->scriptBlock($script, array('inline' => false));

$canEdit = !empty($canUpdate) || (!empty($CurrentUser->id) && $CurrentUser->id == $ticket['Reporter']['id']);

if ($this->Session->check('Ticket.back')) {
	echo $html->tag('div', $html->link('back', $this->Session->read('Ticket.back')), array('class' => 'page-navigation'));
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
			<?php echo $time->timeAgoInWords($ticket['Ticket']['created'], 'm.d.y');?>
		</span>

		<span class="reporter">
			<strong><?php __('reported by') ?>:</strong> <?php echo $ticket['Reporter']['username'];?>
		</span>

		<?php if (!empty($ticket['Owner']['username'])):?>
			<span class="owner">
				<strong><?php __('owned by') ?>:</strong> <?php echo $ticket['Owner']['username'];?>
			</span>
		<?php endif;?>

	</div>

	<div class="edit">

		<?php if (!empty($CurrentUser->id)): ?>

			<?php echo $form->create(array('action' => 'modify'));?>

				<?php if (!empty($canEdit)):?>
					<div id="modify" style="display:none">
						<fieldset class="main">
							<legend>
								<?php __('Modify Ticket');?>
								<em>(<a href="#" class="close">close</a>)</em>
							</legend>
							<?php if ($ticket['Ticket']['status'] == 'closed'):?>
								<fieldset class="options">
									<?php
										if (!empty($owners)) {
											echo $form->input('owner', array('empty' => true));
										}
										echo $form->input('type');
										echo $form->input('priority');
										if (!empty($versions)) {
											echo $form->input('version_id');
										}
									?>
								</fieldset>
							<?php endif; ?>
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
						<?php
							if (!empty($comment['reason'])) {
								echo "<strong>({$comment['reason']})</strong>";
							}
						?>
						<span class="date">
							<?php echo $time->timeAgoInWords($comment['created'], 'm.d.y');?>
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
								echo $html->tag('div', h($comment['body']), array('class' => 'wiki-text'));

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
						<?php __('Update Ticket');?>
					</legend>
					
					<?php if ($ticket['Ticket']['status'] != 'closed'):?>
						<fieldset class="options prop">
							<?php
								if (!empty($owners)) {
									echo $form->input('owner', array('empty' => true));
								}
								echo $form->input('type');
								echo $form->input('priority');
								if (!empty($versions)) {
									echo $form->input('version_id');
								}
							?>
						</fieldset>
					<?php endif; ?>
					
					<fieldset class="options">
					<?php
						if ($ticket['Ticket']['status'] == 'closed') {
							echo $form->checkbox('event', array(
								'value' => 'reopen',
							));
							echo $form->label('event', __('reopen', true));
						} else if (!empty($canUpdate)) {
							echo $form->input('event', array(
								'label'=> __('Change status', true), 'empty' => true,
							));
							if (in_array($ticket['Ticket']['status'], array('pending', 'approved', 'in progress'))) {
								echo $form->input('resolution', array(
									'label'=> __('Or close as', true), 'empty' => true,
								));
							}
						}
					?>
					</fieldset>

					<?php
						echo $form->input('id');
						echo $form->hidden('number');
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