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
 * VictoryCMS - VcmsView
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
 * This is an abstract class for a Vcms View
 *
 * @package View
 */
abstract class VcmsView
{
	
	abstract public function __construct($params);
	/**
	 * 
	 * Renders the view using echo().
	 * @param Array of parameters needed to render the view $params
	 */
	abstract protected function render();
	
	/**
	 * 
	 * Returns the body of the view instead of rendering it.
	 */
	abstract protected function getBody();
	
	/**
	 * 
	 * Returns the content-type of the view.
	 */
	abstract protected function getContentType();
	
	/**
	 * 
	 * Returns true if the view can be cached; the default is false and this can be
	 * overridden if a VcmsView has caching capability.
	 */
	protected function isCacheable()
	{
		return false;
	}
	
	/**
	 * 
	 * Caches the view; this function will not cache the view, but is here so that
	 * you may extend it to provide cacheing capability.
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
	 * you may extend it to provide cacheing capability.
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