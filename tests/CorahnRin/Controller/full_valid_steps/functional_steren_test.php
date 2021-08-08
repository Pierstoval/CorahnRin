<?php

declare(strict_types=1);

/*
 * This file is part of the Corahn-Rin package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    '01_people' => [
        'next_step' => '02_job',
        'form_values' => [
            'gen-div-choice' => 1,
        ],
        'session_value' => 1,
    ],

    '02_job' => [
        'next_step' => '03_birthplace',
        'form_values' => [
            'job_value' => 15,
        ],
        'session_value' => 15,
    ],

    '03_birthplace' => [
        'next_step' => '04_geo',
        'form_values' => [
            'region_value' => 1,
        ],
        'session_value' => 1,
    ],

    '04_geo' => [
        'next_step' => '05_social_class',
        'form_values' => [
            'gen-div-choice' => 2,
        ],
        'session_value' => 2,
    ],

    '05_social_class' => [
        'next_step' => '06_age',
        'form_values' => [
            'gen-div-choice' => 2,
            'domains' => [
                18 => 'domains.science',
                19 => 'domains.erudition',
            ],
        ],
        'session_value' => [
            'id' => 2,
            'domains' => [
                0 => 'domains.science',
                1 => 'domains.erudition',
            ],
        ],
    ],

    '06_age' => [
        'next_step' => '07_setbacks',
        'form_values' => [
            'age' => 21,
        ],
        'session_value' => 21,
    ],

    '07_setbacks' => [
        'route_uri' => '07_setbacks?manual',
        'next_step' => '08_ways',
        'form_values' => [
            'setbacks_value' => [
                7 => 9,
            ],
        ],
        'session_value' => [
            9 => ['id' => 9, 'avoided' => false],
        ],
    ],

    '08_ways' => [
        'next_step' => '09_traits',
        'form_values' => [
            'ways' => [
                'ways.combativeness' => 1,
                'ways.creativity' => 3,
                'ways.empathy' => 5,
                'ways.reason' => 5,
                'ways.conviction' => 1,
            ],
        ],
        'session_value' => [
            'ways.combativeness' => 1,
            'ways.creativity' => 3,
            'ways.empathy' => 5,
            'ways.reason' => 5,
            'ways.conviction' => 1,
        ],
    ],

    '09_traits' => [
        'next_step' => '10_orientation',
        'form_values' => ['quality' => 57, 'flaw' => 67],
        'session_value' => ['quality' => 57, 'flaw' => 67],
    ],

    '10_orientation' => [
        'next_step' => '11_advantages',
        'form_values' => [],
        'session_value' => 'character.orientation.rational',
    ],

    '11_advantages' => [
        'next_step' => '12_mental_disorder',
        'form_values' => [
            'advantages' => [20 => 2],
            'disadvantages' => [],
            'advantages_indications' => [],
        ],
        'session_value' => [
            'advantages' => [20 => 2],
            'disadvantages' => [],
            'advantages_indications' => [],
            'remainingExp' => 40,
        ],
    ],

    '12_mental_disorder' => [
        'next_step' => '13_primary_domains',
        'form_values' => [
            'gen-div-choice' => 2,
        ],
        'session_value' => 2,
    ],

    '13_primary_domains' => [
        'next_step' => '14_use_domain_bonuses',
        'form_values' => [
            'ost' => 'domains.close_combat',
            'domains' => [
                'domains.natural_environment' => 1,
                'domains.occultism' => 5,
                'domains.perception' => 1,
                'domains.relation' => 2,
                'domains.travel' => 2,
                'domains.erudition' => 3,
            ],
        ],
        'session_value' => [
            'domains' => [
                'domains.craft' => 0,
                'domains.close_combat' => 0,
                'domains.stealth' => 0,
                'domains.magience' => 0,
                'domains.natural_environment' => 1,
                'domains.demorthen_mysteries' => 0,
                'domains.occultism' => 5,
                'domains.perception' => 1,
                'domains.prayer' => 0,
                'domains.feats' => 0,
                'domains.relation' => 2,
                'domains.performance' => 0,
                'domains.science' => 0,
                'domains.shooting_and_throwing' => 0,
                'domains.travel' => 2,
                'domains.erudition' => 3,
            ],
            'ost' => 'domains.close_combat',
        ],
    ],

    '14_use_domain_bonuses' => [
        'next_step' => '15_domains_spend_exp',
        'form_values' => [
            'domains_bonuses' => [
                'domains.craft' => 0,
                'domains.close_combat' => 0,
                'domains.stealth' => 0,
                'domains.magience' => 0,
                'domains.natural_environment' => 0,
                'domains.demorthen_mysteries' => 0,
                'domains.occultism' => 0,
                'domains.perception' => 0,
                'domains.prayer' => 0,
                'domains.feats' => 0,
                'domains.relation' => 0,
                'domains.performance' => 0,
                'domains.science' => 0,
                'domains.shooting_and_throwing' => 0,
                'domains.travel' => 0,
                'domains.erudition' => 0,
            ],
        ],
        'session_value' => [
            'domains' => [
                'domains.craft' => 0,
                'domains.close_combat' => 0,
                'domains.stealth' => 0,
                'domains.magience' => 0,
                'domains.natural_environment' => 0,
                'domains.demorthen_mysteries' => 0,
                'domains.occultism' => 0,
                'domains.perception' => 0,
                'domains.prayer' => 0,
                'domains.feats' => 0,
                'domains.relation' => 0,
                'domains.performance' => 0,
                'domains.science' => 0,
                'domains.shooting_and_throwing' => 0,
                'domains.travel' => 0,
                'domains.erudition' => 0,
            ],
            'remaining' => 1,
        ],
    ],

    '15_domains_spend_exp' => [
        'next_step' => '16_disciplines',
        'form_values' => [
            'domains_spend_exp' => [
                'domains.craft' => 0,
                'domains.close_combat' => 0,
                'domains.stealth' => 0,
                'domains.magience' => 0,
                'domains.natural_environment' => 0,
                'domains.demorthen_mysteries' => 0,
                'domains.occultism' => 0,
                'domains.perception' => 0,
                'domains.prayer' => 0,
                'domains.feats' => 0,
                'domains.relation' => 0,
                'domains.performance' => 0,
                'domains.science' => 0,
                'domains.shooting_and_throwing' => 0,
                'domains.travel' => 0,
                'domains.erudition' => 0,
            ],
        ],
        'session_value' => [
            'domains' => [
                'domains.craft' => null,
                'domains.close_combat' => null,
                'domains.stealth' => null,
                'domains.magience' => null,
                'domains.natural_environment' => null,
                'domains.demorthen_mysteries' => null,
                'domains.occultism' => null,
                'domains.perception' => null,
                'domains.prayer' => null,
                'domains.feats' => null,
                'domains.relation' => null,
                'domains.performance' => null,
                'domains.science' => null,
                'domains.shooting_and_throwing' => null,
                'domains.travel' => null,
                'domains.erudition' => null,
            ],
            'remainingExp' => 40,
        ],
    ],

    '16_disciplines' => [
        'next_step' => '17_combat_arts',
        'form_values' => [
            'disciplines_spend_exp' => [
                'domains.occultism' => [
                    55 => 'on',
                    82 => 'on',
                ],
            ],
        ],
        'session_value' => [
            'disciplines' => [
                'domains.occultism' => [
                    55 => true,
                    82 => true,
                ],
            ],
            'remainingExp' => 15,
            'remainingBonusPoints' => 0,
        ],
    ],

    '17_combat_arts' => [
        'next_step' => '18_equipment',
        'form_values' => [
        ],
        'session_value' => [
            'combatArts' => [],
            'remainingExp' => 15,
        ],
    ],

    '18_equipment' => [
        'next_step' => '19_description',
        'form_values' => [
            'armors' => [],
            'weapons' => [19 => true],
            'equipment' => [],
        ],
        'session_value' => [
            'armors' => [],
            'weapons' => [19 => true],
            'equipment' => [],
        ],
    ],

    '19_description' => [
        'next_step' => '20_finish',
        'form_values' => [
            'details' => [
                'name' => 'Steren Slaine',
                'player_name' => 'Test user',
                'sex' => 'character.sex.female',
                'description' => 'Small but beautiful',
                'story' => 'A long story...',
                'facts' => 'Loves ghost stories...',
            ],
        ],
        'session_value' => [
            'name' => 'Steren Slaine',
            'player_name' => 'Test user',
            'sex' => 'character.sex.female',
            'description' => 'Small but beautiful',
            'story' => 'A long story...',
            'facts' => 'Loves ghost stories...',
        ],
    ],
];
