<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2015 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Parser;

class View_Lex extends \View
{
	protected static $_parser;

	public static function parser()
	{
		if ( ! empty(static::$_parser))
		{
			return static::$_parser;
		}

		static::$_parser = new \Lex\Parser();

		return static::$_parser;
	}

	public static function injectNoparse($template)
	{
		\Lex\Parser::injectNoparse($template);
	}

	public $extension = 'lex';

	protected $callback = false;

	public function setCallback($callback = false)
	{
		$this->callback = $callback;

		return $this;
	}

	protected function process_file($file_override = false)
	{
		$file = $file_override ?: $this->file_name;

		try
		{
			static::parser()->scopeGlue(\Config::get('parser.View_Lex.scope_glue', '.'));
			return static::parser()->parse(file_get_contents($file), $this->get_data(), $this->callback, \Config::get('parser.View_Lex.allow_php', false));
		}
		catch (\Exception $e)
		{
			// Delete the output buffer & re-throw the exception
			ob_end_clean();
			throw $e;
		}
	}
}
