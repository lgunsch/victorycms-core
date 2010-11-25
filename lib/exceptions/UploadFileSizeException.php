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
 * VictoryCMS - UploadFileSizeException
 * 
 * @license http://www.gnu.org/licenses/gpl.html
 * @author Anderw Crouse <amcrouse@victorycms.org>
 * @filesource
 * @package Exceptions
 */

namespace VictoryCMS\Exception;

/**
 * This represents an invalid uploaded file size exception. This is
 * usually thrown whenever a file is uploaded that is larger then the
 * maximum allowable.
 *
 * @package Exceptions
 */
class UploadFileSizeException extends  \VictoryCMS\Exception
{
	/**
	 * Constructs a new UploadFileSizeException.
	 */
	public function __construct()
	{	
		parent::__construct('Maximum file upload size exceeded.');
	}
}
?>
