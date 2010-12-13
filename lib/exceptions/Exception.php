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
 * VictoryCMS - Exception
 * 
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Andrew Crouse <amcrouse@victorycms.org>
 * @filesource
 * @package Exceptions
 */

namespace VictoryCMS;

/**
 * This is a Exception class that is thrown by VictoryCMS
 *
 * @package Exceptions
 */
class Exception extends \Exception
{
	/**
	 * Constructs a new VictoryCMS Exception.
	 */
	public function __construct($message, $code=0)
	{
		parent::__construct($message, $code);
	}

	/**
	 * Returns the exception in a string.
	 */
	public function __toString()
	{
		return __CLASS__.':['.$this->code.']: '.$this->message.'\n';
	}
}
?>
