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
 * processed before the application front controller is processed. In you
 * configuration file you should set the 'authenticator' key to the fully qualified
 * class name of your class, including the namespace, which should extend from this
 * class. In your process method you are required to change the state to one of
 * ROOT, AUTHENTICATED, or ANONYMOUS as you see fit. After, other classes may use
 * <your class>::isAuthenticated() and <your class>::getState() to check for user
 * authentication and current privilege level, or also you can check for root level
 * privileges with <your class>::isRoot().
 *
 * @package Core
 */
abstract class AbstractAuthenticator
{
	/**
	 * Represents root level privileges for the currently authenticated user.
	 * 
	 * @example static::$state = static::ROOT;
	 */
	const ROOT = 0;
	
	/**
	 * Represents user level privileges for the currently authenticated user.
	 * 
	 * @example static::$state = static::AUTHENTICATED;
	 */
	const AUTHENTICATED = 1;
	
	/**
	 * Represents guest level privileges for the currently anonymous user.
	 * 
	 * @example static::$state = static::ANONYMOUS;
	 */
	const ANONYMOUS = 2;
	
	/**
	 * Current state of authentication; either ROOT, AUTHENTICATED, or ANONYMOUS.
	 * This should be properly updated by the implementing class after the process
	 * method has been called.
	 * 
	 * @example static::$state = static::AUTHENTICATED;
	 */
	protected static $state;
	
	/** Singleton instance to the authenticator */
	protected static $instance;
	
	/**
	 * protected constructor; prevents direct creation of authenticator object. This
	 * may be extended to allow for proper object construction. 
	 */
	protected function __construct()
	{
		static::$state = static::ANONYMOUS;
	}

	/**
	 * The singleton functon for getting the Authenticator object.
	 * @return Autoloader Object used to autoload classes.
	 */
	public static function getInstance()
	{
		if(! isset(static::$instance)){
			static::$instance = new static();
		}
		return static::$instance;
	}
	
	/**
	 * @todo: I require detailed documentation.
	 * 
	 */
	abstract public function process();
	
	/**
	 * Returns the current authentication state for this session, this can be
	 * AUTHENTICATED, ANONYMOUS, or ROOT.
	 * 
	 * @return current authentication state: ANONYMOUS, AUTHENTICATED, or ROOT.
	 */
	public static function getState()
	{
		return static::$state;
	}
	
	/**
	 * Returns true if user is authenticated, and false if anonymous,
	 * and is not authenticated yet.
	 * 
	 * @return bool true if user is authenticated, false if anonymous.
	 */
	public static function isAuthenticated()
	{
		if (static::$state === ROOT || static::$state === static::AUTHENTICATED) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns true if the currently authenticated user has root level privileges,
	 * and false if not.
	 * 
	 * @return bool true if authenticated user has root privileges, false if not.
	 */
	public static function isRoot()
	{
		if (static::$state === static::ROOT) {
			return true;
		} else {
			return false;
		}
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