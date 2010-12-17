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

/**
 * VictoryCMS - FileUtils
 *
 * @filesource
 * @category VictoryCMS
 * @package  Utilities
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace VictoryCMS;

/**
 * A collection of file and directory manipulating methods.
 * 
 * @author Lewis Gunsch
 * @filesource
 * @package Utilities
 * @license http://www.gnu.org/licenses/gpl.html
 *
 */
class FileUtils
{
	/**
	 * Recursively finds any PHP files in the directory $path or in any number of
	 * sub-folder's underneath the $path including hidden files.
	 * 
	 * @example
	 * $files = FileUtils::findPHPFiles('/etc');
	 *	foreach ($files as $filePath) {
	 *		echo "$filePath\n";
	 *	}
	 * 
	 * @param string $path Directory path from which to find PHP files
	 * @throws \Exception if path is not a string
	 * 
	 * @return array containing a single index for each PHP file.
	 */
	public static function findPHPFiles($path)
	{
		return static::findFilesByExtension($path, 'php');
	}
	
	/**
	 * Recursively finds any files with the specified extension in the directory $path
	 * or in any number of sub-folder's underneath the $path including hidden files.
	 * 
	 * @example
	 * $files = FileUtils::findPHPFiles('/etc', 'ini');
	 *	foreach ($files as $filePath) {
	 *		echo "Found INI file: $filePath\n";
	 *	}
	 * 
	 * @param string $path Directory path from which to find files with extension
	 * $extension
	 * @param string $extension The file extension to look for when searching for
	 * files, but should not have a '.' at the start.
	 * @param bool $caseSensitive If the extension should be case-sensitive or not;
	 * default is false or not case-sensitive.
	 * @throws \Exception if path or extension are not strings, or if caseSensitive
	 * is not a bool.
	 * 
	 * @return array containing a single index for each PHP file.
	 */
	public static function findFilesByExtension($path, $extension, $caseSensitive = false)
	{
		if (! is_string($path) || ! is_string($extension)) {
			throw new \Exception('Path and extension must be a string.');
		}
		if (! is_bool($caseSensitive)) {
			throw new \Exception('caseSensitive must be a bool.');
		}
		
		$path = realpath($path);
		if (! is_dir($path)) {
			throw new \Exception("$path must be a readable directory path!");
		}
		if (empty($extension)) {
			throw new \Exception('$extension cannot be an empty string.');
		}
		
		// Create an iterator to match the files requested
		$dirIt = new \RecursiveDirectoryIterator($path);
		$recursiveIt = new \RecursiveIteratorIterator($dirIt);
		$iterator = new \RegexIterator(
			$recursiveIt,
			'/^.+\.'.$extension.'$/'.(($caseSensitive)? '' : 'i'),
			\RecursiveRegexIterator::GET_MATCH
		);
		
		// Use the iterator to build the list of files matched
		$files = array();
		foreach ($iterator as $match) {
			array_push($files, $match[0]);
		}
		
		return $files;
	}
}