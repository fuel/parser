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

class View_SimpleTags extends \View {

	protected static $_parser;

	protected function process_file($file_override = false)
	{
		$file = $file_override ?: $this->file_name;
		$data = $this->get_data();

		try
		{
			// Load the view within the current scope
			$output = static::parser()->parse(file_get_contents($file), $data);
			return $output['content'];
		}
		catch (\Exception $e)
		{
			// Delete the output buffer & re-throw the exception
			ob_end_clean();
			throw $e;
		}
	}

	public $extension = 'stags';

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
}

// end of file simpletags.php