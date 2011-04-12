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
 * processed before the application front controller is processed and the user
 * authenticated will become the owner of this session. The current state of
 * authentication may be polled by the isRoot and isAuthenticated methods.
 * 
 * In you configuration file you should set the 'authenticator' key to the fully
 * qualified class name of your class, including the namespace, which should extend
 * from this class. You should also override the process method with your own process
 * method.
 * 
 * In your process method you are required to change the state to one of ROOT,
 * AUTHENTICATED, or ANONYMOUS as you see fit. After, other classes may use
 * <your class>::isAuthenticated() and <your class>::getState() to check for user
 * authentication and current privilege level, or also you can check for root level
 * privileges with <your class>::isRoot().
 *
 * @package Core
 */
abstract class AbstractAuthenticator
{
	/**
	 * Represents root level privileges.
	 * 
	 * @example static::$state = static::ROOT;
	 */
	const ROOT = 0;
	
	/**
	 * Represents user level privileges.
	 * 
	 * @example static::$state = static::AUTHENTICATED;
	 */
	const AUTHENTICATED = 1;
	
	/**
	 * Represents guest level privileges.
	 * 
	 * @example static::$state = static::ANONYMOUS;
	 */
	const ANONYMOUS = 2;
	
	/**
	 * Current state of user authentication for the owner of this session; either
	 * ROOT, AUTHENTICATED, or ANONYMOUS. This should be properly updated by the
	 * implementing class after the process method has been called.
	 * 
	 * @example static::$state = static::AUTHENTICATED;
	 */
	protected static $state = self::ANONYMOUS;
	
	/**
	 * Process method called by 
	 * 
	 * @todo: I require detailed documentation.
	 */
	public static function process()
	{
		/*
		 * This function must be extended to provide authentication functionality.
		 */
	}
	
	/**
	 * Returns the current authentication state for the user who owns this session,
	 * this can be AUTHENTICATED, ANONYMOUS, or ROOT.
	 * 
	 * @return current authentication state: ANONYMOUS, AUTHENTICATED, or ROOT.
	 */
	public static function getState()
	{
		return static::$state;
	}
	
	/**
	 * Returns true if user is authenticated, and false if anonymous and not
	 * authenticated yet; this only checks the authentication state of the user
	 * who owns the session, not any instantiated user.
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
	 * and false if not; this only checks the authentication state of the user
	 * who owns the session, not any instantiated user.
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
}
?>