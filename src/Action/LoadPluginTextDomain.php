<?php

namespace IM\Fabric\Plugin\SponsorTracking\Action;

use IM\Fabric\Package\WordPress\Action\Action;

class LoadPluginTextDomain extends Action
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function action(...$args)
    {
        load_textdomain(
            IM_SPONSOR_TRACKING_PLUGIN_ID,
            IM_SPONSOR_TRACKING_PLUGIN_DIR . 'src/language/' . IM_SPONSOR_TRACKING_PLUGIN_ID . '-' .
                get_user_locale() . '.mo'
        );
    }
}
