<?php

namespace IM\Fabric\Plugin\PlSponsorTracking\Action;

use IM\Fabric\Package\WordPress\Action\Action;

class LoadPluginTextDomain extends Action
{
    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function action(...$args)
    {
        load_textdomain(
            IM_PL_SPONSOR_TRACKING_PLUGIN_ID,
            IM_PL_SPONSOR_TRACKING_PLUGIN_DIR . 'src/language/' . IM_PL_SPONSOR_TRACKING_PLUGIN_ID . '-' .
                get_user_locale() . '.mo'
        );
    }
}
