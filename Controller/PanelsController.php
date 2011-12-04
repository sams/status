<?php
/*
 * App Status Planel CakePHP Plugin
 * Copyright (c) 2009 Matt Curry
 * www.PseudoCoder.com
 * http://github.com/mcurry/status
 *
 * @author      Matt Curry <matt@pseudocoder.com>
 * @license     MIT
 *
 */
 
class PanelsController extends StatusAppController {
	var $name = 'Panels';
	var $uses = array();
	var $helpers = array('Javascript', 'Time');
	var $components = array('RequestHandler');

	function system() {
		$free = disk_free_space("/");
		$total = disk_total_space("/");
		if($free !== false && $total !== false) {
  		$perc = round(($free / $total * 100), 2);
  		$disk = array('free' => $this->__diskHumanize($free),
  									'total' => $this->__diskHumanize($total),
  									'perc' => $perc);
    } else {
      $disk = false;
    }

		$uptime = exec('uptime');

		return compact('disk', 'uptime');
	}

	function shell() {
		$this->loadModel('Status.StatusConsole');
		$this->paginate = array('order' => array('StatusConsole.created' => 'DESC'),
														'limit' => 10);
		return $this->paginate('StatusConsole');
	}

	function logs() {
		$logfile = $this->request->params['log'];
		if (strpos($logfile, '.') === false) {
			$logfile .= '.log';
		}

		$filename = LOGS . $logfile;
		if (!file_exists($filename)) {
			return;
		}

		return $this->_parseFile($filename);
	}

	function google_analytics($type, $span=1) {
		$this->loadModel('Status.GoogleAnalytics');

		$data = $this->GoogleAnalytics->load($type, array('span' => $span));
		$this->set(compact('type', 'data', 'span'));
	}
	
	function tests() {
		$path = TESTS . 'cases';
		$Folder = new Folder($path);
		$cases = $Folder->findRecursive('.*\.test\.php');
		foreach($cases as $i => $case) {
			$case = str_replace($path, '', $case);
			$case = str_replace('.test.php', '', $case);
			$cases[$i] = trim($case, DS);
		}
		
		return array('cases' => $cases);
	}
	
	function tests_run() {
		if(!empty($this->request->params['url']['case'])) {
			$case = $this->request->params['url']['case'];
			
			if(stripos($_ENV['OS'], 'windows') !== false) {
				$cmd = 'cake.bat';
			} else {
				$cmd = 'cake';
			}
			
			$cmd = ROOT.DS.CAKE.'console'.DS.$cmd.' -app '.APP.' testsuite app case ' . $case;
			exec($cmd, $results);
			
			$this->set('case', $case);
			$this->set('summary', $results[count($results) - 2]);
			$this->set('results', $this->__parseTestResult($results));
		}
	}
	
	function __parseTestResult($results) {
		if(count($results) == 13) {
			return false;
		}
		
		return array_slice($results, 11, -2);
	}
	
/**
 * I stole the regex part for parsing CakePHP log files from Mark Story's awesome DebugKit
 * http://thechaw.com/debug_kit/
 */
	function _parseFile($filename) {
		$file =& new File($filename);
		$contents = $file->read();
		$timePattern = '/(\d{4}-\d{2}\-\d{2}\s\d{1,2}\:\d{1,2}\:\d{1,2})/';
		$chunks = preg_split($timePattern, $contents, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

		$chunks = array_values(array_slice($chunks, -20));

		$return = array();
		$time = null;
		foreach($chunks as $i => $chunk) {
			if ($i % 2 == 0) {
				$time = $chunk;
			} else {
				$return[] = array('time' => $time,
													'entry' => $chunk);
			}
		}

		return array_reverse($return);
	}

	function __diskHumanize($size) {
		$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 Bytes';
	}
}