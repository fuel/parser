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

use Everzet\Jade\Jade;
use Everzet\Jade\Parser;
use Everzet\Jade\Lexer\Lexer;
use Everzet\Jade\Dumper\PHPDumper;
use Everzet\Jade\Visitor\AutotagsVisitor;
use Everzet\Jade\Filter\JavaScriptFilter;
use Everzet\Jade\Filter\CDATAFilter;
use Everzet\Jade\Filter\PHPFilter;
use Everzet\Jade\Filter\CSSFilter;

class View_Jade extends \View {

	protected static $_jade;

	protected static function capture($view_filename, array $view_data)
	{
		// Import the view variables to local namespace
		$view_data AND extract($view_data, EXTR_SKIP);

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
      eval('?>'.static::parser()->render($view_filename));
		}
		catch (Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
	}

	public $extension = 'jade';

	public function parser()
	{
		if ( ! empty(static::$_parser))
		{
			return static::$_parser;
		}

		$parser = new Parser(new Lexer());
		$dumper = new PHPDumper();
		$dumper->registerVisitor('tag', new AutotagsVisitor());
		$dumper->registerFilter('javascript', new JavaScriptFilter());
		$dumper->registerFilter('cdata', new CDATAFilter());
		$dumper->registerFilter('php', new PHPFilter());
		$dumper->registerFilter('style', new CSSFilter());

		static::$_jade = new Jade($parser, $dumper);

		return static::$_jade;
	}

}

/* end of file jade.php */
