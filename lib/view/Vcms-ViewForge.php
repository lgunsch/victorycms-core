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
 * VictoryCMS - ViewForge
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
 * After receiving a properly formatted forgespec, this class will
 * instantiate all of the specified view objects, pass the proper parameters 
 * to those objects, and then renders those objects. It will then return
 * a final response object.
 *
 * @package View
 * @todo Implement cache() and purge()
 * @todo Test
 */
class ViewForge
{
	/** Singleton instance to ViewForge */
	private static $instance;
	
	/** User friendly error message */
	private static $errorMessage;
	
	/** Cacheable boolean */
	private static $cacheable = false;

	/**
	 * private constructor for singleton pattern
	 */
	private function __construct()
	{
	}

	/**
	 * The singleton functon for getting the object.
	 * 
	 * @return ViewForge object used to render views.
	 */
	public static function getInstance()
	{
		if (! isset(static::$instance)) {
			$c = __CLASS__;
			static::$instance = new $c;
			static::$errorMessage = '';
		}
		return static::$instance;
	}
	
	/**
	 * 
	 * The main forge function which receives a forgespec and
	 * instantiates all view objects while building a Vcms Response
	 * object. If everything goes well, it will render the view
	 * objects and return the final vcms response with status code.
	 * 
	 * @param  A JSON formatted string  $forgeSpec 
	 * @return VcmsResponse object
	 */
	public static function forge($forgeSpec)
	{
		if (! function_exists('json_decode')) {
			static::$errorMessage = "JSON PHP extension is required.\n";
			throw new \Exception('ViewForge requires json_decode function!');
		}
		
		$contents = FileUtils::removeComments($forgeSpec);
		$json = json_decode($contents, true);
		
		if ($json === null) {
			static::$errorMessage = "ForgeSpec cannot be decoded.";
			throw new \Exception('Configuration file cannot be decoded.');
		}
		
		/* list of objects to render after checking their mime types, etc */
		$objects_to_render = array();
		
		/* Vcms Response */
		$response = new VcmsResponse(200, 'OK', null, null);
		$response_body = ""; 
		
		/* A string to store the last content type to make sure they all match */
		$last_content_type = null;
		
		/* A forgeSpec is an array of objects named 'objects', each object
		 * in the array having a 'name' key and an optional 'param' key. 
		 */
		foreach ($json as $key => $value) {
			if (($key == 'objects') && is_array($value)) {
				foreach ($value as $object) {
					
						$params = (isset($object["params"]))? $object["params"] : null;
						$class = (isset($object["name"]))? $object["name"] : null;
						
						if (is_null($class)) throw new \Exception(
							'Improperly formatted ForgeSpec'
						);
						
						$reflection = new \ReflectionClass($class);
						$constructor = $reflection->getConstructor();
                
						if ($constructor == null || $constructor->isPrivate() || $constructor->isProtected()) {
						    throw new \Exception('Can not instantiate view object');
						}
						
						if (! is_subclass_of($class, "\Vcms\VcmsView")) {
							throw new \Exception('View object does not extend VcmsView');
						}
						
						
				
						$instance = new $class($params);
						
						$objects_to_render[] = $instance;
						
						/* Ensure all view objects have the same content type */
						$this_content_type = $instance->getContentType();
						if ($last_content_type == null) {
							$last_content_type = $this_content_type;
							$response->setContentType($this_content_type);
						}
						
						if (! strcmp($last_content_type, $this_content_type) == 0) {
							$response->setStatusCode(500);
							$response->setStatusMessage("Internal Server Error");
							$response->setContentType(null);
							$response_body = null;
						} else {
							$response_body .= $instance->getBody();
						}
						
						if (! $instance->isCacheable()) {
							static::$cacheable = false;
						}
					}
					
					$response->setBody($response_body);
			} else{
				throw new \Exception('Improperly formatted ForgeSpec');
			}
		}
		
		/* render all of the objects */
		if ($response->getStatusCode() === 200) {
			foreach ($objects_to_render as $object) {
				$object->render();
			}
		}
		
		return $response;
	}
	
	/**
	 * 
	 * Returns true if all of the rendered views are cacheable.
	 * 
	 * @return boolean
	 */
	public static function isCacheable()
	{
		return static::$cacheable;
	}
	
	/**
	 * 
	 * Caches the views
	 */
	public static function cache()
	{
		//TODO: implement
		/*
		 * This will simply call cache on all of the view objects, if and only if
		 * all of them are cacheable.
		 */
	}
	
	/**
	 * 
	 * Purges the cached views
	 */
	public static function purge()
	{
		//TODO: implement
		/*
		 * This will simply call purge on all of the view objects that are cacheable,
		 * and not on any that are not cacheable.
		 * 
		 */
	}
	
	/**
	 * Returns the user friendly error message for the last error.
	 * 
	 * @return string Last error message in user friendly format
	 */
	public static function getUserErrorMessage()
	{
		return static::$errorMessage;
	}
	
	/**
	 * Preventing cloning of this class
	 */
	public function __clone()
	{
		throw new \Vcms\Exception\SingletonCopyException;
	}
}
?>