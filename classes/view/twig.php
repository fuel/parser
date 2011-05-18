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

class View_Twig extends \View {

    protected static $_parser;
    protected static $_parser_loader;

    public static function _init()
    {
        \Twig_Autoloader::register();
    }

    protected static function capture($view_filename, array $view_data)
    {
        $data = static::$_global_data;
        $data = array_merge($data, $view_data);
        
        // Extract View name/extension (ex. "template.twig")
        $view_name = pathinfo($view_filename, PATHINFO_BASENAME);
        
        // Twig Loader
        $views_paths = \Config::get('parser.View_Twig.views_paths', array(APPPATH . 'views'));
        array_unshift($views_paths, pathinfo($view_filename, PATHINFO_DIRNAME));
        static::$_parser_loader = new \Twig_Loader_Filesystem($views_paths);

        try
        {
            $template = static::parser()->loadTemplate($view_name);
            return $template->render($data);
        } 
        catch (\Exception $e) 
        {
			// @TODO: Some problems with Twig/Fuel exceptions
            ob_end_clean();	// Delete the output buffer
            throw $e;		// Re-throw the exception
        }
    }
    
    public $extension = 'twig';
	
    public function parser()
    {
        if ( ! empty(static::$_parser))
        {
            return static::$_parser;
        }
        
        // Twig Environment
        $twig_env_conf = \Config::get('parser.View_Twig.environment', array('optimizer' => -1));
        static::$_parser = new \Twig_Environment(static::$_parser_loader, $twig_env_conf);

        // Twig Lexer
        $twig_lexer_conf = \Config::get('parser.View_Twig.delimiters', null);
        if (isset($twig_lexer_conf))
        {
            $twig_lexer = new \Twig_Lexer(static::$_parser, $twig_lexer_conf);
            static::$_parser->setLexer($twig_lexer);
        }

        return static::$_parser;
    }

}

// end of file twig.php