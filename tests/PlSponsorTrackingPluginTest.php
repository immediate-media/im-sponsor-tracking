<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\PlSponsorTracking\Test;

use IM\Fabric\Package\Plugin\WordPressPlugin;
use IM\Fabric\Package\WordPress\WordPress;
use IM\Fabric\Package\WpPost\PostTypes;
use IM\Fabric\Plugin\PlSponsorTracking\Action\AdminFields\AddSponsorBox;
use IM\Fabric\Plugin\PlSponsorTracking\Action\LoadPluginTextDomain;
use IM\Fabric\Plugin\PlSponsorTracking\Filter\ValidateTrackingCode;
use IM\Fabric\Plugin\PlSponsorTracking\PlSponsorTrackingPlugin;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PlSponsorTrackingPluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const EXPECTED_ACTIONS = [
        ['plugins_loaded', LoadPluginTextDomain::class],
        ['init', AddSponsorBox::class]
    ];
    private const EXPECTED_FILTERS = [
        ['acf/validate_value/key=field_pl_sponsor_tracking-item-repeater-pixel-code', ValidateTrackingCode::class]
    ];

    private PlSponsorTrackingPlugin $plugin;

    private WordPress $wordPress;
    private PostTypes $postTypes;

    public function setUp(): void
    {
        $this->postTypes = Mockery::mock(PostTypes::class);
        $this->postTypes->allows('getEditorialPostTypes')
            ->andReturn(['Article' => 'article'])
            ->byDefault();

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
