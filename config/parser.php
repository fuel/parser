<?php

return array(

	// Register extensions to their parsers, either classname or array config
	'extensions' => array(
		'php'       => 'View',
		'stags'     => 'View_SimpleTags',
		'twig'      => 'View_Twig',
		'dwoo'      => array('class' => 'View_Dwoo',     'extension' => '.tpl'),
		'mustache'  => 'View_Mustache',
	),

	// Individual class config by classname

	'View_SimpleTags' => array(
		'include'     => PKGPATH.'parser'.DS.'vendor'.DS.'simpletags.php',
		'delimiters'  => array('<<', '>>'),
		'trigger'     => 'tag:',
	),

	'View_Twig' => array(
		'include'     => APPPATH.'twig'.DS.'Autoloader.php',
	),

	'View_Dwoo' => array(
		'include'     => APPPATH.'dwoo'.DS.'dwooAutoload.php',
	),

	'View_Mustache' => array(
		'include'     => APPPATH.'mustache'.DS.'Mustache.php',
	),
);

// end of file parser.php