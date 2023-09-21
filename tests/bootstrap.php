<?php

namespace {

    WP_Mock::activateStrictMode();
    WP_Mock::bootstrap();

    if (!defined('IM_SPONSOR_TRACKING_PLUGIN_DIR')) {
        define('IM_SPONSOR_TRACKING_PLUGIN_DIR', dirname(__FILE__) . '/');
    }

    if (!defined('IM_SPONSOR_TRACKING_PLUGIN_ID')) {
        define('IM_SPONSOR_TRACKING_PLUGIN_ID', 'im-sponsor-tracking');
    }

    if (!defined('IM_SPONSOR_TRACKING_PLUGIN_URL')) {
        define('IM_SPONSOR_TRACKING_PLUGIN_URL', 'https://www.wordpress-root.com/plugins/name');
    }


}
