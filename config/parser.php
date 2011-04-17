<?php

return array(

	// Register extensions to their parsers, either classname or array config
	'extensions' => array(
		'php'     => 'View',
		'stags'   => 'View_SimpleTags',
		'dwoo'    => array('class' => 'View_Dwoo', 'extension' => '.tpl'),  // example, not yet implemented
	),

	// Individual class config by classname

	'View_SimpleTags' => array(
		'include'     => PKGPATH.'parser'.DS.'vendor'.DS.'simpletags.php',
		'delimiters'  => array('{', '}'),
		'trigger'     => 'tag:',
	),
);