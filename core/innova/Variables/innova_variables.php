<?php
/**
 * This file contains php configuration settings
 * and other important setting
 */

/**
 * Overriding php.ini settings
 */
//$PHP_INI = \Innova\Configs\ConfigHandler::config("files_settings");
//ini_set("upload_max_filesize", $PHP_INI['upload_max_filesize'] . "M");
//ini_set("post_max_size", $PHP_INI['post_max_size'] . "M");
//ini_set("max_file_uploads", $PHP_INI['max_file_uploads'] . "M");
//ini_set("max_input_time", $PHP_INI['max_input_time']);
//ini_set("max_execution_time", $PHP_INI['max_execution_time'] ?? 1000);


/**
 * database connection variable
 */
global $connection;

/**
 * view templates variables
 * $web_head_section is variable that will be used to access your site head section
 * <head></head>
 *
 * you modify this section with these keys
 *  ------------keys------------------
 *  title - string
 *  meta - array as meta name as key of object value for content
 *  library - array with keys js, css, font and value of the keys
 *  must be arrayed of values eg ['assets/css/main.css', 'assets/css/page.css'] for key
 *  css
 */
global $head_section;

/**
 * body_section will hold <body></body> content. this is array of these keys
 * --------------keys-------------------
 *  navigation - string value by default site will for navigation at namespace
 *   Innova\Controller\routes\Navigation for defaults but you have yours at Innova\Controller\custom\Navigation
 *   this will be used only but default will be used if user is admin
 *
 * main - string all content designated for main <main><main> in short all return values of controller will be
 *   set to this variable please not return variable will only be string of html
 *
 */
global $body_section;

/**
 *  footer_section will hold <footer</footer> and all other below footer content
 * -------------------keys---------------------
 * footer_content - string of footer contents
 * footer_outer - as array same as library in head_section
 */
global $footer_section;

/**
 * By the above variable will have no direct access to views but only controllers
 */
global $messagesStorages;
$messagesStorages = &$_SESSION['messages_all'];

$changes = changeGlobals();

global $home_path;
$home_path  = $_SERVER['DOCUMENT_ROOT'] . "/" . $changes['home'];

$_SERVER['DOCUMENT_ROOT'] = $_SERVER['DOCUMENT_ROOT'] . "/" . $changes['home'];

/**
 * Menu variable
 */
global $menus;