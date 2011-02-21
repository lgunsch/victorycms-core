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
 * VictoryCMS - ViewForge
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
 * After receiving a properly formatted forgespec, this class will
 * instantiate all of the specified view objects, pass the proper parameters 
 * to those objects, and then renders those objects. It will then return
 * a final response object.
 *
 * @package Core
 * @todo Finish implementing
 * @todo Test
 */


class ViewForge
{
	
	/** Singleton instance to ViewForge */
	private static $instance;
	
	/** User friendly error message */
	private static $errorMessage;
	
	/** Cacheable boolean */
	private static $cacheable = false;

	/**
	 * private constructor for singleton pattern
	 */
	private function __construct()
	{
	}

	/**
	 * The singleton functon for getting the object.
	 * 
	 * @return ViewForge object used to render views.
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
	 * 
	 * The main forge function which receives a forgespec and
	 * initiates/renders all of the necessary objects then returns
	 * a response object.
	 * 
	 * @param  $forgeSpec A JSON formatted string 
	 * @return VcmsResponse object
	 */
	public static function forge($forgeSpec){
		
	}
	
	/**
	 * 
	 * Returns true if all of the rendered views are cacheable.
	 * 
	 * @return boolean
	 */
	public static function isCacheable(){
		return static::$cacheable;
	}
	
	/**
	 * 
	 * Caches the views
	 */
	public static function cache(){
		//TODO: implement
	}
	
	/**
	 * 
	 * Purges the cached views
	 */
	public static function purge(){
		//TODO: implement
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
	 * Preventing cloning of this class
	 */
	public function __clone()
	{
		throw new \Vcms\Exception\SingletonCopyException;
	}
	
	
}
?>