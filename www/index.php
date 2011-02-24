<?php

/* Should normally resolve to the web document root,
 * ./../../ or you can just set it to "path/to/web_root/"
 */
$webRoot = ''.dirname(__DIR__).DIRECTORY_SEPARATOR;

// Path to VictoryCMS.php: "../lib/VictoryCMS.php"
require_once $webRoot.'lib'.DIRECTORY_SEPARATOR.'VictoryCMS.php';

// Change the below path to config.json as required
Vcms\VictoryCMS::bootstrap($webRoot.'config.json');
