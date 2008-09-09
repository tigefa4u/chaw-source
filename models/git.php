<?php
class Git extends AppModel {

	var $name = 'Git';

	var $useTable = false;

	var $__config = array('git' => '/opt/local/bin/git', 'tmp' => TMP, 'username' => '', 'password' => '');

	var $debug = array();

	var $workingCopy = null;

	var $repo = null;


	function create($project, $repo) {

	}

	function pull($branch, $params = array()) {
		umask(0);

		if (!is_dir($this->workingCopy)) {
			$this->run('clone', array_merge($params, array($this->repo, $this->workingCopy)));
		}

		if (is_dir($this->workingCopy)) {
			$c = $this->debug[] = "cd {$this->workingCopy}";
			shell_exec($c);
			$this->run('checkout', array($branch));
			$this->run('pull', array_merge($params, array($this->repo, $branch)));
			return true;
		}
		return false;
	}

	function diff() {

	}

	function info($branch, $params = null) {
		$params = '%h %an %ar %s';

		if ($params === null) {
			$params = array('--header', '--max-count=1', $branch);
		} else if (is_array($params)) {
			array_push($params, $branch);
		} else {
			$params = array("--pretty=format:'{$params}'", $branch);
		}

		$out = $this->sub('rev-list', $params);

		return $out;
	}

	function tree($branch, $params = array()) {
		if (empty($params)) {
			$params = array($branch, "| sed -e 's/\t/ /g'");
		} else {
			array_push($params, $branch);
		}
		$out = $this->sub('ls-tree', $params);

		if (empty($out[0])) {
			return false;
		}

		if (strpos(trim($out[0]), ' ') === false) {
			return $out;
		}

		$result = array();

        foreach ($out as $line) {
            $entry = array();
            $arr = explode(" ", $line);
            $entry['perm'] = $arr[0];
            $entry['type'] = $arr[1];
            $entry['hash'] = $arr[2];
            $entry['file'] = $arr[3];
            $result[] = $entry;
        }
        return $result;
	}

	function run($command, $params = array()) {
		extract($this->__config);
		$c = $this->debug[] = trim("{$git} {$command} " . join(' ', $params));
		umask(0);
		return shell_exec($c);
	}

	function sub($command, $params = array()) {
		extract($this->__config);
		$c = $this->debug[] = trim("GIT_DIR={$this->repo} {$git}-{$command} " . join(' ', $params));
		umask(0);
		exec($c, $out);
		return $out;
	}

}
?>