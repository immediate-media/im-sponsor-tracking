<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\PlSponsorTracking;

use IM\Fabric\Package\Plugin\WordPressPlugin;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PlSponsorTrackingPlugin extends WordPressPlugin
{
    public const PLUGIN_ID = 'im-pl-sponsor-tracking';

    public function run(): void
    {
        $this->wordPress->addAction('plugins_loaded', $this->get(Action\LoadPluginTextDomain::class));
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function boot()
    {
    }
}
