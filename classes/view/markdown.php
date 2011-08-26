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

class View_Markdown extends \View {

	protected static $_parser;

	protected static function capture($view_filename, array $view_data = array())
	{
		$contents = '';

		if (\Config::get('parser.View_Markdown.allow_php', false))
		{
			$contents = static::pre_process('php', $view_filename, $view_data);
		}
		else
		{
			$contents = file_get_contents($view_filename);
		}
		
		return static::parser()->transform($contents);
	}
	
	protected static function pre_process($_type = 'php', $_view_filename, array $_data = array())
	{
		if ($_type == 'php')
		{
			// Import the view variables to local namespace
			$_data AND extract($_data, EXTR_SKIP);

			if (static::$_global_data)
			{
				// Import the global view variables to local namespace and maintain references
				extract(static::$_global_data, EXTR_REFS);
			}

			// Capture the view output
			ob_start();

			try
			{
				// Load the view within the current scope
				include $_view_filename;
			}
			catch (\Exception $e)
			{
				// Delete the output buffer
				ob_end_clean();

				// Re-throw the exception
				throw $e;
			}

			// Get the captured output and close the buffer
			return ob_get_clean();
		}
	}

	public $extension = 'md';

	public static function parser()
	{
		static $parser = null;
		if (is_null($parser))
		{
			$parser_class = \MARKDOWN_PARSER_CLASS;
			$parser = new $parser_class;
		}

		return $parser;
	}
}

// end of file mustache.php