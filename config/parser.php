<?php

return array(
    // Register extensions to their parsers, either classname or array config
    'extensions' => array(
        'php' => 'View',
        'stags' => 'View_SimpleTags',
        'twig' => 'View_Twig',
        'dwoo' => array('class' => 'View_Dwoo', 'extension' => '.tpl'),
        'mustache' => 'View_Mustache',
    ),

    // Individual class config by classname
    'View_SimpleTags' => array(
        'include' => PKGPATH . 'parser' . DS . 'vendor' . DS . 'simpletags.php',
        'delimiters' => array('<<', '>>'),
        'trigger' => 'tag:',
    ),
    
    'View_Twig' => array(
        'include' => APPPATH . 'vendor' . DS . 'Twig' . DS . 'Autoloader.php',
        'views_paths' => array(APPPATH . 'views'),
        'environment' => array(
            'debug'                 => false,
            'charset'               => 'utf-8',
            'base_template_class'   => 'Twig_Template',
            'cache'                 => false,	// APPPATH . 'cache' . DS . 'Twig' . DS,
            'auto_reload'           => true,
            'strict_variables'      => false,
            'autoescape'            => false,
            'optimizations'         => -1,
        ),
        'delimiters' => array(
            'tag_block' => array('{%', '%}'),
            'tag_comment' => array('{#', '#}'),
            'tag_variable' => array('{{', '}}'),
        ),
    ),
    
    'View_Dwoo' => array(
        'include' => APPPATH . 'vendor' . DS . 'dwoo' . DS . 'dwooAutoload.php',
    ),
    
    'View_Mustache' => array(
        'include' => APPPATH . 'vendor' . DS . 'mustache' . DS . 'Mustache.php',
    ),
);

// end of file parser.php