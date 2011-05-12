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
 * @package  View
 * @author   Mitchell Bosecke <mitchellbosecke@gmail.com>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace Vcms;

/**
 * An object use to pass messages between Vcms classes
 *
 * @package View
 * @todo Test
 */
class VcmsResponse
{
	/**
	 * Response status code.
	 */
	private $status_code;

	/**
	 * Status message
	 */
	private $status_message;

	/**
	 * Content type
	 */
	private $content_type;

	/**
	 * Body of the response
	 */
	private $body;

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
	public function __construct($status_code, $status_message, $content_type, $body)
	{

		if ($status_code === null || $status_message === null) {
			throw new \Vcms\Exception\DataException("Not null", "null", "VcmsResponse parameter");
		}
		if (! is_int($status_code) ) {
			throw new \Vcms\Exception\DataTypeException("Int", $status_code, '$status_code');
		}

		$this->status_code = $status_code;
		$this->status_message = $status_message;
		$this->content_type = $content_type;
		$this->body = $body;
	}

	/**
	 * Returns the status code of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getStatusCode()
	{
		return $this->status_code;
	}

	/**
	 * Returns the status message of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getStatusMessage()
	{
		return $this->status_message;
	}

	/**
	 * Returns the content type of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getContentType()
	{
		return isset($this->content_type)? $this->content_type : null;
	}

	/**
	 * Returns the body of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function getBody()
	{
		return isset($this->body)? $this->body : null;
	}

	/**
	 * Sets the status code of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function setStatusCode($status_code)
	{
		$this->status_code = $status_code;
	}

	/**
	 * Sets the status message of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function setStatusMessage($status_message)
	{
		$this->status_message = $status_message;
	}

	/**
	 * Sets the content type of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function setContentType($content_type)
	{
		$this->content_type = $content_type;
	}

	/**
	 * Sets the body of the VcmsResponse.
	 *
	 * @throws \Vcms\Exception\DataException
	 * @throws \Vcms\Exception\DataTypeException
	 */
	public function setBody($body)
	{
		$this->body = $body;
	}

	/**#@+
	 *
     * HTTP status codes.
     * @var string
     */
	const HTTP_100='Continue';
	const HTTP_101='Switching Protocols';
	const HTTP_200='OK';
	const HTTP_201='Created';
	const HTTP_202='Accepted';
	const HTTP_203='Non-Authorative Information';
	const HTTP_204='No Content';
	const HTTP_205='Reset Content';
	const HTTP_206='Partial Content';
	const HTTP_300='Multiple Choices';
	const HTTP_301='Moved Permanently';
	const HTTP_302='Found';
	const HTTP_303='See Other';
	const HTTP_304='Not Modified';
	const HTTP_305='Use Proxy';
	const HTTP_306='Temporary Redirect';
	const HTTP_400='Bad Request';
	const HTTP_401='Unauthorized';
	const HTTP_402='Payment Required';
	const HTTP_403='Forbidden';
	const HTTP_404='Not Found';
	const HTTP_405='Method Not Allowed';
	const HTTP_406='Not Acceptable';
	const HTTP_407='Proxy Authentication Required';
	const HTTP_408='Request Timeout';
	const HTTP_409='Conflict';
	const HTTP_410='Gone';
	const HTTP_411='Length Required';
	const HTTP_412='Precondition Failed';
	const HTTP_413='Request Entity Too Large';
	const HTTP_414='Request-URI Too Long';
	const HTTP_415='Unsupported Media Type';
	const HTTP_416='Requested Range Not Satisfiable';
	const HTTP_417='Expectation Failed';
	const HTTP_500='Internal Server Error';
	const HTTP_501='Not Implemented';
	const HTTP_502='Bad Gateway';
	const HTTP_503='Service Unavailable';
	const HTTP_504='Gateway Timeout';
	const HTTP_505='HTTP Version Not Supported';
}