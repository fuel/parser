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

	protected static $loaded_files = array();

	public static function _init()
	{
		\Config::load('view', true);
	}

	public static function factory($file = null, array $data = null, $auto_encode = null)
	{
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		$class     = \Config::get('view.extensions.'.$extension, get_called_class());
		$file      = pathinfo($file, PATHINFO_DIRNAME).DS.pathinfo($file, PATHINFO_FILENAME);

		if (is_array($class))
		{
			$class['extension'] and $extension = $class['extension'];
			$class = $class['class'];
		}

		$view = new $class($file, $data, $auto_encode);
		$extension and $view->extension = $extension;
		method_exists($view, 'init') and $view->init();
	}

	public $extension = 'php';

	public function set_filename($file)
	{
		if (($path = \Fuel::find_file('views', $file, '.'.$this->extension, false, false)) === false)
		{
			throw new \View_Exception('The requested view could not be found: '.\Fuel::clean_path($file));
		}

		// Store the file path locally
		$this->_file = $path;

		return $this;
	}
}

// end of file view.php