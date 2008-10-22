<?php
class Browser extends Object {
	
	var $useTable = false;

	/* path to files */
	var $config = array('path' => null, 'type' => null, 'working' => null);

	function read($path = null) {
		$data = null;

		if ($this->config['working'] == null) {
			return false;
		}

		$this->config['working'] = Folder::slashTerm($this->config['working']);

		$wwwPath = join('/', explode(DS, $path)) . '/';

		$Folder = new Folder($this->config['working'] . $path);

		list($dirs, $files) = $Folder->read(true, array('.svn'));

		$dir = $file = array();

		$path = $Folder->slashTerm($Folder->pwd());
		
		$Repo = ClassRegistry::init($this->config['type']);

		$Repo->config(array(
			'repo' => $this->config['path'],
			'working' => $this->config['working']
		));
	
		if ($path === $this->config['working']) {
			$Repo->update();
		}

		$count = count($dirs);
		for ($i = 0; $i < $count; $i++) {
			$dir[$i]['name'] = $dirs[$i];
			$dir[$i]['path'] = $wwwPath . $dirs[$i];
			$dir[$i]['md5'] = null;
			$dir[$i]['size'] = $this->__size($path . $dirs[$i]);
			$dir[$i]['icon'] = '/icons/dir.gif';
			$dir[$i]['info'] = $Repo->pathInfo($path . $dirs[$i]);
		}

		$count = count($files);
		for ($i = 0; $i < $count; $i++) {
			$file[$i]['name'] = $files[$i];
			$file[$i]['size'] = $this->__size($path . $files[$i]);
			$file[$i]['icon'] = $this->__icon($files[$i]);
			$file[$i]['path'] = $wwwPath . $files[$i];
			$file[$i]['md5'] = md5($Folder->pwd() . $files[$i]);
			$file[$i]['info'] = $Repo->pathInfo($path . $files[$i]);
		}

		return array('Folder' => $dir, 'File' => $file);
	}

	function __gitInfo($name) {
		$Git = ClassRegistry::init('Git');

		$Git->config(array(
			'repo' => $this->config['path'],
			'working' => $this->config['working']
		));

		$path = str_replace($this->config['working'], '', $name);

		return $Git->pathInfo($path);
	}

	function __svnInfo($name) {
		$Svn = ClassRegistry::init('Svn');
		return $Svn->pathInfo($name);
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