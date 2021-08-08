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
    'values' => [
        '01_people' => 1,
        '02_job' => 1, // Artisan
        '03_birthplace' => 1,
        '04_geo' => 1, // Rural (natural env)
        '05_social_class' => [
            'id' => 1,
            'domains' => [
                0 => 'domains.natural_environment',
                1 => 'domains.perception',
            ],
        ],
        '06_age' => 31, // +2 bonus points
        '07_setbacks' => [
            2 => [
                'id' => 2,
                'avoided' => false,
            ],
            3 => [
                'id' => 3,
                'avoided' => false,
            ],
        ],
        '08_ways' => [
            'ways.combativeness' => 5,
            'ways.creativity' => 4,
            'ways.empathy' => 3,
            'ways.reason' => 2,
            'ways.conviction' => 1,
        ],
        '09_traits' => [
            'quality' => 1,
            'flaw' => 10,
        ],
        '10_orientation' => 'character.orientation.instinctive',
        '11_advantages' => [
            'advantages' => [
                22 => 2, // Survival -45
                8 => 1, // Financial aid 5 -50
            ],
            'disadvantages' => [
                31 => 1, // Crippled +30
                47 => 1, // Poor +10
                AdvantagesFixtures::ID_PHOBIA => 1, // Phobia +40
            ],
            'advantages_indications' => [
                AdvantagesFixtures::ID_PHOBIA => 'Some phobia',
            ],
            'remainingExp' => 85,
        ],
        '12_mental_disorder' => 1,
        '13_primary_domains' => [
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
        '14_use_domain_bonuses' => [
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
        '15_domains_spend_exp' => [
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
            'remainingExp' => 55,
        ],
        '16_disciplines' => [
            'disciplines' => [
                'domains.craft' => [
                    12 => true,
                    45 => true,
                    92 => true,
                ],
            ],
            'remainingExp' => 30,
            'remainingBonusPoints' => 0,
        ],
        '17_combat_arts' => [
            'combatArts' => [
                1 => true,
            ],
            'remainingExp' => 10,
        ],
        '18_equipment' => [
            'armors' => [
                9 => true,
            ],
            'weapons' => [
                5 => true,
            ],
            'equipment' => [
                'Livre de règles',
                'Un grimoire',
            ],
        ],
        '19_description' => [
            'name' => 'A',
            'player_name' => 'B',
            'sex' => 'character.sex.female',
            'description' => 'Some kind of description',
            'story' => 'An incredible story',
            'facts' => 'Of course, something true',
        ],
        '20_finish' => true,
    ],

    'expected_values' => [
        ['value' => 1, 'property_path' => 'people.id'],

        ['value' => 1, 'property_path' => 'job.id'],

        ['value' => 1, 'property_path' => 'birthplace.id'],

        ['value' => 1, 'property_path' => 'geoLiving.id'],

        ['value' => 1, 'property_path' => 'socialClass.id'],
        ['value' => 'domains.natural_environment', 'property_path' => 'socialClassDomain1'],
        ['value' => 'domains.perception', 'property_path' => 'socialClassDomain2'],

        ['value' => 31, 'property_path' => 'age'],

        ['value' => 2, 'property_path' => 'setbacks[0].setback.id'],
        ['value' => 3, 'property_path' => 'setbacks[1].setback.id'],

        ['value' => 5, 'property_path' => 'combativeness'],
        ['value' => 5, 'property_path' => 'ways.combativeness'],
        ['value' => 4, 'property_path' => 'creativity'],
        ['value' => 4, 'property_path' => 'ways.creativity'],
        ['value' => 3, 'property_path' => 'empathy'],
        ['value' => 3, 'property_path' => 'ways.empathy'],
        ['value' => 2, 'property_path' => 'reason'],
        ['value' => 2, 'property_path' => 'ways.reason'],
        ['value' => 1, 'property_path' => 'conviction'],
        ['value' => 1, 'property_path' => 'ways.conviction'],

        ['value' => 1, 'property_path' => 'quality.id'],
        ['value' => 10, 'property_path' => 'flaw.id'],
        ['value' => 1, 'property_path' => 'mentalDisorder.id'],

        ['value' => 'character.orientation.instinctive', 'property_path' => 'orientation'],

        ['value' => 22, 'property_path' => 'advantages[0].advantage.id'],
        ['value' => 2, 'property_path' => 'advantages[0].score'],

        ['value' => 8, 'property_path' => 'advantages[1].advantage.id'],
        ['value' => 1, 'property_path' => 'advantages[1].score'],
        ['value' => '', 'property_path' => 'advantages[1].indication'],

        ['value' => 31, 'property_path' => 'disadvantages[0].advantage.id'],
        ['value' => 1, 'property_path' => 'disadvantages[0].score'],
        ['value' => '', 'property_path' => 'disadvantages[0].indication'],

        ['value' => 47, 'property_path' => 'disadvantages[1].advantage.id'],
        ['value' => 1, 'property_path' => 'disadvantages[1].score'],
        ['value' => '', 'property_path' => 'disadvantages[1].indication'],

        ['value' => AdvantagesFixtures::ID_PHOBIA, 'property_path' => 'disadvantages[2].advantage.id'],
        ['value' => 1, 'property_path' => 'disadvantages[2].score'],
        ['value' => 'Some phobia', 'property_path' => 'disadvantages[2].indication'],

        ['value' => 5, 'property_path' => 'domains.craft'],
        ['value' => 5, 'property_path' => 'domains.closeCombat'],
        ['value' => 1, 'property_path' => 'domains.stealth'],
        ['value' => 0, 'property_path' => 'domains.magience'],
        ['value' => 4, 'property_path' => 'domains.naturalEnvironment'],
        ['value' => 0, 'property_path' => 'domains.demorthenMysteries'],
        ['value' => 0, 'property_path' => 'domains.occultism'],
        ['value' => 1, 'property_path' => 'domains.perception'],
        ['value' => 0, 'property_path' => 'domains.prayer'],
        ['value' => 0, 'property_path' => 'domains.feats'],
        ['value' => 3, 'property_path' => 'domains.relation'],
        ['value' => 0, 'property_path' => 'domains.performance'],
        ['value' => 1, 'property_path' => 'domains.science'],
        ['value' => 0, 'property_path' => 'domains.shootingAndThrowing'],
        ['value' => 0, 'property_path' => 'domains.travel'],
        ['value' => 2, 'property_path' => 'domains.erudition'],

        ['value' => 'domains.close_combat', 'property_path' => 'ostService'],

        ['value' => 'domains.craft', 'property_path' => 'disciplines[0].domain'],
        ['value' => 6, 'property_path' => 'disciplines[0].score'],
        ['value' => 12, 'property_path' => 'disciplines[0].discipline.id'],
        ['value' => 'domains.craft', 'property_path' => 'disciplines[1].domain'],
        ['value' => 6, 'property_path' => 'disciplines[1].score'],
        ['value' => 45, 'property_path' => 'disciplines[1].discipline.id'],
        ['value' => 'domains.craft', 'property_path' => 'disciplines[2].domain'],
        ['value' => 6, 'property_path' => 'disciplines[2].score'],
        ['value' => 92, 'property_path' => 'disciplines[2].discipline.id'],

        ['value' => 1, 'property_path' => 'combatArts[0].id'],
        ['value' => 10, 'property_path' => 'experienceActual'],

        ['value' => 9, 'property_path' => 'armors[0].id'],
        ['value' => 5, 'property_path' => 'weapons[0].id'],
        ['value' => ['Livre de règles', 'Un grimoire'], 'property_path' => 'inventory'],

        ['value' => 'A', 'property_path' => 'name'],
        ['value' => 'B', 'property_path' => 'playerName'],
        ['value' => 'character.sex.female', 'property_path' => 'sex'],
        ['value' => 'Some kind of description', 'property_path' => 'description'],
        ['value' => 'An incredible story', 'property_path' => 'story'],
        ['value' => 'Of course, something true', 'property_path' => 'facts'],

        ['value' => 0, 'property_path' => 'experienceSpent'],
    ],
];
