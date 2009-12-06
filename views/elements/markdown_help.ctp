<h4 id="markdown_syntax">
	<?php __('Markdown Help') ?>
	<?php //echo $html->link(__('Markdown Help',true)); ?>
</h4>
<div class="markdown">
	<p>
		<?php __('ticket') ?>: #1234
		<br />
		<?php __('commit') ?>: [1234]
	</p>
	<p>
		```<code><?php __('inline code') ?></code>```
		<br />
<pre><code>{{{
 'three brackets';
 'with code inside';
}}}</code></pre>
	</p>
	<p>
		# <?php __('header 1') ?>
		<br />
		## <?php __('header 2') ?>'
	</p>
	<p>
		<em>*<?php __('italic') ?>*</em>
		<em>or</em>
		<em>_<?php __('italic')?>_</em>
		<br />
		<strong>**<?php __('bold')?>**</strong>
		<em>or</em>
		<strong>__<?php __('bold')?>__</strong>
	</p>
	<p>
		- <?php __('unordered list 1')?>
		<br />
		1. <?php __('unordered list 2')?>
	</p>
<?php if (empty($short)):?>
	<p>
		[wiki:page The Page]
		<br />
		[a link](http://url.com/)
	</p>
	<p>ohloh widgets: [ohloh:project/widget_name]</p>
<?php endif;?>

</div>