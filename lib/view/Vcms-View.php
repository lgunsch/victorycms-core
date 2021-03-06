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
abstract class View
{

	/**
	 * Construct a new VcmsView object.
	 *
	 * @param array $params required to render view.
	 */
	abstract public function __construct($params);

	/**
	 * Returns a string representation of the view.
	 *
	 * @return string result of rendered view.
	 */
	abstract public function render();

	/**
	 * Returns the content-type of the view.
	 *
	 * @return string view mime type.
	 */
	abstract public function getContentType();

	/**
	 * Returns true if the view can be cached; the default is false and this can be
	 * overridden if a VcmsView has caching capability.
	 *
	 * @return boolean true if view is cacheable.
	 */
	public function isCacheable()
	{
		return false;
	}

	/**
	 * Caches the view; this function will not cache the view, but is here so that
	 * you may extend it to provide cacheing capability.
	 *
	 * @return void
	 */
	public function cache()
	{
		/*
		 * This is an empty implementation so that cacheing capability is not
		 * required, but may be implemented.
		 */
	}

	/**
	 * Purges the cache; this function will not purge the cache, but is here so that
	 * you may extend it to provide cacheing capability.
	 *
	 * @return void
	 */
	public function purge()
	{
		/*
		 * This is an empty implementation so that cacheing capability is not
		 * required, but may be implemented.
		 */
	}
}
