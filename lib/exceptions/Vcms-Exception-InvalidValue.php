<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2009  Andrew Crouse <amcrouse@victorycms.org>
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
 * VictoryCMS - InvalidValue
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Andrew Crouse <amcrouse@victorycms.org>
 * @filesource
 * @package Exceptions
 */

namespace Vcms\Exception;

/**
 * This represents an invalid value exception; thrown when a variable or paramter
 * is in the correct type, but an unexpected value. See InvalidType for when a
 * varaible or parameter is not in the correct data type.
 *
 * @package Exceptions
 */
class InvalidValue extends  \Vcms\Exception
{
	/**
	 * Constructs a new InvalidValue.
	 *
	 * @param string $variable paramter name which received invalid value.
	 * @param string $value    expected values.
	 */
	public function __construct($variable='variable', $value='required value')
	{
		parent::__construct('Could not set '.$variable.' to '.$value.'.');
	}
}
?>
