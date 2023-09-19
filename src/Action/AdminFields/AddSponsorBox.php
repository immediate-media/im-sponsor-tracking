<?php

namespace IM\Fabric\Plugin\PlSponsorTracking\Action\AdminFields;

use IM\Fabric\Package\FormWrapper\Form\Rule;
use IM\Fabric\Package\FormWrapper\Form\RuleCollection;
use IM\Fabric\Package\FormWrapper\Service\ComponentRegistrationInterface;
use IM\Fabric\Package\WordPress\Action\Action;
use IM\Fabric\Package\WpPost\PostTypes;
use IM\Fabric\Plugin\PlSponsorTracking\Factory\AcfSponsorBoxFactory;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class AddSponsorBox extends Action
{
    protected $priority = 15;

    public const SPONSOR_TRACKING = 'pl_sponsor_tracking';

    public function __construct(
        private ComponentRegistrationInterface $componentRegistration,
        private AcfSponsorBoxFactory $acfSponsorBoxFactory,
        private PostTypes $postTypes
    ) {
    }

    private function buildSponsorInformationRuleCollection(): RuleCollection
    {
        $ruleCollection = new RuleCollection();
        $allowedPostType = ['post','recipe'];

        foreach ($this->postTypes->getEditorialPostTypes() as $postType) {
            if (in_array($postType, $allowedPostType)) {
                $rule = new Rule('post_type', '==', $postType);
                $ruleCollection->addRuleSet($rule);
            }
        }

        return $ruleCollection;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function action(...$args)
    {
        $this->componentRegistration->register(
            $this->acfSponsorBoxFactory->create(self::SPONSOR_TRACKING),
            $this->buildSponsorInformationRuleCollection()
        );
    }
}
