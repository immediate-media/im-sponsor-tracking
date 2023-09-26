<?php

namespace IM\Fabric\Plugin\SponsorTracking\Filter;

use IM\Fabric\Package\WordPress\Filter;
use IM\Fabric\Plugin\SponsorTracking\Action\AdminFields\AddSponsorBox;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddIsSponsoredFlagToTimberContext extends Filter
{
    /**
     * Add the plant details data to the timber context (used for render on the front end)
     *
     * @param array ...$args
     * @return mixed
     */
    public function filter(...$args)
    {
        [$context] = $args;

        $isSponsoredKey = AddSponsorBox::SPONSOR_TRACKING . '-metabox_'
              . AddSponsorBox::SPONSOR_TRACKING . '-is-sponsored';

        $context['sponsorTracking']['isSponsored'] = (bool) get_field($isSponsoredKey);

        return $context;
    }
}
