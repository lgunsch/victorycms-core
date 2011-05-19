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
 * VictoryCMS - SingletonCopy
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Andrew Couse <amcrouse@victorycms.org>
 * @filesource
 * @package Exceptions
 */

namespace Vcms\Exception;

/**
 * This represents an invalid singleton copy exception.
 *
 * @package Exceptions
 */
class SingletonCopy extends \Vcms\Exception
{
	/**
	 * Constructs a new SingletonCopy.
	 */
	public function __construct()
	{
		parent::__construct('Clone is not allowed on a singleton.');
	}
}
?>
