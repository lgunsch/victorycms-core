<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010  Andrew Crouse <amcrouse@victorycms.org>
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
 * This class represents a autoloader object that loads needed classes. This class
 * depends on the registry locations for finding needed classes.
 *
 * @package Core
 */
class AutoLoader {

	/** Singleton instance to AutoLoader */
	protected static $instance;
	
	/** Namespace separator pattern: dot, whitespace, or a dash. */
	private static $NSSeparatorPattern = '(\.|\s|-)+';
	
	/** Class search regular expression pattern: [class.]%s[.class OR .inc].php  */
	private static $pattern = '/^(class\.)?%s(\.class|\.inc){0,2}\.php$/i';
	
	/** Array of file format extensions */
	private $fileNameFormats;

	/**
	 * protected constructor; prevents direct creation of object. Also adds a few default values for seach for.
	 */
	protected function __construct()
	{
		$this->fileNameFormats = array(
        	'%s.php',
      		'%s.class.php',
      		'class.%s.php',
      		'%s.inc.php'
        );
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
	 * The function to be called to search for a given needed class.
	 *
	 * @param string $class the class name to search for
	 */
	public function autoload($class)
	{
		// Search all of them for the class to load.
		foreach (static::listDirs() as $directory) {
			static::autoloadRecursive($class, $directory);
		}
	}

	/**
	 * The function that recursivley searchs for the needed class.
	 *
	 * @param string $class the class name to search for
	 * @param string $directory location to search within
	 */
	private function autoloadRecursive($class, $directory)
	{
		foreach ($this->fileNameFormats as $fileNameFormat) {
			$path = $directory.sprintf($fileNameFormat, $class);
			if (file_exists($path)) {
				include_once $path;
				return;
			}
		}
		$d = dir($directory);
		while ($entry=$d->read()) {
			if (is_dir($d->path.DIRECTORY_SEPARATOR.$entry) && ($entry != '.' || $entry != '..')) {
				static::autoloadRecursive($class, $d->path.DIRECTORY_SEPARATOR.$entry);
			}
		}
		$d->close();
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
		$class = trim($class);
		$fixed = ($class{0} == '\\')? substr($class, 1) : $class;
		$separated = str_replace('\\', self::$NSSeparatorPattern, $fixed);
		$pattern = sprintf(self::$pattern, $separated);
		return $pattern;
	}	
	
	/**
	 * Adds a directory to recursively search in for the needed class; the directory
	 * should be a valid readable directory, although this cannot be checked until
	 * it is used by the autoload method. You should not add a sub-directory of a 
	 * directory already added into the AutoLoader.
	 *
	 * @param string $directory Directory to search in.
	 */
	public static function addDir($directory)
	{
		if (! is_string($directory) || empty($directory)) {
			throw new \VictoryCMS\Exception\DataTypeException();
		}
		Registry::add(RegistryKeys::autoload, $directory, false);
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