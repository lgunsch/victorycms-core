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
 *
 * @todo Testing since I can't till the reg is done.
 * @todo Add the directories from the reg.
 */
class AutoLoader {

	/** Singleton instance to AutoLoader */
	private static $instance;
	
	/** Array of registered directories */
	private $directories;
	
	/** Array of file format extensions */
	private $fileNameFormats;

	/**
	 * private constructor; prevents direct creation of object. Also adds a few default values for seach for.
	 */
	private function __construct()
	{
		$directories = array(
		);
		$fileNameFormats = array(
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
		echo "Loading $class";
		foreach ($this->directories as $directory) {
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
	 * Adds a directory to search in for the needed class.
	 *
	 * @param string $directory Directory to search in.
	 */
	public function addDirectory($directory)
	{
		    if (is_string($directory)) {
	        $this->directories[] = $directory;
	    } elseif (is_array($directory)) {
	        foreach ($directory as $dir) {
			    $this->addDirectory($dir);
		    }
	    }
	}

	/**
	 * Adds a file name format to search for.
	 *
	 * @param string $format File name format to add.
	 */
	public function addFileNameFormat($format)
	{
		if (is_string($format)) {
	        $this->fileNameFormats[] = $format;
	    } elseif (is_array($format)) {
	        foreach ($format as $for) {
			    $this->addFileNameFormat($for);
		    }
	    }
	}


	/**
	 * Disables the clone of this class.
	 */
	public function __clone()
	{
		throw new VictoryCMS\Exception\SingletonCopyException;
	}
}
?>