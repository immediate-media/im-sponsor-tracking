<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\PlSponsorTracking;

use IM\Fabric\Package\FormWrapper\Service\ACFComponentRegistration;
use IM\Fabric\Package\FormWrapper\Service\ComponentRegistrationInterface;
use IM\Fabric\Package\OptionsWrapper\OptionsWrapper;
use IM\Fabric\Package\Plugin\WordPressPlugin;
use IM\Fabric\Plugin\PlSponsorTracking\Action\AdminFields\AddSponsorBox;

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

        $this->wordPress->addFilter(
            "acf/validate_value/key=field_" . AddSponsorBox::SPONSOR_TRACKING . "-pixel-code",
            $this->get(Filter\ValidateTrackingCode::class)
        );
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    protected function boot()
    {
        $this->add(OptionsWrapper::class, OptionsWrapper::getInstance());
        $this->add(ComponentRegistrationInterface::class, $this->get(ACFComponentRegistration::class));
    }
}
