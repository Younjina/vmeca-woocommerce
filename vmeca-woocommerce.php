<?php
/**
 * Plugin Name: vmeca-woocommerce
 * Plugin URI: http://ivynet.co.kr
 * Description: VMECA 우커머스 커스터마이징
 * Version: 0.0.1
 * Author: IVYNET
 * Author URI: http://ivynet.co.kr
 *
 */

require_once __DIR__ . '/defined.php';

require_once __DIR__ . '/vendor/autoload.php';

//require_once __DIR__.'/src/Functions/ItemAjax.php';

use function Ivy\Vmeca\Functions\checkEnvironment;
use function Ivy\Vmeca\Functions\getAutoDiscoverLauncher;

checkEnvironment() and getAutoDiscoverLauncher(__FILE__);
