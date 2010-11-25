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
 * VictoryCMS - RegistryNode
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
 * This class keeps value of the registry along with if it is read only or not.
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
	 */
	public function __construct($value, $readonly = false)
	{
		$this->value = $value;
		if ($readonly == true) {
			$this->readonly = true;
		} else {
			$this->readonly = false;
		}
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
	 * Sets the value of the RegistryNode if the node is not read only.
	 *
	 * @param $value Value to set the RegistryNode to.
	 * @return true if the value has been set; false otherwise.
	 */
	public function setValue($value)
	{
		if ($this->readonly == false) {
			$this->value = $value;
			return true;
		}
		return false;
	}

	/**
	 * Sets the RegistryNode to read only.
	 * @return void
	 */
	public function setReadOnly()
	{
		$this->readonly = true;
	}

	/**
	 * Returns if the value is read only or not.
	 *
	 * @return true if the value is read only; false otherwise.
	 */
	public function isReadOnly()
	{
		return $this->readonly;
	}
}
?>