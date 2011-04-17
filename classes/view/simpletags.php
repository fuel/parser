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

class View_SimpleTags extends View {

	protected static $_parser;

	public static function parser()
	{
		if ( ! empty(static::$_parser))
		{
			return static::$_parser;
		}

		static::$_parser = new \Simpletags();
		static::$_parser->set_delimiters(
			\Config::get('parser.View_SimpleTags.delimiters.0', '{'),
			\Config::get('parser.View_SimpleTags.delimiters.1', '}')
		);
		static::$_parser->set_trigger(\Config::get('parser.View_SimpleTags.trigger', 'tag:'));

		return static::$_parser;
	}

	protected static function capture($view_filename, array $view_data)
	{
		$data = static::$_global_data;
		$data = array_merge($data, $view_data);

		try
		{
			// Load the view within the current scope
			$output = static::parser()->parse(file_get_contents($view_filename), $data);
			return $output['content'];
		}
		catch (\Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}
	}

	public $extension = 'stags';
}

// end of file simpletags.php