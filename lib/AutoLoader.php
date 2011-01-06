<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010  Andrew Crouse <amcrouse@victorycms.org>
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

/**
 * VictoryCMS - AutoLoader
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Andrew Crouse <amcrouse@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace VictoryCMS;

/**
 * This class represents a autoloader object that loads required classes. This class
 * depends on the registry locations for finding needed classes.
 *
 * @package Core
 */
class AutoLoader {

	/** Singleton instance to AutoLoader */
	protected static $instance;
	
	/** Namespace separator pattern: dot, whitespace, or a dash. */
	private static $NSSeparatorPattern = '(\.|\s|-)+';
	
	/** PHP File pattern, matches all full path PHP files. */
	private static $phpFilePattern = '/^.+\.php$/i';
	
	/** Class file search pattern: [class.]%s[.class OR .inc]  */
	private static $pattern = '/^(class\.)?%s(\.class|\.inc){0,2}$/i';
	
	/** Array of arrays of PHP file paths */
	protected static $directoryFiles = array();
	
	/**
	 * protected constructor; prevents direct creation of object. Also adds a few default values for seach for.
	 */
	protected function __construct()
	{

	}

	/**
	 * The singleton functon for getting the object.
	 * @return AutoLoader Object used to auto load classes.
	 */
	public static function getInstance()
	{
		if (!isset(static::$instance)) {
			$c = __CLASS__;
			static::$instance = new $c;
		}
		return static::$instance;
	}

	/**
	 * This is called to load a required class.
	 *
	 * @param string $class The class name to search for.
	 */
	public static function autoload($class)
	{
		//TODO: check if class is already loaded!
		
		// Search all of them for the class to load.
		foreach (static::listDirs() as $directory) {
			static::autoloadDir($class, $directory);
		}
	}

	/**
	 * This searchs the directoryFiles array for the required class
	 * and loads it. 
	 *
	 * @param string $class The class name to search for.
	 * @param string $directory Directory index of array to search in
	 * directoryFiles.
	 * 
	 * @return boolean true if class is loaded, false if not.
	 */
	private static function autoloadDir($class, $directory)
	{
		if (! array_key_exists($directory, static::$directoryFiles)) {
			return false;
		}
		
		$files = static::$directoryFiles[$directory];
		$pattern = static::getPattern($class);
		
		foreach ($files as $file) {
			if (preg_match($pattern, pathinfo($file, PATHINFO_FILENAME)) == 1) {
				require_once $file;
				return true;
			}
		}
		return false;
	}

	/**
	 * This will return the regular expression pattern for the given class; the
	 * class is expected to be in the form of 'Namespace\Sub-namespace\Class'
	 * similar to what is passed into a spl_autoload function.
	 * 
	 * @param string $class Class name to use in the pattern.
	 * 
	 * @return string the complete pattern to match against.
	 */
	protected static function getPattern($class)
	{
		// Create the namespace and class pattern
		$class = trim($class);
		$fixed = ($class{0} == '\\')? substr($class, 1) : $class;
		$separated = str_replace('\\', self::$NSSeparatorPattern, $fixed);

		// Assemble the pattern
		$pattern = sprintf(self::$pattern, $separated);

		return $pattern;
	}	
	
	/**
	 * Loads PHP file paths from a directory or any sub-directories into an array in
	 * the directoryFiles array with the directory path used as the index. 
	 * 
	 * @param string $directory Directory to search for PHP files.
	 */
	protected static function loadDir($directory)
	{
		static::$directoryFiles[$directory] = array();
		
		//TODO: is_dir() nees debugged realpath (FileUtils::truepath) to be accurate!
		if (! is_dir($directory)) {
			throw new \Exception($directory.' is not a valid directory!');
		}

		// Create a PHP file recursive iterator
		$dirIterator = new \RecursiveDirectoryIterator($directory);
		$recursiveIterator = new \RecursiveIteratorIterator($dirIterator);
		$iterator = new \RegexIterator(
			$recursiveIterator,
			static::$phpFilePattern,
			\RecursiveRegexIterator::GET_MATCH
		);

		// Use the iterator to build the list of PHP files
		$files = array();
		foreach ($iterator as $match) {
			array_push(static::$directoryFiles[$directory], $match[0]);
		}
	}
	
	/**
	 * Adds a directory to search in for the required class; all sub-directories below
	 * this the directory will also be searched. The directory should be a valid
	 * readable directory. You should NOT add a sub-directory of a directory already
	 * added into the AutoLoader.
	 *
	 * @param string $directory Directory to search in.
	 */
	public static function addDir($directory)
	{
		if (! is_string($directory) || empty($directory)) {
			throw new \VictoryCMS\Exception\DataTypeException();
		}
		Registry::add(RegistryKeys::autoload, $directory, false);
		static::loadDir($directory);
	}
	
	/**
	 * Returns the directory paths from which the autoloader will search for classes
	 * to load; this will only return a list of top level directories,
	 * sub-directories of these top level directories will be searched by the
	 * autoloader but not returned here.
	 * 
	 * @return array of directory paths.
	 * 
	 */
	public static function listDirs()
	{
		// Check for any user configured autoload directories
		if (Registry::isKey(RegistryKeys::autoload)) {
			$autoload = Registry::get(RegistryKeys::autoload);
		} else {
			$autoload = array();
		}
		
		return $autoload;
	}

	/**
	 * Disables the clone of this class.
	 */
	public function __clone()
	{
		throw new \VictoryCMS\Exception\SingletonCopyException;
	}
}
?>