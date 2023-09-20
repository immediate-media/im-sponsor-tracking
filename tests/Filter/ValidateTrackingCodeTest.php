<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\SponsorTracking\Test\Filter;

use IM\Fabric\Plugin\SponsorTracking\Filter\ValidateTrackingCode;
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
        $trackingCode = 'https://invalid-domain.com?tracking';
        $expected = 'Not allowed domain';
        $this->assertSame($expected, $this->filter->filter(true, $trackingCode, [], 'input_name'));
    }


    public function testFilterReturnInfoAboutInvalidUrl()
    {
        WP_Mock::passthruFunction('__');
        $trackingCode = 'invalid-url';
        $expected = 'Invalid URL';
        $this->assertSame($expected, $this->filter->filter(true, $trackingCode, [], 'input_name'));
    }
}
