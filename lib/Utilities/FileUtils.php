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
	 * Recursively loads any PHP files in the directory $path or in any number of
	 * sub-folder's underneath the $path including hidden files.
	 * 
	 * @example
	 * $files = FileUtils::findPHPFiles('/etc');
	 *	foreach ($files as $name => $object) {
	 *		echo "$name\n";
	 *	}
	 * 
	 * @param string $path Directory path from which to load PHP files
	 * 
	 * @return array containing a single index for each PHP file.
	 */
	public static function findPHPFiles($path)
	{
		$path = realpath($path);
		if (! is_dir($path)) {
			\Exception("$path must be a directory path!");
		}
		$dirIt = new \RecursiveDirectoryIterator($path);
		$recursiveIt = new \RecursiveIteratorIterator($dirIt);
		$files = new \RegexIterator($recursiveIt, '/^.+\.php$/i',
			\RecursiveRegexIterator::GET_MATCH);
		return $files;
	}
}