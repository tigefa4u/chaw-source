<?php
/**
 * Short description
 *
 * Long description
 *
 * Copyright 2008, Garrett J. Woodworth <gwoo@cakephp.org>
 * Redistributions not permitted
 *
 * @copyright		Copyright 2008, Garrett J. Woodworth
 * @package			chaw
 * @subpackage		chaw.models
 * @since			Chaw 0.1
 * @license			commercial
 *
 */
class Source extends Object {
/**
 * undocumented class variable
 *
 * @var string
 **/
	var $useTable = false;
/**
 * the current uri
 *
 * @var string
 **/
	var $Repo = null;
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function initialize(&$Repo, $args = array()) {
		$this->Repo =& $Repo;

		$path = join(DS, $args);
		if ($this->Repo->type == 'git') {
			if(empty($args) && !$this->Repo->branch) {
				$this->branches();
				$this->Repo->branch = null;
			}

			if (!empty($args)) {
				$branch = $args[0];
				$branches = $this->branches();
				if (in_array($branch, $branches)) {
					$branch = array_shift($args);
					$path = join(DS, $args);
					if (in_array($branch, $branches)) {
						$this->Repo->branch($branch, true);
						//$this->Repo->pull('origin', $branch);
					}
				}
			}

			if ($this->Repo->branch) {
				array_unshift($args, $this->Repo->branch);
			}
			array_unshift($args, 'branches');

			if (empty($path) && $this->Repo->branch) {
				//$this->Repo->update('origin', $this->Repo->branch);
			}
		}

		$current = null;
		if (count($args) > 0) {
			$current = array_pop($args);
 		}

		return array($args, $path, $current);
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function branches() {
		if ($this->Repo->type != 'git') {
			return array();
		}

		$config = $this->Repo->config;
		$this->Repo->branch('master', true);
		$this->Repo->update('origin');
		$branches = $this->Repo->find('branches');
		foreach ($branches as $branch) {
			if ($branch == 'master') {
				continue;
			}
			$this->Repo->branch($branch, true);
			$this->Repo->update('origin', $branch);
		}

		$this->Repo->config($config);
		return $branches;
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function rebase() {
		if ($this->Repo->working) {
			$path = dirname($this->Repo->working);
		}
		$Cleanup = new Folder($path);
		if ($Cleanup->pwd() == $path) {
			$Cleanup->delete();
		}
		return $this->Repo->pull();
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function read($path = null) {
		$data = null;

		if (!is_dir($this->Repo->working)) {
			return false;
		}

		if (is_file($this->Repo->working . DS . $path)) {
			$File = new File($this->Repo->working . DS .$path);
			return array('Content' => $File->read());
		}

		$isRoot = false;

		$wwwPath = $base = null;
		if ($path) {
			$wwwPath = $base = join('/', explode(DS, $path)) . '/';
		}

		$Folder = new Folder($this->Repo->working . DS . $path);
		$path = Folder::slashTerm($Folder->pwd());

		if ($this->Repo->type == 'git') {
			if ($this->Repo->branch == null) {
				$isRoot = true;
			} else {
				$branch = basename($this->Repo->working);
				if ($branch != 'master') {
					$wwwPath = 'branches/' . $branch . '/' . $base;
				}
			}
		}

		list($dirs, $files) = $Folder->read(true, array('.git', '.svn'));

		$dir = $file = array();
		$count = count($dirs);

		for ($i = 0; $i < $count; $i++) {
			$dir[$i]['name'] = $dirs[$i];
			$lookup = $path . $dirs[$i];
			$here = $wwwPath . $dirs[$i];
			if ($dirs[$i] == 'master') {
				$isRoot = true;
			}
			if ($isRoot) {
				$this->Repo->working = $path . $dirs[$i];
				$here = $base . 'branches/' . $dirs[$i];
				if ($dirs[$i] == 'master') {
					$here = $base;
				}
			}
			$dir[$i]['path'] = $here;
			$dir[$i]['info'] = $this->Repo->pathInfo($lookup . DS);
			//$dir[$i]['md5'] = null;
			//$dir[$i]['size'] = $this->__size($path . $dirs[$i]);
			//$dir[$i]['icon'] = '/icons/dir.gif';
		}

		$count = count($files);
		for ($i = 0; $i < $count; $i++) {
			$file[$i]['name'] = $files[$i];
			$file[$i]['path'] = $wwwPath . $files[$i];
			$file[$i]['info'] = $this->Repo->pathInfo($path . $files[$i]);
			//$file[$i]['md5'] = md5($Folder->pwd() . $files[$i]);
			//$file[$i]['size'] = $this->__size($path . $files[$i]);
			//$file[$i]['icon'] = $this->__icon($files[$i]);
		}

		return array('Folder' => $dir, 'File' => $file);
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function __size($file = null, $ext = 'B', $size = '0') {
		$size_ext = array('','K','M','G','T');

		if (!file_exists($file)) {
			return 0;
		}

		$size = filesize($file);

		if ($size > 0) {
			$div = 0;
			while ($size >= pow(1024,$div)) $div++;
			return array('num' => number_format(($size/pow(1024, $div-1)), 1, ". ", ".") , 'ext' => $size_ext[$div-1] . $ext);
		} else {
			return array('num' => 0,'ext' => '');
		}
	}
/**
 * undocumented function
 *
 * @return void
 *
 **/
	function __icon($file) {
		$array = explode('.', $file);
		$ext = '';
		$partCount = count($array);

		if ($partCount == 1) {
			$ext = '^^BLANKICON^^';
		} else {
			$ext = array_pop($array);
		}

		$exts = array("bin" => "/icons/binary.gif",
					"hqx" => "/icons/binhex.gif",
					"tar" => "/icons/tar.gif",
					"wrl" => "/icons/world2.gif",
					"Z" => "/icons/compressed.gif",
					"gz" => "/icons/compressed.gif",
					"zip" => "/icons/compressed.gif",
					"bz2" => "/icons/compressed.gif",
					"rar" => "/icons/compressed.gif",
					"ace" => "/icons/compressed.gif",
					"ps" => "/icons/ps.gif",
					"pdf" => "/icons/ps.gif",
					"html" => "/icons/layout.gif",
					"txt" => "/icons/text.gif",
					"c" => "/icons/c.gif",
					"cpp" => "/icons/small/c.gif",
					"pl" => "/icons/p.gif",
					"php" => "/icons/p.gif",
					"php3" => "/icons/p.gif",
					"php4" => "/icons/p.gif",
					"php5" => "/icons/p.gif",
					"for" => "/icons/f.gif",
					"dvi" => "/icons/dvi.gif",
					"uu" => "/icons/uuencoded.gif",
					"conf" => "/icons/script.gif",
					"tex" => "/icons/tex.gif",
					"core" => "/icons/bomb.gif",
					"^^BLANKICON^^" => "/icons/blank.gif",
					"^^UNKOWN^^" => "/icons/unknown.gif");

		if (isset($exts[$ext])) {
			return $exts[ $ext ];
		} else {
			return $exts['^^UNKOWN^^'];
		}
	}
}
?>