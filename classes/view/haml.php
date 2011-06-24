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

use HamlParser;

class View_Haml extends \View {

	protected static $_parser;
  protected static $_cache;

	protected static function capture($view_filename, array $view_data)
	{
	
    static::cache_init($view_filename);
    $file = static::parser()->parse($view_filename, static::$_cache);
		return parent::capture($file, $view_data);

	}

	public $extension = 'haml';

	public function parser()
	{
		if ( ! empty(static::$_parser))
		{
			return static::$_parser;
		}

		static::$_parser = new HamlParser();

		return static::$_parser;
	}
  
	// Jade stores cached templates as the filename in plain text,
	// so there is a high chance of name collisions (ex: index.jade).
	// This function attempts to create a unique directory for each
	// compiled template.
	// TODO: Extend Jade's caching class?
	public function cache_init($file_path)
	{
  
		$cache_key = md5($file_path);
		$cache_path = \Config::get('parser.View_Haml.cache_dir', null)
			.substr($cache_key, 0, 2).DS.substr($cache_key, 2, 2);

		if ($cache_path !== null AND ! is_dir($cache_path))
		{
			mkdir($cache_path, 0777, true);
		}

		static::$_cache = $cache_path;
	}  
  
}

/* end of file haml.php */