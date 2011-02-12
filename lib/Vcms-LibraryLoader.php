<?php
//
//  VictoryCMS - Content managment system and framework.
//
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
 * VictoryCMS - LibraryLoader
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Mitchell Bosecke <mitchellbosecke@gmail.com>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace Vcms;

/**
 * This class loads the external libraries of which are located
 * in directories specified by the configuration file.
 *
 * @package Core
 * @todo Finish implementing
 * @todo Test
 */
class LibraryLoader
{
	
	/** Singleton instance to LibraryLoader */
	private static $instance;
	
	/** User friendly error message */
	private static $errorMessage;
	
	/** PHP File pattern, matches all full path PHP files. */
	private static $phpFilePattern = '/^.+\.php$/i';
	
	/**
	 * private constructor
	 */
	private function __construct()
	{
	}

	/**
	 * The singleton functon for getting the object.
	 * @return LibraryLoader object used to load external libraries.
	 */
	public static function getInstance()
	{
		if (!isset(static::$instance)) {
			$c = __CLASS__;
			static::$instance = new $c;
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
	 * Loads both app-specific and global external libraries
	 */
	public static function loadLibraries($lib_external,$app_external)
	{
		static::loadArrayOfLibraries($lib_external);
		static::loadArrayOfLibraries($app_external);
	}
	
	
	/**
	 * 
	 * Loads all the libraries in a given array
	 * @param array of libraries $libraries
	 */
	private static function loadArrayOfLibraries($libraries){
		foreach ($libraries as $library){
			if (! isset($library["name"])){
				static::$errorMessage = "Library name not set properly in config.json";
				//TODO: throw exception
			}
			if (! isset($library["class"])){
				static::$errorMessage = "Library class not set properly in config.json";
				//TODO: throw exception
			}
			
			$class_name = $library["class"];
			try {
				$obj = new $class_name;
			} catch (Exception $e){
				echo "Couldnt instantiate class";
			}
			if (! class_exists($class_name)){
				echo "<br>$class_name doesn't exist";
				//TODO: throw exception;
			}
			
			if (! get_parent_class($class_name)==('AbstractLibraryInit')){
				echo "<br>$class_name doesn't extend AbstractLibraryInit!";
				//TODO:: throw exception
			}
			
		}
		
	}
	
	
	/**
	 * Preventing cloning of this class
	 */
	public function __clone()
	{
		throw new \Vcms\Exception\SingletonCopyException;
	}
}
?>