<h2>Welcome to Chaw Installation</h2>
<?php
	$dbConfigExists = file_exists(CONFIGS . 'database.php');
	if (!$dbConfigExists):
		echo '<span class="notice">';
			__('Your database configuration file is NOT present.');
			echo '<br/>';
			__('Rename APP/config/database.php.default to APP/config/database.php');
		echo '</span>';
		return;
	else :
		uses('model' . DS . 'connection_manager');
		$db = ConnectionManager::getInstance();
		@$connected = $db->getDataSource('default');
		if(!$connected->isConnected()):
			echo '<span class="notice">';
				__('Chaw is NOT able to connect to the database.');
				echo '<br/>';
				__('Check that your database exists and the proper settings are in APP/config/database.php');
			echo '</span>';
			return;
		else :
			$Project = ClassRegistry::init('Project');
			if ($Project->find('first')) {
				echo $html->tag('h3', __('Finished', true));
				return;
			}
		endif;
	endif;

	$installReady = true;
?>
<p>
	<?php
		if (!is_writable(TMP)):
			$installReady = false;
			echo '<span class="notice">';
				__('Your tmp directory is NOT writable.');
			echo '</span>';
		endif;
	?>
</p>
<p>
	<?php
		$paths = Configure::read('Content');
		foreach ($paths as $type => $path) {
			$Folder = @new Folder($path, true, 0775);
			if (!is_writable($path)):
				$installReady = false;
				echo '<span class="notice">';
					echo sprintf(__('%s is NOT writable.', true), str_replace(APP, "APP/", $path));
				echo '</span>';
			endif;
		}
	?>
</p>
<?php
if ($installReady) :
	if (empty($CurrentUser->username)) :
		echo $html->tag('h3', $html->link(__('Register for an account',true), array('controller' => 'users', 'action' => 'add')));
	else:
		echo $html->tag('h3', $html->link(__('Create a Project',true), array('admin' => false, 'controller' => 'projects', 'action' => 'add')));
	endif;
endif;
?>