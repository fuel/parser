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

namespace Parser;

class View extends \Fuel\Core\View {

	public static function _init()
	{
		\Config::load('parser', true);
	}

	public static function factory($file = null, array $data = null, $auto_encode = null)
	{
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		$class     = \Config::get('parser.extensions.'.$extension, get_called_class());
		$file      = pathinfo($file, PATHINFO_DIRNAME).DS.pathinfo($file, PATHINFO_FILENAME);

		// Class can be an array config
		if (is_array($class))
		{
			$class['extension'] and $extension = $class['extension'];
			$class = $class['class'];
		}

		$view = new $class($file, $data, $auto_encode);

		// Set extension when given
		$extension and $view->extension = $extension;

		// Include necessary files
		foreach ((array) \Config::get('parser.'.$class.'.include', array()) as $include)
		{
			include_once $include;
		}

		return $view;
	}
}

// end of file view.php