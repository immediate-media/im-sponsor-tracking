<?php

namespace IM\Fabric\Plugin\PlSponsorTracking\Factory;

use IM\Fabric\Package\FormWrapper\Form\Component;
use IM\Fabric\Package\FormWrapper\Form\Config\ComponentConfig;
use IM\Fabric\Package\FormWrapper\Form\Input;

class AcfSponsorBoxFactory
{
    public function create(string $formKey): Component
    {
        return new Component(
            new ComponentConfig($formKey, __('Sponsor', IM_PL_SPONSOR_TRACKING_PLUGIN_ID), ''),
            [
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'position' => 'side',
                'style' => 'default'
            ],
            ...$this->buildInputs($formKey)
        );
    }

    /**
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    private function buildInputs(string $formKey): array
    {
        $group = new Input('Sponsor', $formKey . '-metabox', 'group', [
            'layout' => 'block',
            'wrapper' => [
                'class' => 'im-no-label im-compact'
            ]
        ]);

        $group->addInputs(
            new Input(
                __('Advertising Cooperation?', IM_PL_SPONSOR_TRACKING_PLUGIN_ID),
                $formKey . '-is-sponsored',
                'true_false',
                [
                    'ui' => 1
                ]
            ),
            new Input(
                __('Campaign Active', IM_PL_SPONSOR_TRACKING_PLUGIN_ID),
                $formKey . '-is-tracking-active',
                'true_false',
                [
                    'ui' => 1,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_' . $formKey . '-is-sponsored',
                                'operator' => '==',
                                'value' => 1,
                            ],
                        ],
                    ]
                ]
            ),
            new Input(
                __('Sponsor Expiration', IM_PL_SPONSOR_TRACKING_PLUGIN_ID),
                $formKey . '-expiration-date',
                'date_picker'
            ),
            new Input(
                __('Tracking Pixel Code', IM_PL_SPONSOR_TRACKING_PLUGIN_ID),
                $formKey . '-pixel-code',
                'textarea',
                [
                    'wrapper' => [
                        'class' => 'im-compact'
                    ],
                    'rows' => 3
                ]
            ),
        );


        return [$group];
    }
}
