<h4 id="markdown_syntax">
	<?php __('Markdown Help') ?>
	<?php //echo $html->link(__('Markdown Help',true)); ?>
</h4>
<div class="markdown">
	<?php __('ticket') ?>: #1234
	<br />
	<?php __('commit') ?>: [1234]
	<br />
	<br />
	```<code><?php __('inline code') ?></code>```
	<br />
<pre><code>{{{
  three brackets
  with code inside
}}}</code></pre>
	<br />
	# <?php __('header 1') ?>
	<br />
	## <?php __('header 2') ?>'
	<br />
	<br />
	<em>*<?php __('italic') ?>*</em>
	<em>or</em>
	<em>_<?php __('italic')?>_</em>
	<br />
	<strong>**<?php __('bold')?>**</strong>
	<em>or</em>
	<strong>__<?php __('bold')?>__</strong>
	<br />
	<br />
	- <?php __('unordered list 1')?>
	<br />
	1. <?php __('unordered list 2')?>
<?php if (empty($short)):?>
	<br />
	<br />
	[wiki:page The Page]
	<br />
	[link](http://url.com/)
	<br />
	<br />
	ohloh widgets: [ohloh:project/widget_name]
<?php endif;?>

</div>