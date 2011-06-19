<?php

return array(

	// ------------------------------------------------------------------------
	// Register extensions to their parsers, either classname or array config
	// ------------------------------------------------------------------------
	'extensions' => array(
		'php'       => 'View',
		'twig'      => 'View_Twig',
		'mustache'  => 'View_Mustache',
		'stags'     => 'View_SimpleTags',
		'dwoo'      => array('class' => 'View_Dwoo', 'extension' => '.tpl'),
	),


	// ------------------------------------------------------------------------
	// Individual class config by classname
	// ------------------------------------------------------------------------


	// SIMPLETAGS ( https://bitbucket.org/dhorrigan/simpletags/overview )
	// ------------------------------------------------------------------------
	'View_SimpleTags' => array(
		'include' => PKGPATH.'parser'.DS.'vendor'.DS.'simpletags.php',
		'delimiters' => array('{', '}'),
		'trigger' => 'tag:',
	),


	// TWIG ( http://www.twig-project.org/documentation )
	// ------------------------------------------------------------------------
	'View_Twig' => array(
		'include' => APPPATH.'vendor'.DS.'Twig'.DS.'Autoloader.php',
		'views_paths' => array(APPPATH.'views'),
		'delimiters' => array(
			'tag_block'     => array('{%', '%}'),
			'tag_comment'   => array('{#', '#}'),
			'tag_variable'  => array('{{', '}}'),
		),
		'environment' => array(
			'debug'                => false,
			'charset'              => 'utf-8',
			'base_template_class'  => 'Twig_Template',
			'cache'                => APPPATH.'cache'.DS.'twig'.DS,
			'auto_reload'          => true,
			'strict_variables'     => false,
			'autoescape'           => false,
			'optimizations'        => -1,
		),
	),

	// DWOO ( http://wiki.dwoo.org/ )
	// ------------------------------------------------------------------------
	'View_Dwoo' => array(
		'include' => APPPATH.'vendor'.DS.'Dwoo'.DS.'dwooAutoload.php',
		'delimiters' => array('{{', '}}'),
		'environment' => array(
			'autoescape'       => false,
			'nested_comments'  => false,
			'allow_spaces'     => false,
			'cache_dir'        => APPPATH.'cache'.DS.'dwoo'.DS,
			'compile_dir'      => APPPATH.'cache'.DS.'dwoo'.DS.'compiled'.DS,
			'cache_time'       => 0,
			
			// Set what parser should do with PHP tags
			// 1 - Encode tags | 2 - Remove tags | 3 - Allow tags
			'allow_php_tags'   => 2,
			
			// Which PHP functions should be accessible through Parser
			'allow_php_func'   => array(),
		),	
	),

	// MUSTACHE ( https://github.com/bobthecow/mustache.php )
	// ------------------------------------------------------------------------
	'View_Mustache' => array(
		'include' => APPPATH.'vendor'.DS.'Mustache'.DS.'Mustache.php',
		'delimiters' => array('{{', '}}'),
		'environment' => array(
			'charset' => 'UTF-8',
			'pragmas' => array(),
		),
	),
);

// end of file parser.php