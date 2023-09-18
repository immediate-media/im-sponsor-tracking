<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\PlSponsorTracking\Test\Filter;

use IM\Fabric\Plugin\PlSponsorTracking\Filter\ValidateTrackingCode;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use WP_Mock;

class ValidateTrackingCodeTest extends TestCase
{
    use MockeryPHPUnitIntegration;


    private ValidateTrackingCode $filter;
    public function setUp(): void
    {
        $this->filter = new ValidateTrackingCode();
    }

    public function testFilterReturnInfoAboutInvalidDomain()
    {
        WP_Mock::passthruFunction('__');
        $trackingCode = '<img src="https://invalid-domain.com?tracking" height="0" width="0" alt="" />';
        $expected = 'Not allowed domains: invalid-domain.com';
        $this->assertSame($expected, $this->filter->filter(true, $trackingCode, [], 'input_name'));
    }

    public function testFilterReturnInfoAboutMissedImg()
    {
        WP_Mock::passthruFunction('__');
        $trackingCode = 'no img tag here';
        $expected = 'Img tag not detected';
        $this->assertSame($expected, $this->filter->filter(true, $trackingCode, [], 'input_name'));
    }

    public function testFilterReturnInfoAboutInvalidUrl()
    {
        WP_Mock::passthruFunction('__');
        $trackingCode = '<img src="invalid-url" />';
        $expected = 'Invalid URL';
        $this->assertSame($expected, $this->filter->filter(true, $trackingCode, [], 'input_name'));
    }
}
