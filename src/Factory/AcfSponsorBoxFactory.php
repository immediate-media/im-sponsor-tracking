<?php

namespace IM\Fabric\Plugin\SponsorTracking\Factory;

use IM\Fabric\Package\FormWrapper\Form\Component;
use IM\Fabric\Package\FormWrapper\Form\Config\ComponentConfig;
use IM\Fabric\Package\FormWrapper\Form\Input;

class AcfSponsorBoxFactory
{
    public function create(string $formKey): Component
    {
        return new Component(
            new ComponentConfig($formKey, __('Sponsor', IM_SPONSOR_TRACKING_PLUGIN_ID), ''),
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

        $repeaterKey = $formKey . '-item-repeater';

        $trackingPixelRepeater = new Input(
            __('Tracking Pixel', IM_SPONSOR_TRACKING_PLUGIN_ID),
            $repeaterKey,
            'repeater',
            [
                'layout' => 'block',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_' . $formKey . '-is-sponsored',
                            'operator' => '==',
                            'value' => 1,
                        ],
                    ],
                ],
                'wrapper' => [
                    'width' => "",
                    "class" => "im-no-inline-add im-hide-column-1"
                ],
                'button_label' => __('Add Pixel', IM_SPONSOR_TRACKING_PLUGIN_ID),
            ]
        );

        $trackingPixelRepeater->addInputs(
            new Input(
                __('Tracking Pixel Code', IM_SPONSOR_TRACKING_PLUGIN_ID),
                $repeaterKey . '-pixel-code',
                'url',
                [
                    'wrapper' => [
                        'class' => 'im-compact'
                    ],
                    'rows' => 3
                ]
            ),
        );

        $group->addInputs(
            new Input(
                __('Advertising Cooperation?', IM_SPONSOR_TRACKING_PLUGIN_ID),
                $formKey . '-is-sponsored',
                'true_false',
                [
                    'ui' => true
                ]
            ),
            new Input(
                __('Campaign Active', IM_SPONSOR_TRACKING_PLUGIN_ID),
                $formKey . '-is-tracking-active',
                'true_false',
                [
                    'ui' => true,
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
                __('Sponsor Expiration', IM_SPONSOR_TRACKING_PLUGIN_ID),
                $formKey . '-expiration-date',
                'date_picker',
                [
                    'return_format' => "Y-m-d",
                    'display_format' => "Y-m-d",
                    'first_day' => 1,
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
            $trackingPixelRepeater,
        );


        return [$group];
    }
}
