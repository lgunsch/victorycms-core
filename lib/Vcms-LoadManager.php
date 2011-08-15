<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010,2011  Andrew Crouse <amcrouse@victorycms.org>
//  Copyright (C) 2010,2011  Lewis Gunsch <lgunsch@victorycms.org>
//  Copyright (C) 2010,2011  Mitchell Bosecke <mitchellbosecke@gmail.com>
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

/**
 * VictoryCMS - LoadManager
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Andrew Crouse <amcrouse@victorycms.org>
 * @author   Mitchell Bosecke <mitchellbosecke@gmail.com>
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace Vcms;

/**
 * This class loads the Registry with information passed in from a settings file.
 * This will produce user friendly error messages which can be retrieved after any
 * exception is thrown.
 *
 * @package Core
 */
class LoadManager
{

	/** Singleton instance to LoadManager */
	protected static $instance;

	/** User friendly error message */
	protected static $errorMessage;

	/**
	 * private constructor; prevents direct creation of object.
	 */
	protected function __construct()
	{

	}

	/**
	 * The singleton functon for getting the object.
	 *
	 * @return LoadManager Object used to add items to the Registry.
	 */
	public static function getInstance()
	{
		if (! isset(static::$instance)) {
			static::$instance = new static();
			static::$errorMessage = '';
		}
		return static::$instance;
	}

	/**
	 * Returns the user friendly error message for the last error.
	 *
	 * @return string Last error message in user friendly format
	 */
	public static function getUserErrorMessage()
	{
		return static::$errorMessage;
	}

	/**
	 * Loads a VictoryCMS configuration file into the Registry. This will first
	 * remove comment lines starting with '##' characters.
	 *
	 * @param string $path to the file to load.
	 *
	 * @throws \Exception If json_decode function is not available, path to
	 * configuration file is invalid, configuration file cannnot be read, or the
	 * configuration file cannot be properly decoded.
	 *
	 * @return void
	 */
	public static function load($path)
	{
		if (! function_exists('json_decode')) {
			static::$errorMessage = "JSON PHP extension is required.\n";
			throw new \Vcms\Exception\NotFound('\'json_decode\'');
		}

		$path = FileUtils::truepath($path);
		if (! file_exists($path)) {
			static::$errorMessage = "Cannot find path for configuration file: $path\n";
			throw new \Vcms\Exception\NotFound($path);
		}

		$contents = file_get_contents($path);
		if ($contents === false) {
			static::$errorMessage = "Cannot read configuration file: $path\n";
			//  This should only happen when file has wrong permissions thus,
			//  it should be caught by file_exists before here.
			throw new \Vcms\Exception\NotFound($path);
		}

		$contents = FileUtils::removeComments($contents);
		$json = json_decode($contents, true);
		$error = json_last_error();

		if ($json === null || $error != JSON_ERROR_NONE) {
			static::$errorMessage = static::getJsonErrorMessage($path);
			throw new \Vcms\Exception\Syntax($path);
		}

		foreach ($json as $key => $value) {
			// Skip any null values
			if (! isset($value)) {
				continue;
			}

			// Process loading of listed config files
			if ($key == RegistryKeys::LOAD) {
				static::processSecondaryFiles($value);

			// Add a key and array value pair into the registry, default is read-only
			} elseif (is_array($value)) {
				if (isset($value["value"]) && isset($value["readonly"])) {
					Registry::add($key, ($value["value"]), $value["readonly"]);
				} elseif (isset($value["value"])) {
					Registry::add($key, ($value["value"]), true);
				} else {
					Registry::add($key, $value, true);
				}

			// Add a key value pair into the registry, default is read-only
			} elseif ($value) {
				Registry::set($key, $value, true);
			}
		}
	}

	/**
	 * Process loading of listed configuration files from the current configuration
	 * file; configuration files which have circular references to each other
	 * cannot be loaded.
	 *
	 * @param mixed $jsonValue JSON decoded value.
	 *
	 * @throws \Exception If json_decode function is not available, path to
	 * configuration file is invalid, configuration file cannnot be read, or the
	 * configuration file cannot be properly decoded.
	 *
	 * @return void
	 */
	private static function processSecondaryFiles($jsonValue)
	{
		// Get the config files which have been already loaded
		if (Registry::isKey(RegistryKeys::LOAD)) {
			$locations = Registry::get(RegistryKeys::LOAD);
		} else {
			$locations = array();
		}

		$load = array();

		// Load multiple config files from array
		if (is_array($jsonValue) && isset($jsonValue)) {
			foreach ($jsonValue as $item) {
				if (is_array($item)) {
					throw new \Exception(
						'LoadManager does not support multi-dimensional loading arrays.'
					);
				}
				if (! in_array($item, $locations)) {
					Registry::add(RegistryKeys::LOAD, $item, false);
					array_push($load, $item);
					array_push($locations, $item);
				}
			}

		// Load a single config file
		} elseif (isset($jsonValue["value"])) {
			if (! in_array($jsonValue, $locations)) {
				Registry::add(RegistryKeys::LOAD, $jsonValue, false);
				array_push($load, $jsonValue);
			}
		}

		// Process each new config file now
		foreach ($load as $location) {
			static::load($location);
		}
	}

	/**
	 * This will return a nice readable error message if there is an error in a
	 * configuration file; It assumes json_decode produced the error since this
	 * loads configuration files using JSON format.
	 *
	 * @param string $filePath The configuration file containing the error.
	 *
	 * @return string The last JSON error in user friendly format.
	 */
	protected static function getJsonErrorMessage($filePath)
	{
		$json_errors = array(
    		JSON_ERROR_NONE => 'No error has occurred',
    		JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
    		JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
    		JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
    		JSON_ERROR_SYNTAX => 'Syntax error',
    		JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
		);
		$message = 'Could not decode configuration file ';
		$message .= (VictoryCMS::isCli())? $filePath : "<em>$filePath</em>";
		$message .= (VictoryCMS::isCli())? ": ": ":&nbsp;<strong>";
		$message .= $json_errors[json_last_error()];
		$message .= (VictoryCMS::isCli())? ".\n" : "</strong>";

		return $message;
	}

	/**
	 * Preventing cloning of this class
	 *
	 * @return void
	 */
	public function __clone()
	{
		throw new \Vcms\Exception\SingletonCopy;
	}
}
