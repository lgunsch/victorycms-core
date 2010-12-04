<?php

/* Should normally resolve to the web document root,
 * ./../../lib/VictoryCMSTestRunner.php or you can just set it to
 * "path/to/web_root/test_lib/VictoryCMSRunner.php"
 */
$webRoot = ''.dirname(__DIR__).DIRECTORY_SEPARATOR;

// Path: "../include/VictoryCMS.php"
require_once $webRoot.'lib'.DIRECTORY_SEPARATOR.'VictoryCMS.php';

// Change the below path to config.json as needed
VictoryCMS\VictoryCMS::bootstrap('../config.json');
