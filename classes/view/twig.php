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

class View_Twig extends View {

	public static function _init()
	{
		\Twig_Autoloader::register();
	}

	public $extension = 'stags';

	protected static function capture($view_filename, array $view_data)
	{
		$data = static::$_global_data;
		$data = array_merge($data, $view_data);

		try
		{
			$loader = new Twig_Loader_String();
			$twig = new Twig_Environment($loader);
			$template = $twig->loadTemplate(file_get_contents($view_filename));

			return $template->render($data);
		}
		catch (\Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}
	}
}

// end of file twig.php