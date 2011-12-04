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
 
class StatusController extends StatusAppController {
	var $name = 'Status';
	var $uses = array();
	var $helpers = array('Javascript', 'Time');
	var $components = array('RequestHandler');

	function beforeFilter() {
		parent::beforeFilter();
	}
	
	function admin_index() {
		$panels = array();
		foreach(Configure::read('Status.panels') as $panel => $options) {

			if (is_numeric($panel)) {
				$panel = $options;
				$options = array();
			}

			if (empty($options) || !Set::numeric(array_keys($options))) {
				$options = array($options);
			}


			if (strpos($panel, '.') !== false) {
				list($plugin, $panel) = explode('.', $panel);
			} else {
				$plugin = false;
			}

			foreach($options as $option) {
				$panels[] = array('plugin' => $plugin,
													'element' => $panel,
													'options' => $option);
			}
		}

		$this->set('panels', $panels);
		$this->layout = 'status';
		$this->render('admin_index');
	}
        
	public function render($action = null, $layout = null, $file = null) {
		if (file_exists(APP . 'View' . DS . 'status' . DS . $this->request->action . '.ctp')) {
			$file = APP . 'View' . DS . DS. 'status' . DS . $this->request->action . '.ctp';
		}
		//$this->set('viewWas', APP . 'View' . DS . 'themed' . DS . 'admin' .   DS. 'status' . DS . $this->request->action . '.ctp');
		return parent::render($action, $layout, $file);
	}
}