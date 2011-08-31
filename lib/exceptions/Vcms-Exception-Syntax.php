<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2011  Lewis Gunsch <lgunsch@victorycms.org>
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
 * VictoryCMS - Syntax
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Andrew Crouse <amcrouse@victorycms.org>
 * @filesource
 * @package Exceptions
 */

namespace Vcms\Exception;

/**
 * This represents an Syntax exception. This is usually thrown whenever a
 * configuration file has syntax errors.
 *
 * @package Exceptions
 */
class Syntax extends  \Vcms\Exception
{
	/**
	 * Constructs a new Syntax.
	 *
	 * @param string $class name requested.
	 */
	public function __construct($component = 'Unknown')
	{
		parent::__construct('Syntax error found in '.$component.'.');
	}
}
