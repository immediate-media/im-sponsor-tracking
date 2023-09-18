<?php

namespace IM\Fabric\Plugin\PlSponsorTracking\Filter;

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
    public $arguments = 4;

    public function filter(...$args)
    {
        [$valid, $value] = $args;

        // Bail early if value is already invalid.
        if (!$valid) {
            return $valid;
        }

        $doc = new DOMDocument('1.0', 'utf-8');

        libxml_use_internal_errors(true);
        $charsetPrepend = '<?xml version="1.0" encoding="UTF-8"?>';
        $doc->loadHTML($charsetPrepend . $value, LIBXML_NOERROR);

        $imagesTags = $doc->getElementsByTagName('img');

        if ($imagesTags->count() === 0) {
            return __('Img tag not detected');
        }

        /** @var DOMNode $imageNode */
        foreach ($imagesTags->getIterator() as $imageNode) {
            if (!$imageNode->hasAttribute('src')) {
                return __('Pixel without src');
            }
            $src = trim($imageNode->getAttribute('src'));
            $src = str_replace('\"', '', $src);

            if (filter_var($src, FILTER_VALIDATE_URL) === false) {
                return __('Invalid URL');
            }

            if (!$this->isValidDomain($src)) {
                $urlParts = parse_url($src);
                return __('Not allowed domains') . ': ' . $urlParts['host'];
            }
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
