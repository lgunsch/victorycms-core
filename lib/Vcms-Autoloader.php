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
 * VictoryCMS - Autoloader
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Andrew Crouse <amcrouse@victorycms.org>
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace Vcms;

/**
 * This class represents an autoloader object that loads required classes. This
 * class depends on the registry locations for finding needed classes.
 *
 * @package Core
 */
class Autoloader
{
	/** Singleton instance to Autoloader */
	protected static $instance;

	/** Namespace separator pattern: dot, or a dash. */
	private static $NSSeparatorPattern = '(\.|-)+';

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
		// disable autoloader class searching by default
		if (! Registry::isKey(RegistryKeys::AUTOLOAD_SEARCH_ENABLE)) {
			Registry::set(RegistryKeys::AUTOLOAD_SEARCH_ENABLE, false);
		}
	}

	/**
	 * The singleton functon for getting the object.
	 *
	 * @return Autoloader Object used to autoload classes.
	 */
	public static function getInstance()
	{
		if (! isset(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * This is called to load a required class.
	 *
	 * @param string $class The class name to search for.
	 *
	 * @return boolean true if $class was loaded, false if not.
	 */
	public static function autoload($class)
	{
		static::getInstance(); // cause constructor to run if necessary

		// Search all autoload directories for the class to load.
		foreach (static::listDirs() as $directory) {
			if (static::autoloadDir($class, $directory)) {
				return true;
			}
		}

		// If we did not find the class the first time rescan the directories and
		// try again. This is code duplication and also very expensive, but it
		// should occur very seldom.
		static::rescanDirs();
		foreach (static::listDirs() as $directory) {
			if (static::autoloadDir($class, $directory)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * This searchs the directoryFiles array for the required class
	 * and loads it.
	 *
	 * @param string $class     class name to search for.
	 * @param string $directory directory index of array to search in
	 *                          directoryFiles.
	 *
	 * @return boolean true if class is loaded, false if not.
	 */
	private static function autoloadDir($class, $directory)
	{
		static::getInstance(); // cause constructor to run if necessary

		if (! array_key_exists($directory, static::$directoryFiles)) {
			return false;
		}

		$files = static::$directoryFiles[$directory];

		// try and find the class path by using the expected key before searching
		$fileKey = str_replace('\\', '-', strtolower($class));
		if (array_key_exists($fileKey, $files)) {
			require_once $files[$fileKey];
			return true;
		}

		// Skip autoloader search if it is disabled
		if (! Registry::get(RegistryKeys::AUTOLOAD_SEARCH_ENABLE)) {
			return false;
		}

		$pattern = static::getPattern($class);

		// This search is exceedingly expensive when done for every class needed,
		// avoid it if possible.
		//TODO: perhaps we should skip searching external libraries which
		// slow this down quite a bit.
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
	 * @param string $class class name to use in the pattern.
	 *
	 * @return string the complete pattern to match against.
	 */
	protected static function getPattern($class)
	{
		static::getInstance(); // cause constructor to run if necessary

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
	 * the directoryFiles array with the directory path used as the index; the file
	 * path will be set to lower case and used as the key for the entry for that
	 * directory, with the value being the full directory path.
	 *
	 * @param string $directory directory to search for PHP files.
	 *
	 * @example static::$directoryFiles[$directory]['vcms-autoloader']
	 *  = '/lib/Vcms-Autoloader.php'
	 *
	 * @return void
	 */
	protected static function loadDir($directory)
	{
		static::getInstance(); // cause constructor to run if necessary

		$directory = static::truepath($directory);
		static::$directoryFiles[$directory] = array();

		if (! is_dir($directory)) {
			throw new \Exception($directory.' is not a valid directory!');
		}

		// Create a PHP file recursive iterator
		$dirIterator = new \RecursiveDirectoryIterator($directory);
		$dirFilter = new RecursiveDirectoryFilter($dirIterator);
		$recursiveIterator = new \RecursiveIteratorIterator($dirFilter);
		$iterator = new \RegexIterator(
			$recursiveIterator,
			static::$phpFilePattern,
			\RecursiveRegexIterator::GET_MATCH
		);

		// Use the iterator to build the list of PHP files
		$files = array();
		foreach ($iterator as $match) {
			$file = pathinfo($match[0], PATHINFO_FILENAME);
			$fileNameKey = str_replace('.', '-', strtolower($file));
			static::$directoryFiles[$directory][$fileNameKey] = $match[0];
		}
	}

	/**
	 * This function is to replace PHP's extremely buggy realpath(); it is used to
	 * realize file system paths. It will resolve absolute and relative paths,
	 * paths with . and .., and paths with extra directory separators. There will be
	 * no trailing / or \ in the path.
	 *
	 * @param string $path original path, can be relative or absolute.
	 *
	 * @return string the resolved path, it may not exist.
	 */
	public static function truepath($path)
	{
		static::getInstance(); // cause constructor to run if necessary

		// attempts to detect if path is relative in which case, add cwd
		if (strpos($path, ':') === false
			&& (strlen($path) == 0 || $path{0} != '/')
		) {
			$path = getcwd().DIRECTORY_SEPARATOR.$path;
		}
		// resolve path parts (single dot, double dot and double delimiters)
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
		$parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
		$absolutes = array();
		foreach ($parts as $part) {
			if ('.'  == $part) {
				continue;
			}
			if ('..' == $part) {
				array_pop($absolutes);
			} else {
				$absolutes[] = $part;
			}
		}
		$path = DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR, $absolutes);
		// if file exists and it is a link, use readlink to resolves links
		if (file_exists($path) && is_link($path) == true) {
			$path = readlink($path);
		}
		return $path;
	}

	/**
	 * This will rescan all the directories for PHP files and rebuild the file path
	 * cache; be frugal in calling this function as it can be expensive.
	 *
	 * @return void
	 */
	public static function rescanDirs()
	{
		static::getInstance(); // cause constructor to run if necessary

		foreach (static::listDirs() as $directory) {
			static::loadDir($directory);
		}
	}

	/**
	 * Adds a directory to search in for the required class; all sub-directories below
	 * this the directory will also be searched. The directory should be a valid
	 * readable directory. You should NOT add a sub-directory of a directory already
	 * added into the Autoloader.
	 *
	 * @param string $directory Directory to search in, not a sub-directory of one
	 * already added.
	 *
	 * @return void
	 */
	public static function addDir($directory)
	{
		static::getInstance(); // cause constructor to run if necessary

		if (! is_string($directory) || empty($directory)) {
			//TODO: fix me, I should be 2 different exceptions
			throw new \Vcms\Exception\InvalidType();
		}
		$path = static::truepath($directory);
		Registry::add(RegistryKeys::AUTOLOAD, $path, false);
		static::loadDir($directory);
	}

	/**
	 * Returns the directory paths from which the autoloader will search for classes
	 * to load in an array; this will only return a list of top level directories,
	 * sub-directories of these top level directories will be searched by the
	 * autoloader but not returned here.
	 *
	 * @return array list of directory paths.
	 */
	public static function listDirs()
	{
		static::getInstance(); // cause constructor to run if necessary

		// Check for any user configured autoload directories
		if (Registry::isKey(RegistryKeys::AUTOLOAD)) {
			$autoload = Registry::get(RegistryKeys::AUTOLOAD);
		} else {
			$autoload = array();
		}

		return $autoload;
	}

	/**
	 * Disables the clone of this class.
	 *
	 * @return void
	 */
	public function __clone()
	{
		throw new \Vcms\Exception\SingletonCopy;
	}
}

/**
 * This Autoloader internal class represents a recursive directory filter iterator
 * that is used to ignore specific directory paths when iterating over the
 * filesystem.
 *
 * @package Core
 */
class RecursiveDirectoryFilter extends \RecursiveFilterIterator
{
	/**
	 * Returns whether the current directory of the iterator is acceptable
	 * through this filter.
	 *
	 * @see http://ca3.php.net/manual/en/filteriterator.accept.php
	 *
	 * @return bool true if directory is acceptable.
	 */
	public function accept()
	{
		if (! Registry::isKey(RegistryKeys::AUTOLOAD_PATH_IGNORE)) {
			return true;
		}

		$filter = Registry::get(RegistryKeys::AUTOLOAD_PATH_IGNORE);
		if (! is_array($filter)) {
			$filter = array($filter);
		}

		$count = count($filter);
		for ($i = 0; $i < $count; $i++) {
			$path = Autoloader::truepath($filter[$i]);
			$filter[$i] = $path;
		}

		$accept = ! in_array($this->current()->getPath(), $filter);
		return $accept;
	}
}
