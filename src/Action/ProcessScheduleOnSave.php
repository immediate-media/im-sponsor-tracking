<?php

namespace IM\Fabric\Plugin\SponsorTracking\Action;

use IM\Fabric\Package\WordPress\Action\Action;
use IM\Fabric\Plugin\SponsorTracking\DataProvider\PixelDataProvider;
use IM\Fabric\Plugin\SponsorTracking\Handler\ScheduleHandler;

class ProcessScheduleOnSave extends Action
{
    private ScheduleHandler $scheduleHandler;

    private PixelDataProvider $dataProvider;

    public function __construct(PixelDataProvider $dataProvider, ScheduleHandler $scheduleHandler)
    {
        $this->dataProvider = $dataProvider;
        $this->scheduleHandler = $scheduleHandler;
    }

    public function action(...$args)
    {
        [$postId] = $args;
        $post = get_post($postId);

        if ($post->post_status === 'publish' && $this->dataProvider->isExpireDateAdded($postId)) {
            $this->scheduleHandler->updateSchedule($postId);
        }
    }
}
