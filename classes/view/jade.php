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
	* 1: Create a file named "jade.autoloader.php" in /app/vendor/Jade/
	* 2: Put the following (and only the following?) in that file:
	
	     Autoloader::add_namespaces(array(
       'Everzet' => __DIR__.'/src/Everzet',
       'Everzet\\Jade' => __DIR__.'/src/Everzet/Jade',
       'Everzet\\Jade\\Lexer' => __DIR__.'/src/Everzet/Jade/Lexer',
       'Everzet\\Jade\\Dumper' => __DIR__.'/src/Everzet/Jade/Dumper',
       'Everzet\\Jade\\Visitor' => __DIR__.'/src/Everzet/Jade/Visitor',
       'Everzet\\Jade\\Filter' => __DIR__.'/src/Everzet/Jade/Filter',
       'Everzet\\Jade\\Node' => __DIR__.'/src/Everzet/Jade/Node/',
	     ));
						
	* 3: Make sure that /parser/config/parser.php has jade.autoloader.php specifed 
	*    be included.
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
	 * Returns a new Jade object. If you do not define the "file" parameter,
	 * you must call [View::set_filename].
	 *
	 *     $view = Jade::factory($file);
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  View
	 */
	public static function factory($file = NULL, array $data = NULL)
	{
		return new self($file, $data);
	}

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
	 * Sets a global variable, similar to [View::set], except that the
	 * variable will be accessible to all views.
	 *
	 *     Jade::set_global($name, $value);
	 *
	 * @param   string  variable name or an array of variables
	 * @param   mixed   value
	 * @return  void
	 */
	public static function set_global($key, $value = NULL)
	{
		if (is_array($key))
		{
			foreach ($key as $key2 => $value)
			{
				self::$_global_data[$key2] = $value;
			}
		}
		else 
		{
			self::$_global_data[$key] = $value;
		}
	}

	/**
	 * Assigns a global variable by reference, similar to [View::bind], except
	 * that the variable will be accessible to all views.
	 *
	 *     Jade::bind_global($key, $value);
	 *
	 * @param   string  variable name 
	 * @param   mixed   referenced variable
	 * @return  void
	 */
	public static function bind_global($key, & $value)
	{
		self::$_global_data[$key] =& $value;
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
	 * Magic method, searches for the given variable and returns its value.
	 * Local variables will be returned before global variables.
	 *
	 *     $value = $view->foo;
	 *
	 * [!!] If the variable has not yet been set, an exception will be thrown.
	 *
	 * @param   string  variable name
	 * @return  mixed
	 * @throws  Exception
	 */
	public function & __get($key)
	{
		if (isset($this->_data[$key]))
		{
			return $this->_data[$key];
		}
		elseif (isset(self::$_global_data[$key]))
		{
			return self::$_global_data[$key];
		}
		else
		{
			throw new \Exception('View variable is not set: '.$key);
		}
	}

	/**
	 * Magic method, calls [View::set] with the same parameters.
	 *
	 *     $view->foo = 'something';
	 *
	 * @param   string  variable name
	 * @param   mixed   value
	 * @return  void
	 */
	public function __set($key, $value)
	{
		$this->set($key, $value);
	}

	/**
	 * Magic method, determines if a variable is set.
	 *
	 *     isset($view->foo);
	 *
	 * [!!] `NULL` variables are not considered to be set by [isset](http://php.net/isset).
	 *
	 * @param   string  variable name
	 * @return  boolean
	 */
	public function __isset($key)
	{
		return (isset($this->_data[$key]) OR isset(self::$_global_data[$key]));
	}

	/**
	 * Magic method, unsets a given variable.
	 *
	 *     unset($view->foo);
	 *
	 * @param   string  variable name
	 * @return  void
	 */
	public function __unset($key)
	{
		unset($this->_data[$key], self::$_global_data[$key]);
	}

	/**
	 * Magic method, returns the output of [View::render].
	 *
	 * @return  string
	 * @uses    View::render
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (Exception $e)
		{
			// Display the exception message
			throw \Exception($e);

			return '';
		}
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

	/**
	 * Assigns a variable by name. Assigned values will be available as a
	 * variable within the view file:
	 *
	 *     // This value can be accessed as $foo within the view
	 *     $view->set('foo', 'my value');
	 *
	 * You can also use an array to set several values at once:
	 *
	 *     // Create the values $food and $beverage in the view
	 *     $view->set(array('food' => 'bread', 'beverage' => 'water'));
	 *
	 * @param   string   variable name or an array of variables
	 * @param   mixed    value
	 * @return  $this
	 */
	public function set($key, $value = NULL)
	{
		if (is_array($key))
		{
			foreach ($key as $name => $value)
			{
				$this->_data[$name] = $value;
			}
		}
		else
		{
			$this->_data[$key] = $value;
		}

		return $this;
	}

	/**
	 * Assigns a value by reference. The benefit of binding is that values can
	 * be altered without re-setting them. It is also possible to bind variables
	 * before they have values. Assigned values will be available as a
	 * variable within the view file:
	 *
	 *     // This reference can be accessed as $ref within the view
	 *     $view->bind('ref', $bar);
	 *
	 * @param   string   variable name
	 * @param   mixed    referenced variable
	 * @return  $this
	 */
	public function bind($key, & $value)
	{
		$this->_data[$key] =& $value;

		return $this;
	}

	public function parse($value = NULL)
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
		
		return self::capture($this->parse($this->_file), $this->_data);
	}
  
}

/* end of file jade.php */