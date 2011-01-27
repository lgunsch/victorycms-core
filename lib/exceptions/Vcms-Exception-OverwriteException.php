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
 * VictoryCMS - OverwriteException
 * 
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Andrew Crouse <amcrouse@victorycms.org>
 * @filesource
 * @package Exceptions
 */

namespace Vcms\Exception;

/**
 * This represents a overwrite Exception. This is usually thrown 
 * whenever a object is asked to overwrite an value. This can
 * usually be fix by unsetting the object before assigning to it.
 *
 * @package Exceptions
 */
class OverwriteException extends  \Vcms\Exception
{
	/**
	 * Constructs a new OverwriteException.
	 */
	public function __construct($type='Class', $name='Var')
	{
		parent::__construct($type.'.'.$name.' already exists, clear '.$type.'.'.$name.' before setting it again.');
	}
}
?>
