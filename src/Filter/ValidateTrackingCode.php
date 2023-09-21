<?php

namespace IM\Fabric\Plugin\SponsorTracking\Filter;

use IM\Fabric\Package\WordPress\Filter\Filter;
use DOMDocument;
use DOMNode;

class ValidateTrackingCode extends Filter
{
    private const VALID_DOMAINS = [
        'gde-default.hit.gemius.pl',
        'ad.doubleclick.net',
        'bs.serving-sys.com',
        'track.adform.net',
        'secure-gl.imrworldwide.com',
        'edipresse.hit.gemius.pl'
    ];
    public $arguments = 2;

    public function filter(...$args)
    {
        [$valid, $value] = $args;

        // Bail early if value is already invalid.
        if (!$valid) {
            return $valid;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            return __('Invalid URL');
        }

        if (!$this->isValidDomain($value)) {
            return __('Domain not allowed');
        }
        return $valid;
    }

    private function isValidDomain($src): bool
    {
        $urlParts = parse_url($src);

        return in_array(
            ($urlParts['host'] ?? ''),
            self::VALID_DOMAINS
        );
    }
}
