<?php

namespace IM\Fabric\Plugin\SponsorTracking\Filter;

use IM\Fabric\Package\WordPress\Filter\Filter;
use IM\Fabric\Package\WordPress\WordPressAwareInterface;
use IM\Fabric\Package\WordPress\WordPressAwareTrait;
use IM\Fabric\Plugin\SponsorTracking\Action\AdminFields\AddSponsorBox;

class RepublishPost extends Filter implements WordPressAwareInterface
{
    use WordPressAwareTrait;

    public function filter(...$args): bool
    {
        [$postId] = $args;

        $isSponsoredKey = AddSponsorBox::SPONSOR_TRACKING . '-metabox_'
            . AddSponsorBox::SPONSOR_TRACKING . '-is-sponsored';

        try {
            update_field($isSponsoredKey, false, $postId);
        } catch (Exception $e) {
            error_log('Unable to update sponsor tracking data: ' . $e->getMessage());
        }

        $result = wp_update_post(
            [
                'ID' => $postId
            ],
            true
        );

        if (is_wp_error($result)) {
            $this->wordPress->doAction(
                'wonolog.log.info',
                [
                    'channel' => 'IM_DEBUG',
                    'message' => 'im-sponsor-tracking - republish failed',
                    'context' => [
                        'file' => __FILE__,
                        'error' => json_encode($result),
                        'postId' => $postId
                    ],
                ]
            );
            return false;
        }

        return true;
    }
}
