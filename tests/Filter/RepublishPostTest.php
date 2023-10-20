<?php

namespace IM\Fabric\Plugin\SponsorTracking\Test\Filter;

use DateTime;
use IM\Fabric\Package\WordPress\WordPress;
use IM\Fabric\Plugin\SponsorTracking\Filter\RepublishPost;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use WP_Mock;
use WP_Mock\Tools\TestCase;

class RepublishPostTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testPostUpdated()
    {
        $wordPress = $this->getMockBuilder(WordPress::class)
            ->setMethods(['doAction'])
            ->getMock();

        $wordPress
            ->expects($this->never())
            ->method('doAction');

        WP_Mock::userFunction('is_wp_error')
            ->once()
            ->with(1)
            ->andReturns(false);

        WP_Mock::userFunction('update_field')
            ->once()
            ->andReturns(true);

        WP_Mock::userFunction('wp_update_post')
            ->once()
            ->with(
                [
                    'ID' => 1
                ],
                true
            )
            ->andReturns(1);

        $republishPost = new RepublishPost();
        $republishPost->setWordPress($wordPress);
        $success = $republishPost->filter(1, new DateTime('2020-10-10 10:00:00'));
        $this->assertTrue($success);
    }

    public function testErrorLoggedIfPostUpdateFails()
    {
        $wordPress = $this->getMockBuilder(WordPress::class)
            ->setMethods(['doAction'])
            ->getMock();

        $file = str_replace(
            '/tests/Filter/RepublishPostTest.php',
            '/src/Filter/RepublishPost.php',
            __FILE__
        );

        $result = ['error'];

        $wordPress
            ->expects($this->once())
            ->method('doAction')
            ->with(
                'wonolog.log.info',
                [
                    'channel' => 'IM_DEBUG',
                    'message' => 'im-sponsor-tracking - republish failed',
                    'context' => [
                        'file' => $file,
                        'error' => json_encode($result),
                        'postId' => 1
                    ],
                ]
            );


        WP_Mock::userFunction('is_wp_error')
            ->once()
            ->with($result)
            ->andReturns(true);

        WP_Mock::userFunction('update_field')
            ->once()
            ->andReturns(true);

        WP_Mock::userFunction('wp_update_post')
            ->once()
            ->with(
                [
                    'ID' => 1
                ],
                true
            )
            ->andReturns($result);

        $republishPost = new RepublishPost();
        $republishPost->setWordPress($wordPress);
        $success = $republishPost->filter(1, new DateTime('2020-10-10 10:00:00'));
        $this->assertFalse($success);
    }
}
