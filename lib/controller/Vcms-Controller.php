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
 * VictoryCMS - Controller
 *
 * @filesource
 * @category VictoryCMS
 * @package  Controller
 * @author   Mitchell Bosecke <mitchellbosecke@gmail.com>
 * @license  GPL http://www.gnu.org/licenses/gpl.html
 * @link     http://www.victorycms.org/
 */

namespace Vcms;

/**
 * This is an abstract class for a Vcms Controller
 *
 */
abstract class Controller
{
	/**
	 * Constructor for a controller object
	 */
	function __construct(){}
	
	/**
	 * The main function for a controller
	 */
	abstract public function process();
	
	/**
	 * 
	 * Returns true if the view can be cached.
	 */
	public function isCacheable()
	{
		return false;
	}
	
	/**
	 * 
	 * Caches the controller response; this function will not cache the results, but
	 * is here so that you may extend it to provide cacheing capability if you need.
	 */
	public function cache()
	{
		/*
		 * This is an empty implementation so that cacheing capability is not
		 * required, but may be implemented.
		 */
	}
	
	/**
	 * 
	 * Purges the cache; this function will not purge the cache, but is here so that
	 * you may extend it to provide cacheing capability if you need.
	 */
	public function purge()
	{
		/*
		 * This is an empty implementation so that cacheing capability is not
		 * required, but may be implemented.
		 */
	}
}
?>