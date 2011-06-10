<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

Autoloader::add_core_namespace('Parser');

Autoloader::add_classes(array(
    'Parser\\View'             => __DIR__.'/classes/view.php',
    'Parser\\View_Dwoo'        => __DIR__.'/classes/view/dwoo.php',
    'Parser\\View_Mustache'    => __DIR__.'/classes/view/mustache.php',
    'Parser\\View_SimpleTags'  => __DIR__.'/classes/view/simpletags.php',
    'Parser\\View_Twig'        => __DIR__.'/classes/view/twig.php',
));

/* End of file bootstrap.php */