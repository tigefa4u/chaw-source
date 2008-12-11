<h4>
	<?php echo $html->link('Markdown Help', '/markdown_syntax', array('target' => '_blank')); ?>
</h4>
<div class="markdown">
	<br />
	ticket: #1234
	<br />
	commit: [1234]
	<br />
	<br />
	```<code>inline code</code>```
	<br />
<pre><code>{{{
  three brackets
  with code inside
}}}</code></pre>
	<br />
	# header 1
	<br />
	## header 2
	<br />
	<br />
	<em>*italic*</em>
	<em>or</em>
	<em>_italic_</em>
	<br />
	<strong>**bold**</strong>
	<em>or</em>
	<strong>__bold__</strong>
	<br />
	<br />
	- unordered list 1
	<br />
	1. ordered list 2
<?php if (empty($short)):?>
	<br />
	<br />
	[wiki:<?php echo $CurrentProject->url?>/page The Page]
	<br />
	[link](http://url.com/)
	<br />
	<br />
	ohloh widgets: [ohloh:project/widget_name]
<?php endif;?>

</div>