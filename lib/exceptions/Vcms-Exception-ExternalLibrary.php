<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2009,2011 Andrew Crouse <amcrouse@victorycms.org>
// 	Copyright (C) 2010,2011	Mitchell Bosecke <mitchellbosecke@gmail.com>
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
 * VictoryCMS - ExternalLibrary
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Mitchell Bosecke <mitchellbosecke@gmail.com>
 * @filesource
 * @package Exceptions
 */

namespace Vcms\Exception;

/**
 * Finish documentation.
 *
 * @todo Documentation.
 *
 * @package Exceptions
 */
class ExternalLibrary extends  \Vcms\Exception
{
	/**
	 * Constructs a new ExternalLibrary.
	 *
	 * @param string $library name requested to be loaded.
	 */
	public function __construct($library = 'Unknown')
	{
		parent::__construct('The external library, '.$library.', could not be properly loaded');
	}
}
