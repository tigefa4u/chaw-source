<h2>Welcome to Chaw Installation</h2>
<?php
if (Configure::read() > 0):
	Debugger::checkSessionKey();
endif;
?>
<p>
	<?php
		$paths = Configure::read('Content');
		foreach ($paths as $type => $path) {
			new Folder($path, true, 0775);
			if (is_writable($path)):
				echo '<span class="notice success">';
					echo sprintf(__('Your %s directory is writable.', true), $type);
				echo '</span>';
			else:
				echo '<span class="notice">';
					echo sprintf(__('please make APP/%s writable.', true), str_replace(APP, "", $path));
				echo '</span>';
			endif;
		}
	?>
</p>
<p>
	<?php
		if (is_writable(TMP)):
			echo '<span class="notice success">';
				__('Your tmp directory is writable.');
			echo '</span>';
		else:
			echo '<span class="notice">';
				__('Your tmp directory is NOT writable.');
			echo '</span>';
		endif;
	?>
</p>
<p>
	<?php
		$settings = Cache::settings();
		if (!empty($settings)):
			echo '<span class="notice success">';
					echo sprintf(__('The %s is being used for caching. To change the config edit APP/config/core.php ', true), '<em>'. $settings['engine'] . 'Engine</em>');
			echo '</span>';
		else:
			echo '<span class="notice">';
					__('Your cache is NOT working. Please check the settings in APP/config/core.php');
			echo '</span>';
		endif;
	?>
</p>
<p>
	<?php
		$filePresent = null;
		if (file_exists(CONFIGS.'database.php')):
			echo '<span class="notice success">';
				__('Your database configuration file is present.');
				$filePresent = true;
			echo '</span>';
		else:
			echo '<span class="notice">';
				__('Your database configuration file is NOT present.');
				echo '<br/>';
				__('Rename config/database.php.default to config/database.php');
			echo '</span>';
		endif;
	?>
</p>
<?php
if (isset($filePresent)):
	uses('model' . DS . 'connection_manager');
	$db = ConnectionManager::getInstance();
	@$connected = $db->getDataSource('default');
?>
<p>
	<?php
		if ($connected->isConnected()):
			echo '<span class="notice success">';
	 			__('Cake is able to connect to the database.');
			echo '</span>';
		else:
			echo '<span class="notice">';
				__('Cake is NOT able to connect to the database.');
			echo '</span>';
		endif;
	?>
</p>
<?php endif;?>
<h3><?php __('Editing this Page'); ?></h3>
<p>
<?php
__('To change the content of this page, edit: APP/views/pages/home.ctp.<br />
To change its layout, edit: APP/views/layouts/default.ctp.<br />
You can also add some CSS styles for your pages at: APP/webroot/css.');
?>
</p>

<p style="margin-top: 2em">
	<?php echo $html->link('Continue Installation', array('admin'=> true, 'controller' => 'projects', 'action' => 'add'))?>
</p>