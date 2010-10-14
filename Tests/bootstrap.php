<?php

preg_match('/\/jggarcia\/(.*?)\//im',realpath(__FILE__),$matches);

set_include_path("/home/jggarcia/".$matches[1]."/library/"
	.PATH_SEPARATOR.get_include_path());


require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('My_');

/* ini_set('display_errors',0);
ini_set('display_startup_errors',0);
 *///ini_set('error_reporting',null);
