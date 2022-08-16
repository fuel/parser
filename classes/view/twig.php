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

use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_Lexer;

class View_Twig extends \View
{
	protected static $_parser;
	protected static $_version;
	protected static $_parser_loader;
	protected static $_twig_lexer_conf;

	public static function _init()
	{
		parent::_init();

		// Detect twig version
		static::$_version = (float) substr(class_exists('\Twig\Environment') ? \Twig\Environment::VERSION : Twig_Environment::VERSION, 0, 3);

		// backward compatibility for Twig 1.x
		if (class_exists('Twig_Autoloader'))
		{
			Twig_Autoloader::register();
		}
	}

	protected function process_file($file_override = false)
	{
		$file = $file_override ?: $this->file_name;

		$local_data  = $this->get_data('local');
		$global_data = $this->get_data('global');

		// Extract View name/extension (ex. "template.twig")
		$view_name = pathinfo($file, PATHINFO_BASENAME);

		// Twig Loader
		$views_paths = \Config::get('parser.View_Twig.views_paths');
		if ( ! $views_paths)
		{
			// get the paths defined in the active request
			if (class_exists('Request', false) and ($request = \Request::active()))
			{
				$views_paths = array();
				foreach ($request->get_paths() as $path)
				{
					$views_paths[] = $path . 'views';
				}
			}
			$views_pathsp[] = APPPATH . 'views';
		}
		array_unshift($views_paths, pathinfo($file, PATHINFO_DIRNAME));

		// check if we're using Twig v3
		if (class_exists('\Twig\Loader\FilesystemLoader'))
		{
			static::$_parser_loader = new \Twig\Loader\FilesystemLoader($views_paths);
		}
		else
		{
			static::$_parser_loader = new Twig_Loader_Filesystem($views_paths);
		}

		if ( ! empty($global_data))
		{
			foreach ($global_data as $key => $value)
			{
				static::parser()->addGlobal($key, $value);
			}
		}
		else
		{
			// Init the parser if you have no global data
			static::parser();
		}

		// check if we're using Twig v3
		if (class_exists('\Twig\Lexer'))
		{
			$twig_lexer = new \Twig\Lexer(static::$_parser, static::$_twig_lexer_conf);
		}
		else
		{
			$twig_lexer = new Twig_Lexer(static::$_parser, static::$_twig_lexer_conf);
		}
		static::$_parser->setLexer($twig_lexer);

		try
		{
			// Arguments of loadTemplate changed in twig 3.x
			if (static::$_version >= 3)
			{
				$template_classe = static::parser()->getTemplateClass($view_name);
				$result = static::parser()->loadTemplate($template_classe, $view_name)->render($local_data);
			}
			else
			{
				$result = static::parser()->loadTemplate($view_name)->render($local_data);
			}
		}
		catch (\Exception $e)
		{
			// Delete the output buffer & re-throw the exception
			ob_end_clean();
			throw $e;
		}

		$this->unsanitize($local_data);
		$this->unsanitize($global_data);

		return $result;
	}

	public $extension = 'twig';

	/**
	 * Returns the Parser lib object
	 *
	 * @return  Twig_Environment
	 */
	public static function parser()
	{
		if ( ! empty(static::$_parser))
		{
			static::$_parser->setLoader(static::$_parser_loader);
			return static::$_parser;
		}

		// Twig Environment
		$twig_env_conf = \Config::get('parser.View_Twig.environment', array('optimizer' => -1));

		// check if we're using Twig v3
		if (class_exists('\Twig\Environment'))
		{
			static::$_parser = new \Twig\Environment(static::$_parser_loader, $twig_env_conf);
		}
		else
		{
			static::$_parser = new Twig_Environment(static::$_parser_loader, $twig_env_conf);
		}

		foreach (\Config::get('parser.View_Twig.extensions') as $ext)
		{
			static::$_parser->addExtension(new $ext());
		}

		// Twig Lexer
		static::$_twig_lexer_conf = \Config::get('parser.View_Twig.delimiters', null);
		if (isset(static::$_twig_lexer_conf))
		{
			isset(static::$_twig_lexer_conf['tag_block'])
				and static::$_twig_lexer_conf['tag_block'] = array_values(static::$_twig_lexer_conf['tag_block']);
			isset(static::$_twig_lexer_conf['tag_comment'])
				and static::$_twig_lexer_conf['tag_comment'] = array_values(static::$_twig_lexer_conf['tag_comment']);
			isset(static::$_twig_lexer_conf['tag_variable'])
				and static::$_twig_lexer_conf['tag_variable'] = array_values(static::$_twig_lexer_conf['tag_variable']);
		}

		return static::$_parser;
	}
}

// end of file twig.php
