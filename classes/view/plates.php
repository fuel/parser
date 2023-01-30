<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.9-dev
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

namespace Parser;

class View_Plates extends \View
{
	// parser instance
	protected static $_parser;

	// create a parser instance
	public static function parser()
	{
		if ( ! empty(static::$_parser))
		{
			return static::$_parser;
		}

		// create a parser instance
		static::$_parser = new \League\Plates\Engine(null, $this->extension);

		// any extensions defined?
		foreach (\Config::get('parser.View_Plates.extensions', array()) as $extension)
		{
			if (class_exists($extension))
			{
				$extension = new $extension();
			}
			if ($extension instanceOf League\Plates\Extension\ExtensionInterface)
			{
				static::$_parser->loadExtension($class);
			}
			else
			{
				throw new \FuelException("Parser: defined Plates extension \"$extension\" is not a valid extension");
			}
		}

		return static::$_parser;
	}

	// extension used by this template engine
	public $extension = 'tpl';

	protected function process_file($file_override = false)
	{
		// get the template filename
		$file = $file_override ?: $this->file_name;

		// split it into parts
		$file = pathinfo($file);

		// set the directory and extension for this template
		static::parser()->setDirectory($file['dirname']);
		if ( ! empty($file['extension']))
		{
			static::parser()->setFileExtension($file['extension']);
		}
		else
		{
			static::parser()->setFileExtension(null);
		}

		// render the template
		try
		{
			$data = $this->get_data();
			$result = static::parser()->render($file['filename'], $data);
		}
		catch (\Exception $e)
		{
			// Delete the output buffer & re-throw the exception
			ob_end_clean();
			throw $e;
		}

		$this->unsanitize($data);
		return $result;
	}
}
