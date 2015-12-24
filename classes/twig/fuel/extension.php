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

use Router;
use Uri;
use Twig_Extension;
use Twig_Function_Function;
use Twig_Function_Method;

/**
 * Provides Twig support for commonly used FuelPHP classes and methods.
 */
class Twig_Fuel_Extension extends Twig_Extension
{
	/**
	 * Gets the name of the extension.
	 *
	 * @return  string
	 */
	public function getName()
	{
		return 'fuel';
	}

	/**
	 * Sets up all of the functions this extension makes available.
	 *
	 * @return  array
	 */
	public function getFunctions() 
	{
        $functions = array();
        // Get config file //
        $config = \Config::load('twig', true);

        // Loop through config results //
        foreach ($config as $name => $v) 
        {
            // Loop through inner array consisting of class and function //
            foreach ($v as $class => $function) 
            {
                // Single out this class //
                if ($class == 'Twig_Fuel_Extension') 
                {
                    $functions[$name] = new Twig_Function_Method($this, $function);
                } 
                else 
                {
                    $functions[$name] = new Twig_Function_Function($class . '::' . $function);
                }
            }
        }
            
        // Return generated results //
        return $functions;
    }

    /**
	 * Provides the url() functionality.  Generates a full url (including
	 * domain and index.php).
	 *
	 * @param   string  URI to make a full URL for (or name of a named route)
	 * @param   array   Array of named params for named routes
	 * @return  string
	 */
	public function url($uri = '', $named_params = array())
	{
		if ($named_uri = \Router::get($uri, $named_params))
		{
			$uri = $named_uri;
		}

		return \Uri::create($uri);
	}

	public function fuel_version()
	{
		return \Fuel::VERSION;
	}

	public function theme_asset_css($stylesheets = array(), $attr = array(), $group = null, $raw = false)
	{
		return \Theme::instance()->asset->css($stylesheets, $attr, $group, $raw);
	}

	public function theme_asset_js($scripts = array(), $attr = array(), $group = null, $raw = false)
	{
		return \Theme::instance()->asset->js($scripts, $attr, $group, $raw);
	}

	public function theme_asset_img($images = array(), $attr = array(), $group = null)
	{
		return \Theme::instance()->asset->img($images, $attr, $group);
	}
}
