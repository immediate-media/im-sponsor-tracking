<?php

namespace IM\Fabric\Plugin\SponsorTracking\Filter;

use IM\Fabric\Package\WordPress\Filter\Filter;

class PreparePixelValue extends Filter
{
    public $arguments = 1;

    public function filter(...$args)
    {
        [$value] = $args;

        if (!is_string($value)) {
            $value = '';
        }

        return htmlspecialchars_decode($value);
    }
}
