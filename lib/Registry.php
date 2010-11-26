<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010  Lewis Gunsch <lgunsch@victorycms.org>
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
 * VictoryCMS - Registry
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @author   Andrew Crouse <amcrouse@victorycms.org>
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
class Registry
{
	/** Array of key-value pairs */
	private $vars = array();

	/** Singleton instance to Registry */
	private static $instance;

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
		if (!isset(static::$instance)) {
			$c = __CLASS__;
			static::$instance = new $c;
		}
		return static::$instance;
	}

	/**
	 * Adds another value into a key-value binding pair.
	 * If the value is an array it is added or replaced into the array.
	 * Values that allready exists and are readonly can not be modified.
	 *
	 * @param $key The key to use to identify the value.
	 * @param $value The value to be bound to the key.
	 * @param $readonly Sets the value as read only protected.
	 * @return true on success; false otherwise.
	 */
	public static function add($key = null, $value = null, $readonly = false)
	{
		if ($key == null || $value == null) {
			return false;
		}
		$gl = static::getInstance();
		if (isset($gl->vars[$key])) {
			if ($gl->vars[$key]->isReadOnly()) {
				return false;
			} elseif ($readonly == true) {
				$gl->vars[$key] = new RegistryNode($value, true);
			} else {
				$gl->vars[$key] = new RegistryNode(array_merge($value,$gl->vars[$key]->getValue()), false);
			}
		} else {
			$gl->vars[$key] = new RegistryNode($value, $readonly);
		}
		return true;
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
	public static function attach($key, &$var, $readonly = false)
	{
		$existing = static::get($key);
		if (!empty($existing)) {
			throw new OverwriteException('Binding', $key);
		}
		$gl = static::getInstance();
		$gl->vars[$key] = new RegistryNode(&$var, $readonly);
	}

	/**
	 * Copy a new key-value binding into the registry.
	 * 
	 * @param $key The key to use to identify the binding.
	 * @param $value The value to be bound to the key.
	 * @return true if the value was set; false otherwise.
	 */
	public static function set($key, $value, $readonly = false)
	{
		if ($key == null || $value == null) {
			return false;
		}
		$gl = static::getInstance();
		if (!isset($gl->vars[$key]) || !($gl->vars[$key]->isReadOnly())) {
			$gl->vars[$bind] = new RegistryNode($value, $readonly);
		}
		return false;
	}

	/**
	 * Returns the value of a key-value pair.
	 *
	 * @param $key The name of the value to return.
	 * @return value The value that was bound to the key.
	 */
	public static function get($key)
	{
		$gl = static::getInstance();
		//TODO: check if NotFoundException can be loaded and use that if possible
		if (empty($gl->vars[$key])) {
			throw new \Exception("Key does not exist!");
		}
		return $gl->vars[$key]->getValue();
	}

	/**
	 * Unsets a key.
	 *
	 * @param $key The key used to identify the binding
	 * @return void
	 */
	public static function clear($key)
	{
		$gl = static::getInstance();
		unset($gl->vars[$key]);
	}

	// Prevent users to clone the instance
	public function __clone()
	{
		throw new VictoryCMS\Exception\SingletonCopyException;
	}
}
?>