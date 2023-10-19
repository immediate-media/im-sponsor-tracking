<?php

namespace IM\Fabric\Plugin\SponsorTracking\Test\Handler;

use DateTime;
use IM\Fabric\Package\WordPress\WordPress;
use IM\Fabric\Plugin\ActionScheduler\Entity\Row;
use IM\Fabric\Plugin\SponsorTracking\DataProvider\PixelDataProvider;
use IM\Fabric\Plugin\SponsorTracking\Handler\ScheduleHandler;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class ScheduleHandlerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testUpdateSchedule()
    {
        $postId = 1;
        $dataProvider = Mockery::mock(PixelDataProvider::class);

        $dataProvider->expects('getExpireDate')->once()->andReturn(new DateTime());

        $row = new Row(
            'plugin',
            new DateTime('2118-10-10'),
            'action',
            []
        );
        $row->setId(1);

        $wordPress = Mockery::mock(WordPress::class);
        $wordPress->expects('applyFilters')
            ->once()
            ->andReturn([$row]);

        $wordPress->expects('applyFilters')
            ->once()
            ->andReturn([$row]);

        WP_Mock::userFunction('get_post_meta')
            ->once()
            ->andReturns([1, 2]);

        WP_Mock::userFunction('update_post_meta')
            ->once();

        $scheduleHandler = new ScheduleHandler($dataProvider);
        $scheduleHandler->setWordpress($wordPress);
        $scheduleHandler->updateSchedule($postId);
    }
}
