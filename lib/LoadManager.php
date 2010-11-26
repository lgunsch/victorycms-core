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
 * VictoryCMS - LoadManager
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
 * This class loads the Registry with information 
 * passed in from a settings file.
 *
 * @package Core
 * @todo Finish implementing
 * @todo Test
 */
class LoadManager
{
	
	/** Singleton instance to LoadManager */
	private static $instance;
		
	/**
	 * private constructor; prevents direct creation of object.
	 */
	private function __construct()
	{
		
	}

	/**
	 * The singleton functon for getting the object.
	 * @return LoadManager Object used to add items to the Registry.
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
	 * Loads a file into the Registry.
	 * 
	 * @param $path To the file to load.
	 */
	public static function Load($path)
	{
		$contents = file_get_contents($path);
		if (!function_exists('json_decode') || $contents == false) {
			return false;
		}
		$json = json_decode(utf8_encode($contents));
		if($json == null){
			return false;
		}
		foreach ($json as $key => $value) {
			if ($key == 'load') {
				$locations = Registry::get('load');
				foreach ($key as $item) {
					if (!in_array($item, $locations)) {
						Registry::add('load', $item, false);
						static::Load($item);
					}
				}
			}
			elseif (isset($json->$key->value)) {
				if (isset($json->$key->readonly)) {
					Registry::add($key, ($json->$key->value), $json->$key->readonly);
				} else {
					Registry::add($key, ($json->$key->value), false);
				}
			}
		}
	}
}
?>