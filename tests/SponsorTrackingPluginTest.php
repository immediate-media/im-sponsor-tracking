<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\SponsorTracking\Test;

use IM\Fabric\Package\Plugin\WordPressPlugin;
use IM\Fabric\Package\WordPress\WordPress;
use IM\Fabric\Package\WpPost\PostTypes;
use IM\Fabric\Plugin\SponsorTracking\Action\AdminFields\AddSponsorBox;
use IM\Fabric\Plugin\SponsorTracking\Action\LoadPluginTextDomain;
use IM\Fabric\Plugin\SponsorTracking\Action\ProcessScheduleOnSave;
use IM\Fabric\Plugin\SponsorTracking\Filter\AddIsSponsoredFlagToTimberContext;
use IM\Fabric\Plugin\SponsorTracking\Filter\PreparePixelValue;
use IM\Fabric\Plugin\SponsorTracking\Filter\RepublishPost;
use IM\Fabric\Plugin\SponsorTracking\Filter\ValidateTrackingCode;
use IM\Fabric\Plugin\SponsorTracking\Handler\ScheduleHandler;
use IM\Fabric\Plugin\SponsorTracking\SponsorTrackingPlugin;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SponsorTrackingPluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private const EXPECTED_ACTIONS = [
        ['plugins_loaded', LoadPluginTextDomain::class],
        ['init', AddSponsorBox::class],
        ['save_post', ProcessScheduleOnSave::class]
    ];
    private const EXPECTED_FILTERS = [
        ['acf/validate_value/key=field_sponsor_tracking-item-repeater-pixel-code', ValidateTrackingCode::class],
        ['acf/update_value/key=field_sponsor_tracking-item-repeater-pixel-code', PreparePixelValue::class],
        ['im-sponsor-tracking-validate-tracking-code', ValidateTrackingCode::class],
        ['render_content_data_filter', AddIsSponsoredFlagToTimberContext::class],
        [ScheduleHandler::SPONSOR_TRACKING_REPUBLISH, RepublishPost::class]
    ];

    private SponsorTrackingPlugin $plugin;

    private WordPress $wordPress;
    private PostTypes $postTypes;

    public function setUp(): void
    {
        $this->postTypes = Mockery::mock(PostTypes::class);
        $this->postTypes->allows('getEditorialPostTypes')
            ->andReturn(['Article' => 'article'])
            ->byDefault();

        $this->wordPress = Mockery::mock(WordPress::class);
        $this->plugin = Mockery::mock(SponsorTrackingPlugin::class)->makePartial();
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
