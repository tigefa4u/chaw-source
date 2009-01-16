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

	var $useTable = false;

/**
 * undocumented function
 *
 * @return void
 *
 **/
	function read(&$Repo, $path) {
		$data = null;

		if (!is_dir($Repo->working)) {
			return false;
		}

		if (is_file($Repo->working . DS . $path)) {
			$File = new File($Repo->working . DS .$path);
			return array('Content' => $File->read());
		}

		$isRoot = false;
		$wwwPath = $base = join('/', explode(DS, $path)) . '/';

		$Folder = new Folder($Repo->working . DS . $path);

		$path = Folder::slashTerm($Folder->pwd());

		if ($Repo->type == 'git') {
			if (basename(dirname($Repo->working)) == 'working') {
				$isRoot = true;
			} else {
				$branch = basename($Repo->working);
				if ($branch != 'master') {
					$wwwPath = 'branches/' . $branch . '/'. $wwwPath;
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
				$Repo->working = $path . $dirs[$i];
				$here = $base . 'branches/' . $dirs[$i];
				if ($dirs[$i] == 'master') {
					$here = $base;
				}
			}
			$dir[$i]['path'] = $here;
			$dir[$i]['info'] = $Repo->pathInfo($lookup . DS);
			//$dir[$i]['md5'] = null;
			//$dir[$i]['size'] = $this->__size($path . $dirs[$i]);
			//$dir[$i]['icon'] = '/icons/dir.gif';
		}

		$count = count($files);
		for ($i = 0; $i < $count; $i++) {
			$file[$i]['name'] = $files[$i];
			$file[$i]['path'] = $wwwPath . $files[$i];
			$file[$i]['info'] = $Repo->pathInfo($path . $files[$i]);
			//$file[$i]['md5'] = md5($Folder->pwd() . $files[$i]);
			//$file[$i]['size'] = $this->__size($path . $files[$i]);
			//$file[$i]['icon'] = $this->__icon($files[$i]);
		}

		return array('Folder' => $dir, 'File' => $file);
	}

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