<?php

Router::connect('/admin/status', array('plugin' => 'status', 'admin' => true, 'prefix' => 'admin', 'controller' => 'status', 'action' => 'index'));
Router::connect('/admin/status/:action/*', array('plugin' => 'status', 'admin' => true, 'prefix' => 'admin', 'controller' => 'status'));



?>