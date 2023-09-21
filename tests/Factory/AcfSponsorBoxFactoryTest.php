<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\SponsorTracking\Test\Factory;

use IM\Fabric\Plugin\SponsorTracking\Factory\AcfSponsorBoxFactory;
use PHPUnit\Framework\TestCase;
use IM\Fabric\Package\FormWrapper\Form\Component;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AcfSponsorBoxFactoryTest extends TestCase
{
    private AcfSponsorBoxFactory $acfSponsorBoxFactory;

    public function setUp(): void
    {
        $this->acfSponsorBoxFactory = $this->createMock(AcfSponsorBoxFactory::class);
    }

    public function testFactoryReturnsComponent(): void
    {
        $this->acfSponsorBoxFactory->expects($this->once())
            ->method('create')
            ->with('form_key');
        $component = $this->acfSponsorBoxFactory->create('form_key');
        $this->assertInstanceOf(Component::class, $component);
    }
}
