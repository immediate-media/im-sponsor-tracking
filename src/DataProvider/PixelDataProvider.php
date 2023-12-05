<?php

namespace IM\Fabric\Plugin\SponsorTracking\DataProvider;

use IM\Fabric\Plugin\SponsorTracking\Action\AdminFields\AddSponsorBox;
use DateTime;

class PixelDataProvider
{
    public function isExpireDateAdded(int $postId): bool
    {
        $isTrackingActiveKey = AddSponsorBox::SPONSOR_TRACKING . '-metabox_'
            . AddSponsorBox::SPONSOR_TRACKING . '-is-tracking-active';

        $isTrackingActive = get_field($isTrackingActiveKey, $postId);
        $expirationDate = $this->getExpireDate($postId);
        if (!$expirationDate) {
            return false;
        }

        return $isTrackingActive && $expirationDate > new DateTime();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getExpireDate(int $postId): ?DateTime
    {
        $expirationDateKey = AddSponsorBox::SPONSOR_TRACKING . '-metabox_'
            . AddSponsorBox::SPONSOR_TRACKING . '-expiration-date';

        $expirationDateString = get_field($expirationDateKey, $postId);

        if (empty($expirationDateString)) {
            return null;
        }

        $expireDate = DateTime::createFromFormat('Y-m-d', $expirationDateString);

        // alternate format - stored in database
        if ($expireDate === false) {
            $expireDate = DateTime::createFromFormat('Ymd', $expirationDateString);
        }

        // if still invalid format
        if ($expireDate === false) {
            return null;
        }

        return $expireDate;
    }
}
