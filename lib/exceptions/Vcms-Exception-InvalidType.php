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
 * VictoryCMS - InvalidType
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Andrew Crouse <amcrouse@victorycms.org>
 * @filesource
 * @package Exceptions
 */

namespace Vcms\Exception;

/**
 * This represents an invalid type exception; thrown when a variable or paramter
 * is in the incorrect type. See InvalidValue for when a variable or paramter is
 * set to an unexpected value.
 *
 * @package Exceptions
 */
class InvalidType extends  \Vcms\Exception
{
	/**
	 * Constructs a new InvalidType.
	 *
	 * @param string $expected valid input type.
	 * @param string $got      invalid input type received.
	 * @param string $for      paramter name which received invalide input type.
	 */
	public function __construct($expected='valid data type', $got='invalid data type',
		$for='variable'
	) {
		parent::__construct('Expected '.$expected.', recevied '.$got.' for '.$for.'.');
	}
}
