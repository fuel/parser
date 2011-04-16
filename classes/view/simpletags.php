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

	public $extension = 'stags';

	protected static function capture($view_filename, array $view_data)
	{
		$data = static::$_global_data;
		$data = array_merge($data, $view_data);

		try
		{
			// Load the view within the current scope
			$simpletags = new \Simpletags();
			$simpletags->set_delimiters(
				\Config::get('parser.simpletags.delimiters.0', '{'),
				\Config::get('parser.simpletags.delimiters.1', '}')
			);
			$simpletags->set_trigger(\Config::get('parser.simpletags.trigger', 'tag:'));
			$output = $simpletags->parse($view_filename, $data);

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

	public static function init()
	{
		static $loaded = false;

		if ($loaded === false)
		{
			include \Config::get('parser.simpletags.include');
			$loaded = true;
		}
	}
}

// end of file simpletags.php