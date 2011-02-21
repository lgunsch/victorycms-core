<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010,2011  Lewis Gunsch <lgunsch@victorycms.org>
//  Copyright (C) 2010,2011  Mitchell Bosecke <mitchellbosecke@gmail.com>
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
 * VictoryCMS - VcmsResponse
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Mitchell Bosecke <mitchellbosecke@gmail.com>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace Vcms;

/**
 * An object use to pass messages between Vcms classes
 *
 * @package Core
 * @todo Test
 */


class VcmsResponse
{
	/**
	 * Status code:
	 * 0 - success
	 * 1 - failure
	 */
	private status_code;
	
	/**
	 * Status message
	 */
	private status_message;
	
	/**
	 * Content type
	 */
	private content_type;
	
	/**
	 * Body of the response
	 */
	private body;
	
	/**
	 * 
	 * Constructor of a VcmsResponse object
	 * @param int $status_code
	 * @param string $status_message
	 * @param string $content_type
	 * @param mixed $body
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function __construct($status_code, $status_message, $content_type, $body){
		
		if ($status_code === null || $status_message === null || $content_type === null || $body === null) {
			throw new \Vcms\Exception\DataException("Not null", "null", "VcmsResponse parameter");
		}
		if (! is_int($status_code) ) {
			throw new \Vcms\Exception\DataTypeException("Int", $status_code, '$status_code');
		}
		if (! is_string($status_message) ) {
			throw new \Vcms\Exception\DataTypeException("String", $status_message, '$status_message');
		}
		if (! is_string($content_type) ) {
			throw new \Vcms\Exception\DataTypeException("String", $content_type, '$content_type');
		}
		$this->$status_code = $status_code;
		$this->$status_message = $status_message;
		$this->$content_type = $content_type;
		$this->$body = $body;
		
	}
	
	/**
	 * Returns the status code of the VcmsResponse.
	 * 
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getStatusCode(){
		return $this->status_code;
	}
	
	/**
	 * Returns the status message of the VcmsResponse.
	 * 
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getStatusMessage(){
		return $this->status_message;
	}
	
	/**
	 * Returns the content type of the VcmsResponse.
	 * 
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getContentType(){
		return $this->content_type;
	}
	
	/**
	 * Returns the body of the VcmsResponse.
	 * 
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getBody(){
		return $this->body;
	}
	
	
	
}