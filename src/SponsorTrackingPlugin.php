<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\SponsorTracking;

use IM\Fabric\Package\FormWrapper\Service\ACFComponentRegistration;
use IM\Fabric\Package\FormWrapper\Service\ComponentRegistrationInterface;
use IM\Fabric\Package\OptionsWrapper\OptionsWrapper;
use IM\Fabric\Package\Plugin\WordPressPlugin;
use IM\Fabric\Plugin\SponsorTracking\Action\AdminFields\AddSponsorBox;
use IM\Fabric\Plugin\SponsorTracking\Handler\ScheduleHandler;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SponsorTrackingPlugin extends WordPressPlugin
{
    public const PLUGIN_ID = 'im-sponsor-tracking';

    public function run(): void
    {
        $this->wordPress->addAction('init', $this->get(Action\AdminFields\AddSponsorBox::class));
        $this->wordPress->addAction('plugins_loaded', $this->get(Action\LoadPluginTextDomain::class));
        $this->wordPress->addAction('save_post', $this->get(Action\ProcessScheduleOnSave::class));

        $this->wordPress->addFilter(
            "acf/validate_value/key=field_" . AddSponsorBox::SPONSOR_TRACKING . "-item-repeater-pixel-code",
            $this->get(Filter\ValidateTrackingCode::class)
        );

        # Allow for external access to the filter (such as in im-headless-post)
        $this->wordPress->addFilter(
            self::PLUGIN_ID . '-validate-tracking-code',
            $this->get(Filter\ValidateTrackingCode::class)
        );

        $this->wordPress->addFilter(
            "acf/update_value/key=field_" . AddSponsorBox::SPONSOR_TRACKING . "-item-repeater-pixel-code",
            $this->get(Filter\PreparePixelValue::class)
        );

        # Add isSponsored flag to Timber context when on the 'single' template
        $this->wordPress->addFilter(
            'render_content_data_filter',
            $this->get(Filter\AddIsSponsoredFlagToTimberContext::class)
        );

        $this->wordPress->addFilter(
            ScheduleHandler::SPONSOR_TRACKING_REPUBLISH,
            $this->get(Filter\RepublishPost::class)
        );
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    protected function boot()
    {
        $this->add(OptionsWrapper::class, OptionsWrapper::getInstance());
        $this->add(ComponentRegistrationInterface::class, $this->get(ACFComponentRegistration::class));
    }
}
