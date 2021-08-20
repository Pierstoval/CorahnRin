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

use DataFixtures\CorahnRin\AdvantagesFixtures;

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
            'job_value' => 1,
        ],
        'session_value' => 1,
    ],

    '03_birthplace' => [
        'next_step' => '04_geo',
        'form_values' => [
            'region_value' => 25,
        ],
        'session_value' => 25,
    ],

    '04_geo' => [
        'next_step' => '05_social_class',
        'form_values' => [
            'gen-div-choice' => 1,
        ],
        'session_value' => 1,
    ],

    '05_social_class' => [
        'next_step' => '06_age',
        'form_values' => [
            'gen-div-choice' => 1,
            'domains' => [
                0 => 'domains.natural_environment',
                1 => 'domains.perception',
            ],
        ],
        'session_value' => [
            'id' => 1,
            'domains' => [
                0 => 'domains.natural_environment',
                1 => 'domains.perception',
            ],
        ],
    ],

    '06_age' => [
        'next_step' => '07_setbacks',
        'form_values' => [
            'age' => 31,
        ],
        'session_value' => 31,
    ],

    '07_setbacks' => [
        'route_uri' => '07_setbacks?manual',
        'next_step' => '08_ways',
        'form_values' => [
            'setbacks_value' => [
                0 => 2,
                1 => 3,
            ],
        ],
        'session_value' => [
            2 => [
                'id' => 2,
                'avoided' => false,
            ],
            3 => [
                'id' => 3,
                'avoided' => false,
            ],
        ],
    ],

    '08_ways' => [
        'next_step' => '09_traits',
        'form_values' => [
            'ways' => [
                'ways.combativeness' => 5,
                'ways.creativity' => 4,
                'ways.empathy' => 3,
                'ways.reason' => 2,
                'ways.conviction' => 1,
            ],
        ],
        'session_value' => [
            'ways.combativeness' => 5,
            'ways.creativity' => 4,
            'ways.empathy' => 3,
            'ways.reason' => 2,
            'ways.conviction' => 1,
        ],
    ],

    '09_traits' => [
        'next_step' => '10_orientation',
        'form_values' => [
            'quality' => 1,
            'flaw' => 10,
        ],
        'session_value' => [
            'quality' => 1,
            'flaw' => 10,
        ],
    ],

    '10_orientation' => [
        'next_step' => '11_advantages',
        'form_values' => [
        ],
        'session_value' => 'character.orientation.instinctive',
    ],

    '11_advantages' => [
        'next_step' => '12_mental_disorder',
        'form_values' => [
            'advantages' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 1,
                AdvantagesFixtures::ID_FINANCIAL_EASE_5 => 1,
            ],
            'disadvantages' => [
                AdvantagesFixtures::ID_CRIPPLED => 1,
                AdvantagesFixtures::ID_PHOBIA => 1,
                AdvantagesFixtures::ID_POOR => 1,
            ],
            'advantages_indications' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 'Influent ally',
                AdvantagesFixtures::ID_PHOBIA => 'Some phobia',
            ],
        ],
        'session_value' => [
            'advantages' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 1,
                AdvantagesFixtures::ID_FINANCIAL_EASE_5 => 1,
            ],
            'disadvantages' => [
                AdvantagesFixtures::ID_CRIPPLED => 1,
                AdvantagesFixtures::ID_PHOBIA => 1,
                AdvantagesFixtures::ID_POOR => 1,
            ],
            'advantages_indications' => [
                AdvantagesFixtures::ID_INFLUENT_ALLY => 'Influent ally',
                AdvantagesFixtures::ID_PHOBIA => 'Some phobia',
            ],
            'remainingExp' => 80,
        ],
    ],

    '12_mental_disorder' => [
        'next_step' => '13_primary_domains',
        'form_values' => [
            'gen-div-choice' => 1,
        ],
        'session_value' => 1,
    ],

    '13_primary_domains' => [
        'next_step' => '14_use_domain_bonuses',
        'form_values' => [
            'ost' => 'domains.close_combat',
            'domains' => [
                'domains.craft' => 5,
                'domains.relation' => 3,
                'domains.close_combat' => 2,
                'domains.erudition' => 2,
                'domains.science' => 1,
                'domains.stealth' => 1,
            ],
        ],
        'session_value' => [
            'domains' => [
                'domains.craft' => 5,
                'domains.close_combat' => 2,
                'domains.stealth' => 1,
                'domains.magience' => 0,
                'domains.natural_environment' => 0,
                'domains.demorthen_mysteries' => 0,
                'domains.occultism' => 0,
                'domains.perception' => 0,
                'domains.prayer' => 0,
                'domains.feats' => 0,
                'domains.relation' => 3,
                'domains.performance' => 0,
                'domains.science' => 1,
                'domains.shooting_and_throwing' => 0,
                'domains.travel' => 0,
                'domains.erudition' => 2,
            ],
            'ost' => 'domains.close_combat',
        ],
    ],

    '14_use_domain_bonuses' => [
        'next_step' => '15_domains_spend_exp',
        'form_values' => [
            'domains_bonuses' => [
                'domains.craft' => 0,
                'domains.close_combat' => 1,
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
                'domains.close_combat' => 1,
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
            'remaining' => 2,
        ],
    ],

    '15_domains_spend_exp' => [
        'next_step' => '16_disciplines',
        'form_values' => [
            'domains_spend_exp' => [
                'domains.craft' => 0,
                'domains.close_combat' => 1,
                'domains.stealth' => 0,
                'domains.magience' => 0,
                'domains.natural_environment' => 2,
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
                'domains.close_combat' => 1,
                'domains.stealth' => null,
                'domains.magience' => null,
                'domains.natural_environment' => 2,
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
            'remainingExp' => 50,
        ],
    ],

    '16_disciplines' => [
        'next_step' => '17_combat_arts',
        'form_values' => [
            'disciplines_spend_exp' => [
                'domains.craft' => [
                    12 => 'on',
                    45 => 'on',
                    92 => 'on',
                ],
            ],
        ],
        'session_value' => [
            'disciplines' => [
                'domains.craft' => [
                    12 => true,
                    45 => true,
                    92 => true,
                ],
            ],
            'remainingExp' => 25,
            'remainingBonusPoints' => 0,
        ],
    ],

    '17_combat_arts' => [
        'next_step' => '18_equipment',
        'form_values' => [
            'combat_arts_spend_exp' => [
                1 => 'on',
            ],
        ],
        'session_value' => [
            'combatArts' => [
                1 => true,
            ],
            'remainingExp' => 5,
        ],
    ],

    '18_equipment' => [
        'next_step' => '19_description',
        'form_values' => [
            'armors' => [
                9 => '9',
            ],
            'weapons' => [
                5 => '5',
            ],
            'equipment' => [
                'Livre<a></a>

de  règles
',
            ],
        ],
        'session_value' => [
            'armors' => [
                9 => true,
            ],
            'weapons' => [
                5 => true,
            ],
            'equipment' => [
                'Livre de règles',
            ],
        ],
    ],

    '19_description' => [
        'next_step' => '20_finish',
        'form_values' => [
            'details' => [
                'name' => 'A',
                'player_name' => 'B',
                'sex' => 'character.sex.female',
                'description' => '',
                'story' => '',
                'facts' => '',
            ],
        ],
        'session_value' => [
            'name' => 'A',
            'player_name' => 'B',
            'sex' => 'character.sex.female',
            'description' => '',
            'story' => '',
            'facts' => '',
        ],
    ],
];
