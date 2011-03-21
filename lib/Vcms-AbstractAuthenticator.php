<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010,2011  Lewis Gunsch <lgunsch@victorycms.org>
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
 * VictoryCMS - AbstractAuthenticator
 *
 * @filesource
 * @category VictoryCMS
 * @package  Core
 * @author   Lewis Gunsch <lgunsch@victorycms.org>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace Vcms;

/**
 * This abstract class may be extended to provide user authentication; this will be
 * processed before the application front controller is processed.
 *
 * @package Core
 */
abstract class AbstractAuthenticator
{
	const ROOT = 0;
	const AUTHENTICATED = 1;
	const ANONYMOUS = 2;
	
	/** Singleton instance to Autoloader */
	protected static $instance;
	
	/**
	 * protected constructor; prevents direct creation of Authenticator object. This
	 * may be extended to allow for proper object construction. 
	 */
	protected function __construct()
	{

	}

	/**
	 * The singleton functon for getting the Authenticator object.
	 * @return Autoloader Object used to autoload classes.
	 */
	public static function getInstance()
	{
		if (! isset(static::$instance)) {
			$c = __CLASS__;
			static::$instance = new $c;
		}
		return static::$instance;
	}
	
	/**
	 * 
	 * 
	 */
	abstract public function process();
	
	/**
	 * 
	 * 
	 */
	public function getState()
	{
		//TODO: implement me
	}
	
	/**
	 * 
	 * 
	 */
	public static function isAuthenticated()
	{
		$auth = static::getInstance();
		//TODO: implement me
	}
	
	/**
	 * 
	 * 
	 */
	public static function isRoot()
	{
		$auth = static::getInstance();
		//TODO: implement me
	}
	
	/**
	 * Disables the clone of this class.
	 */
	public function __clone()
	{
		throw new \Vcms\Exception\SingletonCopyException;
	}
}
?>