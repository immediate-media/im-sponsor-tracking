<?php

declare(strict_types=1);

namespace IM\Fabric\Plugin\PlSponsorTracking\Test\Action\AdminFields;

use IM\Fabric\Package\FormWrapper\Form\Component;
use IM\Fabric\Package\FormWrapper\Form\RuleCollection;
use IM\Fabric\Package\FormWrapper\Service\ComponentRegistrationInterface;
use IM\Fabric\Package\WpPost\PostTypes;
use IM\Fabric\Plugin\PlSponsorTracking\Action\AdminFields\AddSponsorBox;
use IM\Fabric\Plugin\PlSponsorTracking\Factory\AcfSponsorBoxFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddSponsorBoxTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private AcfSponsorBoxFactory $acfSponsorBoxFactory;
    private ComponentRegistrationInterface $componentRegistration;
    private PostTypes $postTypes;

    public function setUp(): void
    {
        $this->componentRegistration = Mockery::mock(ComponentRegistrationInterface::class);
        $this->acfSponsorBoxFactory = Mockery::mock(AcfSponsorBoxFactory::class);
        $this->acfSponsorBoxFactory->allows('create')
            ->andReturn(Mockery::mock(Component::class))
            ->byDefault();
        $this->postTypes = Mockery::mock(PostTypes::class);
        $this->postTypes->allows('getEditorialPostTypes')
            ->andReturn(['Article' => 'post'])
            ->byDefault();
    }

    public function testAddSponsorBoxSetsUpCorrectComponent(): void
    {
        $this->componentRegistration->expects('register')
            ->with(
                Mockery::type(Component::class),
                Mockery::type(RuleCollection::class)
            );
        (new AddSponsorBox($this->componentRegistration, $this->acfSponsorBoxFactory, $this->postTypes))->action();
    }
}
