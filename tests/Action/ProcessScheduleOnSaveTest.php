<?php

namespace IM\Fabric\Plugin\SponsorTracking\Test\Action;

use IM\Fabric\Plugin\SponsorTracking\Action\ProcessScheduleOnSave;
use IM\Fabric\Plugin\SponsorTracking\DataProvider\PixelDataProvider;
use IM\Fabric\Plugin\SponsorTracking\Handler\ScheduleHandler;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use stdClass;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class ProcessScheduleOnSaveTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testCallScheduleHandler()
    {
        $scheduleHandler = Mockery::mock(ScheduleHandler::class);
        $scheduleHandler->expects('updateSchedule')->once();

        $dataProvider = Mockery::mock(PixelDataProvider::class);
        $dataProvider->expects('isExpireDateAdded')->once()->andReturn(true);

        $post = new stdClass();
        $post->post_status = 'publish';

        WP_Mock::userFunction('get_post')
            ->once()
            ->with(1)
            ->andReturns($post);

        $action = new ProcessScheduleOnSave($dataProvider, $scheduleHandler);
        $action->action(1);
    }

    public function testNotCallScheduleHandlerForDraft()
    {
        $scheduleHandler = Mockery::mock(ScheduleHandler::class);
        $scheduleHandler->expects('updateSchedule')->never();

        $dataProvider = Mockery::mock(PixelDataProvider::class);
        $dataProvider->expects('isExpireDateAdded')->never();

        $post = new stdClass();
        $post->post_status = 'draft';

        WP_Mock::userFunction('get_post')
            ->once()
            ->with(1)
            ->andReturns($post);

        $action = new ProcessScheduleOnSave($dataProvider, $scheduleHandler);
        $action->action(1);
    }

    public function testNotCallScheduleHandlerWithoutSelectedSuppression()
    {
        $scheduleHandler = Mockery::mock(ScheduleHandler::class);
        $scheduleHandler->expects('updateSchedule')->never();

        $dataProvider = Mockery::mock(PixelDataProvider::class);
        $dataProvider->expects('isExpireDateAdded')->once()->andReturn(false);

        $post = new stdClass();
        $post->post_status = 'publish';

        WP_Mock::userFunction('get_post')
            ->once()
            ->with(1)
            ->andReturns($post);

        $action = new ProcessScheduleOnSave($dataProvider, $scheduleHandler);
        $action->action(1);
    }
}
