<?php

/**
 * Plugin Name: IM PL Sponsor tracking
 * Description: IM PL Sponsor tracking Plugin.
 * Version: 0.1
 * Author: Immediate Media
 * Author URI: http://www.immediate.co.uk
 * License: Proprietary
 */

if (! defined('ABSPATH')) {
    return;
}

define('IM_PL_SPONSOR_TRACKING_PLUGIN_ID', 'im-pl-sponsor-tracking');
define('IM_PL_SPONSOR_TRACKING_PLUGIN_URL', plugins_url('', __FILE__));
define('IM_PL_SPONSOR_TRACKING_PLUGIN_DIR', \plugin_dir_path(__FILE__));

$plugin = new IM\Fabric\Plugin\SponsorTracking\PlSponsorTrackingPlugin();
$plugin->run();
