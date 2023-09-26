<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\SponsorTracking\Test\Filter;

use IM\Fabric\Plugin\SponsorTracking\Filter\AddIsSponsoredFlagToTimberContext;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use WP_Mock\Tools\TestCase;
use WP_Mock;

class AddIsSponsoredFlagToTimberContextTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @dataProvider fieldValueDataProvider */
    public function testFilterReturnsAppropriateConfig(?bool $fieldValue, bool $expected): void
    {
        $expected = [
            'existingData' => true,
            'sponsorTracking' => [
                'isSponsored' => $expected
            ]
        ];

        WP_Mock::userFunction('get_field')
            ->with('sponsor_tracking-metabox_sponsor_tracking-is-sponsored')
            ->andReturn($fieldValue);

        $actual = (new AddIsSponsoredFlagToTimberContext())->filter(['existingData' => true]);

        $this->assertEquals($expected, $actual);
    }

    public function fieldValueDataProvider(): array
    {
        return [
            'field value = true' => [true, true],
            'field value = false' => [false, false],
            'field value = missing' => [null, false]
        ];
    }
}
