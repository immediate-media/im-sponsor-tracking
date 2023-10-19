<?php

namespace IM\Fabric\Plugin\SponsorTracking\Handler;

use IM\Fabric\Package\WordPress\WordPressAwareInterface;
use IM\Fabric\Package\WordPress\WordPressAwareTrait;
use IM\Fabric\Plugin\ActionScheduler\Entity\Row;
use IM\Fabric\Plugin\SponsorTracking\DataProvider\PixelDataProvider;

class ScheduleHandler implements WordPressAwareInterface
{
    use WordPressAwareTrait;

    public const SPONSOR_TRACKING_SCHEDULE_IDS = 'sponsor_tracking_schedule_ids';
    public const SPONSOR_TRACKING_REPUBLISH = 'sponsor_tracking_republish';
    private const SPONSOR_TRACKING_KEY = 'IM_SPONSOR_TRACKING_PLUGIN_ID';

    private PixelDataProvider $dataProvider;

    public function __construct(PixelDataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    public function updateSchedule(int $postId): void
    {
        $this->removeAssignedSchedules($postId);
        $rows = $this->buildScheduleRow($postId);
        $rows = $this->wordPress->applyFilters('as_insert_rows', $rows);
        $this->updateAssignedSchedulesList($postId, $rows);
    }

    private function removeAssignedSchedules(int $postId): void
    {
        $assignedSchedules = get_post_meta($postId, self::SPONSOR_TRACKING_SCHEDULE_IDS, true);
        if ($assignedSchedules) {
            $this->wordPress->applyFilters('as_delete_rows_by_id', $assignedSchedules);
        }
    }

    private function buildScheduleRow(int $postId): array
    {
        $row = new Row(
            self::SPONSOR_TRACKING_KEY,
            $this->dataProvider->getExpireDate($postId),
            self::SPONSOR_TRACKING_REPUBLISH
        );
        $row->setPostId($postId);

        return [$row];
    }

    private function updateAssignedSchedulesList(int $postId, array $rows): void
    {
        $rowsIds = array_map(
            function ($row) {
                return $row->getId();
            },
            $rows
        );

        update_post_meta($postId, self::SPONSOR_TRACKING_SCHEDULE_IDS, $rowsIds);
    }
}
