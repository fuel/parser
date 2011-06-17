<?php
/**
 *
 * Sourced from: 
 *   https://github.com/jeremyf76/jade.ko3/tree/kohana3/classes/jade/
 *   https://github.com/jeremyf76/jade.ko3/blob/kohana3/classes/jade/core.php
 * 
 * Modified by:
 *   Evan Tishuk (https://github.com/evantishuk/)
 *
 * 
 * Using the above source, it was (quickly) modified to work with FuelPHP's 
 * Parser package (https://github.com/fuel/parser).  Undoubtedly, this could 
 * benefit from a more experienced eye within the FuelPHP community.  And since
 * it was borrowed from a Kohana module, it probably needs some further 
 * revision and due props given to Jeremy Fowler (https://github.com/jeremyf76) 
 * and Konstantin Kudryashov (https://github.com/everzet).
 *
 * 1: "mkdir Jade" in /app/vendor/
 * 2: download Jade PHP from https://github.com/everzet/jade.php
 * 3: Unzip the Jade PHP archive into the newly created Jade directory
 * 4: Make sure that /parser/config/parser.php has autoloader.php.dist 
	*    specified to be included.
 * 5: Create or edit a Controller and View to see Jade in action. Here is 
 *    some sample code:
 *      http://forum.kohanaframework.org/discussion/7295/new-jade-module-haml-like-template-compiler-for-php5.3
 * 
 */

namespace Parser;

use Everzet\Jade\Jade as Engine;
use Everzet\Jade\Parser;
use Everzet\Jade\Lexer\Lexer;
use Everzet\Jade\Dumper\PHPDumper;
use Everzet\Jade\Visitor\AutotagsVisitor;
use Everzet\Jade\Filter\JavaScriptFilter;
use Everzet\Jade\Filter\CDATAFilter;
use Everzet\Jade\Filter\PHPFilter;
use Everzet\Jade\Filter\CSSFilter;

class View_Jade extends \View {

	protected $_file;                          // View filename	
	protected $_data               = array();  // Array of local variables
	protected $_jade               = null;     // Jade Engine
	public $extension              = '.jade';  // (originally no period, problem?)
	protected static $_global_data = array();
	
		/**
	 * Captures the output that is generated when a view is included.
	 * The view data will be extracted to make local variables. This method
	 * is static to prevent object scope resolution.
	 *
	 *     $output = Jade::capture($file, $data);
	 *
	 * @param   string  jade template text
	 * @param   array   variables
	 * @return  string
	 */
	protected static function capture($template, array $data)
	{
		// Import the view variables to local namespace
		extract($data, EXTR_SKIP);

		if (self::$_global_data)
		{
			// Import the global view variables to local namespace and maintain references
			extract(self::$_global_data, EXTR_REFS);
		}
		
		// Capture the view output
		ob_start();

		try
		{
			// Load the view within the current scope
			eval('?>'.$template);
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

 
	/**
	 * Sets the initial jade template filename and local data. Views should almost
	 * always only be created using [Jade::factory].
	 *
	 *     $view = new View($file);
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  void
	 * @uses    View::set_filename
	 */
	public function __construct($file = NULL, array $data = NULL)
	{
	
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if ( $data !== NULL)
		{
			// Add the values to the current data
			$this->_data = $data + $this->_data;
		}
		$parser = new Parser(new Lexer());
		$dumper = new PHPDumper();
		$dumper->registerVisitor('tag', new AutotagsVisitor());
		$dumper->registerFilter('javascript', new JavaScriptFilter());
		$dumper->registerFilter('cdata', new CDATAFilter());
		$dumper->registerFilter('php', new PHPFilter());
		$dumper->registerFilter('style', new CSSFilter());

		$this->_jade = new Engine($parser, $dumper);
	}


	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @param   string  view filename
	 * @return  View
	 * @throws  Exception
	*/ 
	public function set_filename($file)
	{

		//find_file($directory, $file, $ext = '.php', $multiple = false, $cache = true)
		if (($path = \Fuel::find_file('views', $file, $this->extension)) === FALSE)
		{
			throw new \Exception('The requested view '.$file.' could not be found');
		}

		// Store the file path locally
		$this->_file = $path;

		return $this;
	}
 
	public function parser($value = NULL)
	{
		$value = is_null($value) ? $this->_file : $value;
		return $this->_jade->render($value);
	}

	/**
	 * Renders the view object to a string. Global and local data are merged
	 * and extracted to create local variables within the view file.
	 *
	 *     $output = $view->render();
	 *
	 * [!!] Global variables with the same key name as local variables will be
	 * overwritten by the local variable.
	 *
	 * @param    string  view filename
	 * @return   string
	 * @throws   Exception
	 * @uses     View::capture
	 */
	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			throw new \Exception('Set the file to use within your view before rendering');
		}

		// Combine local and global data, render the template, and capture the output
		
		return self::capture($this->parser($this->_file), $this->_data);
	}
  
}

/* end of file jade.php */