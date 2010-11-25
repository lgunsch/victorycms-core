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
//  the Free Software Foundation, either version 3 of the License, or
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
 * VictoryCMS - Registry
 * 
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace VictoryCMS;

/**
 * This singleton class keeps key-value pairs saved in a similar 
 * fasion as $GLOBALS without using the globals array.
 *
 * @package Core
 * 
 */
use VictoryCMS\Exception\NotFoundException;

class Registry
{
	/** Array of key-value pairs */
	private $vars = array();
	
	/** Singleton instance to Registry */
	static $instance = null;

	/**
	 * Private Registry constructor.
	 */
	private function __construct()
	{
	
	}

	/** 
	 * Returns an instance to Registry.
	 * 
	 * @return Registry
	 */
	public static function getInstance()
	{
		if(static::$instance == null) {
			static::$instance = new Registry;
		}
		return static::$instance;
	}

	/**
	 * Create a new key-value binding using a 
	 * reference to the value. The value should 
	 * be instantiated before calling attach.
	 * 
	 * @param $bind The binding name or key to use to identify the binding.
	 * @param $var The address of the value to be bound to the key.
	 * @throws OverwriteException when trying to attach a binding that has already been set.
	 * @return void
	 */
	public static function attach($bind, &$var)
	{	
		$existing = static::get($bind);
		if(! empty($existing)) {
			throw new OverwriteException('Binding', $bind);
		}
		
		$gl = static::getInstance();
		$gl->vars[$bind] = &$var;
	}

	/**
	 * Copy a new key-value binding into the variable array.
	 * @param $bind The binding name or key to use to identify the binding
	 * @param $value The value to be bound to the key.
	 * @return void
	 */
	public static function set($bind,$value)
	{
		// I think resetting a variable should be allowd?
		// Maybe we could add  a constant modifier options to 
		// a binding to dissallow resetting a binding when
		// it is read only
		// 
		//if(! empty(static::get($bind))) {
		//	throw new OverwriteException('Binding', $bind);
		//}
		
		$gl = static::getInstance();
		$gl->vars[$bind] = $value;
	}

	/**
	 * Returns the value of a key-value binding pair.
	 * 
	 * @param $bind The value of the key binding to return.
	 * @return value The value to be bound to the key.
	 */
	public static function get($bind)
	{
		$gl = static::getInstance();
		//TODO: check if NotFoundException can be loaded and use that if possible
		if (empty($gl->vars[$bind])) {
			throw new \Exception("Key does not exist!");
		}
		return $gl->vars[$bind];
	}

	/**
	 * Adds another value into a key-value binding pair.
	 * If the value is an array it is added into the array, and
	 * only if it is not already in the array.
	 * 
	 * @param $bind The binding name or key to use to identify the binding
	 * @param $value The value to be bound to the key.
	 * @return void
	 */
	public static function add($bind,$value)
	{
		$gl = static::getInstance();
		if(is_array($gl->vars[$bind])) {
			if(!in_array($value,$gl->vars[$bind])) {
				$gl->vars[$bind][] = $value;
			}
		} else {
			$gl->vars[$bind] .= $value;
		}
	}
	
	/**
	 * Unsets a binding.
	 * 
	 * @param $bind The binding name or key to use to identify the binding
	 * @return void
	 */
	public static function clear($bind)
	{
		$gl = static::getInstance();
		unset($gl->vars[$bind]);
	}
	
	// Prevent users to clone the instance
	public function __clone()
	{
		throw new VictoryCMS\Exception\SingletonCopyException;
	}
}
?>
