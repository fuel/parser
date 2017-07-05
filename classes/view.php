<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2017 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Parser;

class View extends \Fuel\Core\View
{
	/**
	 * @var  array  Holds the list of loaded files.
	 */
	protected static $loaded_files = array();

	public static function _init()
	{
		// get and normalize the config
		$parser = \Config::load('parser', true);
		foreach (\Config::get('parser.extensions', array()) as $extension => $config)
		{
			if (isset($config['extension']))
			{
				unset($parser['extensions'][$extension]);
				$parser['extensions'][$config['extension']] = $config;
			}
		}
		\Config::set('parser', $parser);

		// Get class name
		$class = \Inflector::denamespace(get_called_class());

		if ($class !== __CLASS__)
		{
			// Include necessary files
			foreach ((array) \Config::get('parser.'.$class.'.include', array()) as $include)
			{
				if ( ! array_key_exists($include, static::$loaded_files))
				{
					require $include;
					static::$loaded_files[$include] = true;
				}
			}
		}
	}

	/**
	 * Forges a new View object based on the extension
	 *
	 * @param   string  $file         view filename
	 * @param   array   $data         view data
	 * @param   bool    $auto_encode  auto encode boolean, null for default
	 * @return  object  a new view instance
	 */
	public static function forge($file = null, $data = null, $auto_encode = null)
	{
		$class = null;
		$extension = 'php';

		// if a view file was given
		if ($file !== null)
		{
			// get its type and check if a parser extension is defined
			$extension = pathinfo($file, PATHINFO_EXTENSION);
			$class = \Config::get('parser.extensions.'.$extension, null);
		}

		// if no extension is defined, use the called class
		if ($class === null)
		{
			$class = get_called_class();
		}

		// class can also be an array config
		elseif (is_array($class))
		{
			$class['extension'] and $extension = $class['extension'];
			$class = $class['class'];
		}

		// if no auto-encode flag is given, get it from config
		if ($auto_encode === null)
		{
			$auto_encode = \Config::get('parser.'.$class.'.auto_encode', null);
		}

		// include necessary parser files
		foreach ((array) \Config::get('parser.'.$class.'.include', array()) as $include)
		{
			if ( ! array_key_exists($include, static::$loaded_files))
			{
				require $include;
				static::$loaded_files[$include] = true;
			}
		}

		// instantiate the Parser class without auto-loading the view file
		$view = new $class(null, $data, $auto_encode);

		// if we have a view file, set it
		if ($file)
		{
			$view->set_filename($file, true);
		}

		// and return the view object
		return $view;
	}
}
