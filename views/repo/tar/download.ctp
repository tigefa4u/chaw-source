<?php

$this->layout = null;
Configure::write('debug', 0);
set_time_limit(0);

header('Content-type: application/x-tar');
header('Content-Disposition: attachment; filename="' . $project . '.tar"');

$project = escapeshellarg($project . '.tar');

$cmd = "cd {$working} && tar c . {$project}";

$fh = popen($cmd, 'r');
while (!feof($fh)) {
  	echo fread($fh, 8192);
}
pclose($fh);
?>