<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\PlSponsorTracking\Test;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use WP_Mock;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PlSponsorTrackingPlugin extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const EXPECTED_ACTIONS = [
        ['plugins_loaded', LoadPluginTextDomain::class],

    ];
    private const EXPECTED_FILTERS = [
    ];

    private PlSponsorTrackingPlugin $plugin;

    private WordPress $wordPress;

    public function setUp(): void
    {
        $this->wordPress = Mockery::mock(WordPress::class);
        $this->plugin = Mockery::mock(PlSponsorTrackingPlugin::class)->makePartial();
        $this->plugin->allows('get')->with(WordPress::class)->andReturn($this->wordPress);
        $this->plugin->__construct();
    }

    public function testInstanceOfWordPressPlugin(): void
    {
        $this->assertInstanceOf(WordPressPlugin::class, $this->plugin);
    }

    public function testRunAddsExpectedActionsAndFilters(): void
    {
        foreach (self::EXPECTED_ACTIONS as $action) {
            $this->wordPress->expects('addAction')->with(...$action);
        }

        foreach (self::EXPECTED_FILTERS as $filter) {
            $this->wordPress->expects('addFilter')->with(...$filter);
        }

        $this->plugin->run();
    }
}
