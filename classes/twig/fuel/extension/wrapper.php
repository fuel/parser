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

/**
 * Extend is not the same class for twig 3.x, hence the use of this condition
 */
if (class_exists('\Twig\Extension\AbstractExtension')) 
{
    /**
     * Extension class for twig 3.x
     */
    abstract class Twig_Fuel_Extension_Wrapper extends \Twig\Extension\AbstractExtension
    {
    }
}
else
{
    /**
     * Extension class for twig < 3.x
     */
    abstract class Twig_Fuel_Extension_Wrapper extends \Twig_Extension
    {
    }
}