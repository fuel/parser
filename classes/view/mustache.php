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

class View_Mustache extends \View {

	protected static $_parser;

	protected static function capture($view_filename, array $view_data)
	{
		$data = static::$_global_data;
		$data = array_merge($data, $view_data);

		try
		{
			return static::parser()->render(file_get_contents($view_filename), $data);
		}
		catch (\Exception $e)
		{
			ob_end_clean();	// Delete the output buffer
			throw $e;		// Re-throw the exception
		}
	}

	public $extension = 'mustache';

	public function parser()
	{
		if ( ! empty(static::$_parser))
		{
			return static::$_parser;
		}

		static::$_parser = new \Mustache();

		return static::$_parser;
	}
}

// end of file dwoo.php