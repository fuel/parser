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

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */

/**
 * This config file is for adding functions to Twig templates.
 * 
 * To add new functions to templates, follow this guide:
 * 'public_facing_name' => array('Class_name' => 'function_to_use'),
 */
return array(
    'fuel_version' => array('Twig_Fuel_Extension' => 'fuel_version'),
    'url' => array('Twig_Fuel_Extension' => 'url'),
    'theme_asset_css' => array('Twig_Fuel_Extension' => 'theme_asset_css'),
    'theme_asset_js' => array('Twig_Fuel_Extension' => 'theme_asset_js'),
    'theme_asset_img' => array('Twig_Fuel_Extension' => 'theme_asset_img'),
    
    'base_url' => array('Uri' => 'base'),
    'current_url' => array('Uri' => 'current'),
    'uri_segment' => array('Uri' => 'segment'),
    'uri_segments' => array('Uri' => 'segments'),
    
    'config' => array('Config' => 'get'),
    
    'dump' => array('Debug' => 'dump'),
    
    'lang' => array('Lang' => 'get'),
    
    'form_open' => array('Form' => 'open'),
    'form_close' => array('Form' => 'close'),
    'form_input' => array('Form' => 'input'),
    'form_password' => array('Form' => 'password'),
    'form_hidden' => array('Form' => 'hidden'),
    'form_radio' => array('Form' => 'radio'),
    'form_checkbox' => array('Form' => 'checkbox'),
    'form_textarea' => array('Form' => 'textarea'),
    'form_file' => array('Form' => 'file'),
    'form_button' => array('Form' => 'button'),
    'form_reset' => array('Form' => 'reset'),
    'form_submit' => array('Form' => 'submit'),
    'form_select' => array('Form' => 'select'),
    'form_label' => array('Form' => 'label'),
    'form_csrf' => array('Form' => 'csrf'),
    
    'form_val' => array('Input' => 'param'),
    'input_get' => array('Input' => 'get'),
    'input_post' => array('Input' => 'post'),
    
    'asset_add_path' => array('Asset' => 'add_path'),
    'asset_css' => array('Asset' => 'css'),
    'asset_js' => array('Asset' => 'js'),
    'asset_img' => array('Asset' => 'img'),
    'asset_render' => array('Asset' => 'render'),
    'asset_find_file' => array('Asset' => 'find_file'),
    
    'html_anchor' => array('Html' => 'anchor'),
    'html_mail_to_safe' => array('Html' => 'mail_to_safe'),
    'html_ul' => array('Html' => 'ul'),
    
    'session_get' => array('Session' => 'get'),
    'session_get_flash' => array('Session' => 'get_flash'),
    
    'e' => array('Security' => 'htmlentities'),
    
    'markdown_parse' => array('Markdown' => 'parse'),
    
    'num_format_bytes' => array('Num' => 'format_bytes'),
);