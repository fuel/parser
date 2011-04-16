<?php

return array(
	'extensions' => array(
		'php'     => 'View',
		'dwoo'    => array('class' => 'View_Dwoo', 'extension' => '.tpl'),
		'stags'   => 'View_SimpleTags',
		'twig'    => 'View_Twig',
		'smarty'  => array('class' => 'View_Smarty', 'extension' => '.tpl'),
	),

	'simpletags' => array(
		'include'     => PKGPATH.'parser'.DS.'vendor'.DS.'simpletags.php',
		'delimiters'  => array('{', '}'),
		'trigger'     => 'tag:',
	),
);