<?php
//
//  VictoryCMS - Content managment system and framework.
//
//  Copyright (C) 2010  Lewis Gunsch <lgunsch@victorycms.org>
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
 * VictoryCMS - RegistryKeys
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
 * This class represents a set of key constants used in the Registry by 
 * VictoryCMS. The keys should all be unique. Behavior when two
 * keys have the same value is undefined.
 * 
 * @package Core
 */
class RegistryKeys
{
	/** Enable debug mode */
 	const debug_enabled = "debug_enabled";
 	
 	/** Administrator's email address. */
 	const admin_email = "admin_email";
 	
 	/** Path to the settings file */
 	const settings_path = "settings_path";
 	
 	/** Path to the VictoryCMS lib directory */
 	const lib_path = "lib_path";
 	
 	/** Registry array of Autoload directory paths */
 	const autoload = 'autoload';
 	
 	/** Array of external libraries to load - app specific */
 	const app_external = 'app_external';
 	
 	/** Array of external libraries to load- global */
 	const lib_external = 'lib_external';
}
?>
