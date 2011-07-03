<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     b3ha
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Parser;

class View_Smarty extends \View {

	protected static $_parser;

	protected static function capture($view_filename, array $view_data)
	{
		$data = static::$_global_data;
		$data = array_merge($data, $view_data);

		try
		{
			return static::parser()->fetch($view_filename, $data);
		}
		catch (\Exception $e)
		{
			// Delete the output buffer & re-throw the exception
			ob_end_clean();
			throw $e;
		}
	}

	public $extension = 'tpl';

	public function parser()
	{
		if ( ! empty(static::$_parser))
		{
			return static::$_parser;
		}

		// Parser
		static::$_parser = new \Smarty();
		static::$_parser->compile_dir       = \Config::get('parser.View_Smarty.environment.compile_dir', APPPATH.'tmp'.DS.'Smarty'.DS.'templates_c'.DS);
		static::$_parser->config_dir        = \Config::get('parser.View_Smarty.environment.config_dir', APPPATH.'tmp'.DS.'Smarty'.DS.'configs'.DS);
		static::$_parser->cache_dir         = \Config::get('parser.View_Smarty.environment.cache_dir', APPPATH.'cache'.DS.'Smarty'.DS);

		static::$_parser->caching           = \Config::get('parser.View_Smarty.environment.caching', false);
		static::$_parser->cache_lifetime    = \Config::get('parser.View_Smarty.environment.cache_lifetime', 0);
		static::$_parser->force_compile     = \Config::get('parser.View_Smarty.environment.force_compile', false);
		static::$_parser->compile_check     = \Config::get('parser.View_Smarty.environment.compile_check', true);
		static::$_parser->debugging         = \Config::get('parser.View_Smarty.environment.debugging', false);

		static::$_parser->left_delimiter    = \Config::get('parser.View_Smarty.delimiters.0', '{');
		static::$_parser->right_delimiter   = \Config::get('parser.View_Smarty.delimiters.1', '}');

		static::$_parser->autoload_filters  = \Config::get('parser.View_Smarty.environment.autoload_filters', array());
		static::$_parser->default_modifiers = \Config::get('parser.View_Smarty.environment.default_modifiers', array());

		return static::$_parser;
	}
}

// end of file smarty.php
