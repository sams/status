<?php


if(!Configure::write('Status.panels')) 
		Configure::write('Status.panels', array('Status.google_analytics' => array('visits', 'referrers', 'keywords'),
							'Status.system',
							'Status.shell',
							'Status.tests',
							'Status.logs' => array('error', 'debug'),
						 ));



?>