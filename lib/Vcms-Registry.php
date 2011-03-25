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

namespace Vcms;

/**
 * This singleton class keeps key-value pairs saved in a similar fasion as $GLOBALS
 * without using the globals array; Use the set, add, and get methods to store
 * values or attach, and get methods to store values by reference.
 *
 * @package Core
 *
 */
class Registry
{
	/** Array of key-value pairs */
	private $vars = array();

	/** Singleton instance to Registry */
	protected static $instance;

	/**
	 * Private Registry constructor.
	 */
	protected function __construct()
	{
		
	}

	/**
	 * Returns an instance to Registry.
	 *
	 * @return Registry the registry instance
	 */
	public static function getInstance()
	{
		if(! isset(static::$instance)) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Adds another value into a key-value binding pair; value's added with this
	 * method will be returned as an array, even a single value. If the value is an
	 * array it is added or replaced into the array, values that already exist and
	 * are readonly can not be modified. Use the isReadOnly method to check if a key
	 * is marked read-only or not.
	 *
	 * @param $key The binding name or key to use to identify the binding.
	 * @param $value The value to be bound to the key.
	 * @param $readonly Sets the value as read only protected, default is false.
	 * 
	 * @throws \Vcms\Exception\DataException If key or value is null, or if
	 * readonly is not a boolean.
	 * @throws \Vcms\Exception\OverwriteException when trying to add to a
	 * binding that has been marked as read-only.
	 */
	public static function add($key, $value, $readonly = false)
	{
		/* this throws an exception to keep developers from ignoring the false return */
		if ($key === null || $value === null) {
			throw new \Vcms\Exception\DataException("Not null", "null", "key");
		}
		if (! is_bool($readonly)) {
			throw new \Vcms\Exception\DataException("bool", $readonly, '$readonly');
		}
		
		$gl = static::getInstance();
		
		if (array_key_exists($key, $gl->vars)) {
			// Key-value already exists, so merge in value if not read-only
			if ($gl->vars[$key]->isReadOnly()) {
				throw new \Vcms\Exception\OverwriteException('Binding', $key);
			}
			$valueArray = array_merge(
				$gl->vars[$key]->getValue(),
				is_array($value)? $value : array($value)
			);
		} else {
			// Key-value does not exist so create a new one
			$valueArray = is_array($value)? $value : array($value);
		}
		$gl->vars[$key] = new RegistryNode($valueArray, $readonly);
		return true;
	}

	/**
	 * Create a new key-value binding using a reference to the value; The value
	 * should be instantiated before calling attach.  Use the isReadOnly method to
	 * check if a key is marked read-only or not.
	 *
	 * @param $key The binding name or key to use to identify the binding.
	 * @param $value The object to be attached to the key.
	 * @param $readonly Sets the value as read only protected, default is false.
	 * 
	 * @throws \Vcms\Exception\DataException If key or value is null, or if
	 * readonly is not a boolean.
	 * @throws \Vcms\Exception\OverwriteException when trying to attach to a
	 * binding that has been marked as read-only.
	 */
	public static function attach($key, & $value, $readonly = false)
	{
		/* this throws an exception to keep developers from ignoring the false return */
		if ($key === null || $value === null) {
			throw new \Vcms\Exception\DataException("Not null", "null", "key");
		}
		if (! is_bool($readonly)) {
			throw new \Vcms\Exception\DataException("bool", $readonly, '$readonly');
		}
		
		$gl = static::getInstance();
		if (! array_key_exists($key, $gl->vars)
				|| ! $gl->vars[$key]->isReadOnly()) {
			$node = new RegistryNode(null);
			$node->setAttachedValue($value);
			$gl->vars[$key] = $node;
			return true;
		}
		throw new \Vcms\Exception\OverwriteException('Binding', $value);
	}

	/**
	 * Copy a new key-value binding into the registry or change an existing entry
	 * if the key-value binding is not read-only. Use the isReadOnly method to check
	 * if a key is marked read-only or not.
	 * 
	 * @param $key The binding name or key to use to identify the binding.
	 * @param $value The value to be bound to the key.
	 * @param $readonly Sets the value as read only protected, default is false.
	 * 
	 * @throws \Vcms\Exception\DataException If key or value is null, or if
	 * readonly is not a boolean.
	 * @throws \Vcms\Exception\OverwriteException when trying to set a
	 * binding that has been marked as read-only.
	 */
	public static function set($key, $value, $readonly = false)
	{
		/* these throw an exception to keep developers from ignoring the false return */
		if ($key === null || $value === null) {
			throw new \Vcms\Exception\DataException("Not null", "null", "key");
		}
		if (! is_bool($readonly)) {
			throw new \Vcms\Exception\DataException("bool", $readonly, '$readonly');
		}
		
		$gl = static::getInstance();
		if (! array_key_exists($key, $gl->vars) || ! ($gl->vars[$key]->isReadOnly())) {
			$gl->vars[$key] = new RegistryNode($value, $readonly);
			return true;
		}
		
		throw new \Vcms\Exception\OverwriteException('Binding', $key);
	}

	/**
	 * Returns the value of a key-value pair or if there is multiple values for
	 * the key then an array is returned; throws an exception \Exception
	 * if the key does not exist. Use the isKey method it check if a key is valid.
	 *
	 * @param $key The binding name or key to use to identify the binding.
	 * 
	 * @throws \Exception If the key does not exist.
	 * 
	 * @return value The value that was bound to the key.
	 */
	public static function get($key)
	{
		$gl = static::getInstance();
		
		/* this throws an exception to keep developers from ignoring the false return */
		if (! array_key_exists($key, $gl->vars)) {
			throw new \Exception("Key '$key' does not exist!");
		}
		
		return $gl->vars[$key]->getValue();
	}

	/**
	 * This returns true if the a key is read-only, and false if not. Use the isKey
	 * method it check if a key is valid.
	 * 
	 * @param $key The binding name or key to use to identify the binding.
	 * @throws \Vcms\Exception\DataException if $Key is null
	 * @throws \Exception If the key does not exist.
	 */
	public static function isReadOnly($key)
	{
		$gl = static::getInstance();
		
		/* these throw an exception to keep developers from ignoring the false return */
		if ($key === null) {
			throw new \Vcms\Exception\DataException("Not null", "null", "key");
		}
		if (! array_key_exists($key, $gl->vars)) {
			throw new \Exception("Key '$key' does not exist!");
		}
		
		return $gl->vars[$key]->isReadOnly();
	}
	
	/**
	 * This returns true if key is a valid key-value binding key, false if not.
	 * 
	 * @param bool $key true if $key is a valid key; false otherwise
	 */
	public static function isKey($key)
	{
		$gl = static::getInstance();
		
		if ($key == null) {
			return false;
		} elseif (! array_key_exists($key, $gl->vars)) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * Unsets a key-value binding if it is not read-only; this will throw an
	 * exception if the key-value binding is marked read-only. Use the isKey method
	 * to check if a key is valid, and the isReadOnly to check if it is marked as
	 * read-only.
	 *
	 * @param $key The binding name or key to use to identify the binding.
	 * 
	 * @throws \Vcms\Exception\OverwriteException when trying to clear a
	 * binding that has been marked as read-only.
	 * @throws \Exception If the key does not exist.
	 */
	public static function clear($key)
	{
		$gl = static::getInstance();
		
		/* these throw an exception to keep developers from ignoring the false return */
		if (! array_key_exists($key, $gl->vars)) {
			throw new \Exception("Key '$key' does not exist!");
		}
		if ($gl->vars[$key]->isReadOnly()) {
			throw new \Vcms\Exception\OverwriteException('Binding', $key);
		}
		
		unset($gl->vars[$key]);
		return true;
	}

	// Prevent users to clone the instance
	public function __clone()
	{
		throw new \Vcms\Exception\SingletonCopyException;
	}
}
?>