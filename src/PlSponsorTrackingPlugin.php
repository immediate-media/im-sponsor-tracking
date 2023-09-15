<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\PlSponsorTracking;

use IM\Fabric\Package\FormWrapper\Service\ACFComponentRegistration;
use IM\Fabric\Package\FormWrapper\Service\ComponentRegistrationInterface;
use IM\Fabric\Package\OptionsWrapper\OptionsWrapper;
use IM\Fabric\Package\Plugin\WordPressPlugin;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PlSponsorTrackingPlugin extends WordPressPlugin
{
    public const PLUGIN_ID = 'im-pl-sponsor-tracking';

    public function run(): void
    {
        $this->wordPress->addAction('init', $this->get(Action\AdminFields\AddSponsorBox::class));
        $this->wordPress->addAction('plugins_loaded', $this->get(Action\LoadPluginTextDomain::class));
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    protected function boot()
    {
        $this->add(OptionsWrapper::class, OptionsWrapper::getInstance());
        $this->add(ComponentRegistrationInterface::class, $this->get(ACFComponentRegistration::class));
    }
}
