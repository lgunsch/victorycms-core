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
 * VictoryCMS - RegistryNode
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Andrew Crouse <amcrouse@victorycms.org>
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace VictoryCMS;

/**
 * This class keeps a value of the registry along with if it is read only or not.
 *
 * @package Core
 *
 */
class RegistryNode
{
	/** Stored value */
	private $value;
	 
	/** Boolean read only flag */
	private $readonly;

	/**
	 * Registry Node constructor.
	 * 
	 * @param mixed $value Value to set the RegistryNode to.
	 * @param bool $readonly sets the RegistryNode to read-only
	 * 
	 * @throws \VictoryCMS\Exception\DataException if $readonly is not a bool value.
	 */
	public function __construct($value, $readonly = false)
	{
		$this->value = $value;
		if (! is_bool($readonly)) {
			throw new \VictoryCMS\Exception\DataException("bool", "$readonly", 'readonly');
		}
		$this->readonly = $readonly;
	}

	/**
	 * Returns the value of the RegistryNode.
	 *
	 * @return Returns the value of the RegistryNode.
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Sets the value of the RegistryNode; this will throw an Overwrite exception
	 * if the value is marked as read-only.
	 *
	 * @param mixed $value Value to set the RegistryNode to.
	 * 
	 * @throws \VictoryCMS\Exception\OverwriteException If the value is read-only.
	 */
	public function setValue($value)
	{
		if ($this->readonly === false) {
			$this->value = $value;
			return true;
		}
		/* this throws an exception to keep developers from ignoring a false return */
		throw new \VictoryCMS\Exception\OverwriteException('Binding', $value);
	}

	/**
	 * Sets the value of the node using a reference to the value; The value
	 * should be instantiated before calling attach. This will also throw
	 * an Overwrite exception if the value is marked as read-only.
	 * 
	 * @param mixed $objAddress Value to set the RegistryNode reference to.
	 * 
	 * @throws \VictoryCMS\Exception\OverwriteException If the value is read-only.
	 */
	public function setAttachedValue(& $objAddress)
	{
		if ($this->readonly === false) {
			$this->value = & $objAddress;
			return true;
		}
		/* this throws an exception to keep developers from ignoring a false return */
		throw new \VictoryCMS\Exception\OverwriteException('Binding', $value);
	}
	
	/**
	 * Sets the RegistryNode to read-only.
	 * 
	 * @return void
	 */
	public function setReadOnly()
	{
		$this->readonly = true;
	}

	/**
	 * Returns if the value is read-only or not.
	 *
	 * @return true if the value is read-only; false otherwise.
	 */
	public function isReadOnly()
	{
		return $this->readonly;
	}
}
?>