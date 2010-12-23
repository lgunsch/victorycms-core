<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010  Lewis Gunsch <lgunsch@victorycms.org>
//
//  This file is part of VictoryCMS.
//
//  VictoryCMS is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 2 of the License, or
//  (at your option) any later version.
//
//  VictoryCMS is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with VictoryCMS.  If not, see <http://www.gnu.org/licenses/>.

namespace VictoryCMS;

require_once 'RegistryKeys.php';
require_once 'Registry.php';
require_once 'AutoLoader.php';
require_once 'RegistryNode.php';
require_once 'LoadManager.php';

/**
 * VictoryCMS core class. This class is the entry point to the VictoryCMS
 * system. It initializes the error handlers, the class autoloader and
 * contains some important functions.
 *
 * <strong>Note:</strong> This depends on <strong>Registry.php</strong>, 
 * <strong>RegistryNode.php</strong> and <strong>RegistryKeys.php</strong> 
 * for storing system variables. It also depends on 
 * <strong>AutoLoader.php</strong> and <strong>LoadManager.php</strong> for
 * loading required classes. These should be in the same directory as this file.
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 *
 */
class VictoryCMS
{
	/** VictoryCMS version number **/
	const VERSION = 0.1;
	
	/** VictoryCMS version code name **/
	const CODE_NAME = "sparkplug";

	/**
	 * Perform PHP environment initiailization. This will add the exception
	 * and error handlers into PHP and also create the AutoLoader.
	 *
	 * @return void
	 */
	protected static function initialize()
	{
		// PHP version must be 5.3.1 or greater
		if (version_compare(phpversion(), "5.3.1", "<")) {
			throw new \RuntimeException(
                "PHP version must be 5.3.1 or greater to '.
                'run VictoryCMS.<br />\nCurrent PHP version: " . phpversion()
			);
		}

		// Error debug info displaying is determined by debug mode
		error_reporting(E_STRICT | -1); // -1 is important to display sytax errors
		ini_set('display_errors', 1); 
			
		set_exception_handler(__NAMESPACE__.'\VictoryCMS::errorHandler');
		set_error_handler(__NAMESPACE__.'\VictoryCMS::errorHandler', E_STRICT);
		
		$autoloader = AutoLoader::getInstance();
		$autoloader = spl_autoload_register(array($autoloader, 'autoload'));
		if (! $autoloader) {
			exit('VictoryCMS could not attach the required autoloader!');
		}
	}

	protected static function load()
	{
		echo "Starting the load process...\n";
		$autoloader = AutoLoader::getInstance();
		$autoloader->addDirectory(dirname(__FILE__));
		try {
			LoadManager::load(Registry::get(RegistryKeys::settings_path));
		} catch (\Exception $e) {
			echo LoadManager::getUserErrorMessage();
			exit();
		}
		echo "done.\n";
	}
	
	/**
	 * The main startup method for initializing VictoryCMS and to process
	 * the request. This should be called with the path to the config.json
	 * file. This is normally called from the index.php file, or default
	 * start page.
	 *
	 * @param string  $settings_path Path to the settings JSON file.
	 * @param boolean $debug_mode Enable debug mode, disabled by default.
	 *
	 * @return void
	 */
	public static function bootstrap($settings_path, $debug_mode = false)
	{
		if (! is_string($settings_path)) {
			exit('VictoryCMS must be called with a valid settings file path! '
				.$settings_path);
		}
		if (! is_readable($settings_path)) {
			exit('Settings file path '.$settings_path.' is not readable!');
		}
		if (! is_bool($debug_mode)) {
			exit('Debug mode must only be enabled with a boolean value!');
		}

		Registry::set(RegistryKeys::settings_path, $settings_path, true);
		Registry::set(RegistryKeys::debug_enabled, $debug_mode, true);
		
		static::initialize();
		static::load();
		static::run();
	}

	/**
	 * The main run method for starting up the VictorCMS front controller
	 * and beginning processing.
	 * 
	 * @return void
	 */
	protected static function run()
	{
		printf("I AM RUNNING!");
	}

	/**
	 * Are we operating on the command line interface, or a web server?
	 *
	 * @return boolean true if This PHP class was called from the command line.
	 */
	public static function isCli()
	{
		return !isset($_SERVER['HTTP_HOST']);
	}
	
	/**
	 * An error handler for Vcms for exceptions and php errors. It will
	 * print out the file and line number, as well as a back trace and
	 * the current php context.
	 *
	 * @todo let the user configure their own pulblic error message via Settings.xml
	 * @todo the admin email should be set in the Settings.xml file, not here.
	 *
	 * @see http://www.php.net/set_error_handler
	 */
	public static function errorHandler($errno, $errstr='', $errfile='', $errline='')
	{
		// if error has been supressed with an @
		if (error_reporting() == 0) {
			return;
		}

		// get the necessary varaibles from the Registry if they exist
		if (Registry::isKey(RegistryKeys::debug_enabled)) {
			$debugging = Registry::get(RegistryKeys::debug_enabled);
		} else {
			$debugging = false;
		}
		if (Registry::isKey(RegistryKeys::admin_email)) {
			$email_path = Registry::get(RegistryKeys::admin_email);
		} else {
			$email_path = '';
		}
		
		// check if function has been called by an exception
		if (func_num_args() == 5) {
			// called by trigger_error()
			$exception = null;
			list($errno, $errstr, $errfile, $errline) = func_get_args();
			$backtrace = array_reverse(debug_backtrace());

		} else {
			// caught exception
			$exc = func_get_arg(0);
			$errno = $exc->getCode();
			$errstr = $exc->getMessage();
			$errfile = $exc->getFile();
			$errline = $exc->getLine();
			$backtrace = $exc->getTrace();
		}

		/*
		 * E_RECOVERABLE_ERROR is not supported in PHP 5.1.*
		 * so comment out for now
		 */
		$errorType = array (
			E_ERROR            => 'ERROR',
			E_WARNING        => 'WARNING',
			E_PARSE          => 'PARSING ERROR',
			E_NOTICE         => 'NOTICE',
			E_CORE_ERROR     => 'CORE ERROR',
			E_CORE_WARNING   => 'CORE WARNING',
			E_COMPILE_ERROR  => 'COMPILE ERROR',
			E_COMPILE_WARNING => 'COMPILE WARNING',
			E_USER_ERROR     => 'USER ERROR',
			E_USER_WARNING   => 'USER WARNING',
			E_USER_NOTICE    => 'USER NOTICE',
			E_STRICT         => 'STRICT NOTICE',
			E_RECOVERABLE_ERROR  => 'RECOVERABLE ERROR'
		);

		// create error message
		if (array_key_exists($errno, $errorType)) {
			$err = (static::isCli())? ''.$errorType[$errno].' ' :
				'<strong>'.$errorType[$errno].'</strong>';
		} else {
			$err = (static::isCli())? "\tCAUGHT_EXCEPTION" :
				'<strong>CAUGHT EXCEPTION</strong>';
		}

		$errMsg = (static::isCli())? "$err: $errstr in $errfile on line $errline\n" :
			"$err: $errstr in <strong>$errfile</strong> on line ".
				"<strong>$errline</strong>";

		// start backtrace
		foreach ($backtrace as $v) {
			if (isset($v['class'])) {
				$trace = (static::isCli())? 'in class '.$v['class'].'::'.$v['function'].'(' :
					'in class '.$v['class'].'::'.$v['function'].'(';
				
				if (isset($v['args'])) {
					$separator = '';
					foreach ($v['args'] as $arg ) {
						$trace .= "$separator".static::getArgument($arg);
						$separator = ', ';
					}
				}
				$trace .= (static::isCli())? ')' : ')</strong>';
				
			} elseif (isset($v['function']) && empty($trace)) {
				$trace = (static::isCli())? 'in function '.$v['function'].'(' :
					'in class '.$v['function'].'(';
				if (!empty($v['args'])) {

					$separator = '';

					foreach ($v['args'] as $arg ) {
						$trace .= "$separator".static::getArgument($arg);
						$separator = ', ';
					}
				}
				$trace .= (static::isCli())? ')' : ')</strong>';
			}
		}

		// display error message in HTML if debug is enabled and not in CLI,
		// always display if on CLI
		if (static::isCli()) {
			echo "\nDebug Msg:\n".$errMsg."\n".
				"Trace: \n\t".$trace."\n\n";
		} elseif ($debugging == true) {
				echo '<h2>Debug Msg</h2>'.nl2br($errMsg).'<br />'.
					'<strong>Trace:</strong> '.nl2br($trace).'<br />';
		}

		// what to do
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
				//return;
				return false;
				break;

			default:
				if ($debugging == false) {
					// send email to admin if we can
					if (! empty($email_path)) {
						$headers = 'From: VictoryCMS Error Handler\r\n';
						$subject = 'critical error on '.$_SERVER['HTTP_HOST'];
						@mail($email_path, $subject, $errMsg, $headers);
					}
					// end and display error msg
					exit("An error was encountered on this page.\n");
				} else {
					if (! static::isCli()) {
						exit('<p>Aborting.</p>');
					} else {
						exit("\nAborting.\n");
					}
				}
				break;
		}
		return false;
	}

	/**
	 * Creates a string representation of an argument
	 * sent to a function.
	 *
	 * @param $arg The argument to output.
	 *
	 * @return string
	 */
	public static function getArgument($arg)
	{
		switch (strtolower(gettype($arg))) {

			case 'string':
				return('"'.str_replace(array("\n"), array(''), $arg).'"');

			case 'boolean':
				return (bool)$arg;

			case 'object':
				return 'object('.get_class($arg).')';

			case 'array':
				$ret = 'array(';
				$separtor = '';
				foreach ($arg as $k => $v) {
					$ret .= $separtor.getArgument($k).' => '.static::getArgument($v);
					$separtor = ', ';
				}
				$ret .= ')';
				return $ret;

			case 'resource':
				return 'resource('.get_resource_type($arg).')';

			default:
				return var_export($arg, true);
		}
	}

	/**
	 * Just for testing, works the same as print_r() but
	 * prints out array's nicely. This should not be used
	 * in a production environment.
	 *
	 * @param $array The array to print out.
	 * @param $count Recursive call parameter - DO NOT use directly
	 */
	public static function printArray($array, $count=0)
	{
		if (! Registry::get(RegistryKeys::debug_enabled)) {
			throw new Exception('Cannot call PrintArray while not in debug mode!');
		}
		$i=0;
		$tab ='';
		while ($i != $count) {
			$i++;
			$tab .= (static::isCli())? '  |  ' : '&nbsp;&nbsp;|&nbsp;&nbsp;';
		}
		$k = 0;
		foreach ((array)$array as $key=>$value) {
			if (is_array($value)) {
				echo $tab.(static::isCli())? "[$key]\n" : "[<strong><u>$key</u></strong>]<br />";
				$count++;
				print_ar($value, $returnText, $count);
				$count--;
			} else {
				$tab2 = substr($tab, 0, -12);
				echo (static::isCli())? "$tab2~ $key: $value\n" :
					"$tab2~ $key: <strong>$value</strong><br />";
			}
			$k++;
		}
		$count--;
	}
}
?>