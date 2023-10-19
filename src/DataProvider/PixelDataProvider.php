<?php

namespace IM\Fabric\Plugin\SponsorTracking\DataProvider;

use IM\Fabric\Plugin\SponsorTracking\Action\AdminFields\AddSponsorBox;
use DateTime;

class PixelDataProvider
{
    public function isExpireDateAdded(int $postId): bool
    {
        $isSponsoredKey = AddSponsorBox::SPONSOR_TRACKING . '-metabox_'
            . AddSponsorBox::SPONSOR_TRACKING . '-is-sponsored';

        $isSponsored = get_field($isSponsoredKey, $postId);
        $expirationDate = $this->getExpireDate($postId);
        if (!$expirationDate) {
            return false;
        }
        $today = new DateTime();

        return $isSponsored && $expirationDate > $today;
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

        return DateTime::createFromFormat('Y-m-d', $expirationDateString);
    }
}
