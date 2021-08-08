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
    'values' => [
        '01_people' => 1,
        '02_job' => 15,
        '03_birthplace' => 1,
        '04_geo' => 2,
        '05_social_class' => [
            'id' => 2,
            'domains' => [
                0 => 'domains.science',
                1 => 'domains.erudition',
            ],
        ],
        '06_age' => 21,
        '07_setbacks' => [
            9 => ['id' => 9, 'avoided' => false],
        ],
        '08_ways' => [
            'ways.combativeness' => 1,
            'ways.creativity' => 3,
            'ways.empathy' => 5,
            'ways.reason' => 5,
            'ways.conviction' => 1,
        ],
        '09_traits' => ['quality' => 57, 'flaw' => 67],
        '10_orientation' => 'character.orientation.rational',
        '11_advantages' => [
            'advantages' => [20 => 2],
            'disadvantages' => [],
            'advantages_indications' => [],
            'remainingExp' => 40,
        ],
        '12_mental_disorder' => 2,
        '13_primary_domains' => [
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
        '14_use_domain_bonuses' => [
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
        '15_domains_spend_exp' => [
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
        '16_disciplines' => [
            'disciplines' => ['domains.occultism' => [55 => true, 82 => true]],
            'remainingExp' => 15,
            'remainingBonusPoints' => 0,
        ],
        '17_combat_arts' => ['combatArts' => [], 'remainingExp' => 15],
        '18_equipment' => ['armors' => [], 'weapons' => [19 => true], 'equipment' => []],
        '19_description' => [
            'name' => 'Steren Slaine',
            'player_name' => 'Test user',
            'sex' => 'character.sex.female',
            'description' => 'Small but beautiful',
            'story' => 'A long story...',
            'facts' => 'Loves ghost stories...',
        ],
        '20_finish' => true,
    ],

    'expected_values' => [
        ['value' => 1, 'property_path' => 'people.id'],

        ['value' => 15, 'property_path' => 'job.id'],

        ['value' => 1, 'property_path' => 'birthplace.id'],

        ['value' => 2, 'property_path' => 'geoLiving.id'],

        ['value' => 2, 'property_path' => 'socialClass.id'],
        ['value' => 'domains.science', 'property_path' => 'socialClassDomain1'],
        ['value' => 'domains.erudition', 'property_path' => 'socialClassDomain2'],

        ['value' => 21, 'property_path' => 'age'],

        ['value' => 9, 'property_path' => 'setbacks[0].setback.id'],
        ['value' => false, 'property_path' => 'setbacks[0].isAvoided'],

        ['value' => 1, 'property_path' => 'combativeness'],
        ['value' => 1, 'property_path' => 'ways.combativeness'],
        ['value' => 3, 'property_path' => 'creativity'],
        ['value' => 3, 'property_path' => 'ways.creativity'],
        ['value' => 5, 'property_path' => 'empathy'],
        ['value' => 5, 'property_path' => 'ways.empathy'],
        ['value' => 5, 'property_path' => 'reason'],
        ['value' => 5, 'property_path' => 'ways.reason'],
        ['value' => 1, 'property_path' => 'conviction'],
        ['value' => 1, 'property_path' => 'ways.conviction'],

        ['value' => 57, 'property_path' => 'quality.id'],
        ['value' => 67, 'property_path' => 'flaw.id'],

        ['value' => 'character.orientation.rational', 'property_path' => 'orientation'],

        ['value' => 20, 'property_path' => 'advantages[0].advantage.id'],
        ['value' => 2, 'property_path' => 'advantages[0].score'],

        ['value' => 2, 'property_path' => 'mentalDisorder.id'],

        ['value' => 0, 'property_path' => 'domains.craft'],
        ['value' => 1, 'property_path' => 'domains.closeCombat'],
        ['value' => 0, 'property_path' => 'domains.stealth'],
        ['value' => 0, 'property_path' => 'domains.magience'],
        ['value' => 1, 'property_path' => 'domains.naturalEnvironment'],
        ['value' => 0, 'property_path' => 'domains.demorthenMysteries'],
        ['value' => 5, 'property_path' => 'domains.occultism'],
        ['value' => 1, 'property_path' => 'domains.perception'],
        ['value' => 0, 'property_path' => 'domains.prayer'],
        ['value' => 0, 'property_path' => 'domains.feats'],
        ['value' => 3, 'property_path' => 'domains.relation'],
        ['value' => 0, 'property_path' => 'domains.performance'],
        ['value' => 1, 'property_path' => 'domains.science'],
        ['value' => 0, 'property_path' => 'domains.shootingAndThrowing'],
        ['value' => 2, 'property_path' => 'domains.travel'],
        ['value' => 4, 'property_path' => 'domains.erudition'],

        ['value' => 'domains.close_combat', 'property_path' => 'ostService'],

        ['value' => 'domains.occultism', 'property_path' => 'disciplines[0].domain'],
        ['value' => 6, 'property_path' => 'disciplines[0].score'],
        ['value' => 55, 'property_path' => 'disciplines[0].discipline.id'],

        ['value' => 'domains.occultism', 'property_path' => 'disciplines[1].domain'],
        ['value' => 6, 'property_path' => 'disciplines[1].score'],
        ['value' => 82, 'property_path' => 'disciplines[1].discipline.id'],

        ['value' => [], 'property_path' => 'combatArts'],
        ['value' => 15, 'property_path' => 'experienceActual'],

        ['value' => [], 'property_path' => 'armors'],
        ['value' => 19, 'property_path' => 'weapons[0].id'],
        ['value' => [], 'property_path' => 'inventory'],

        ['value' => 'Steren Slaine', 'property_path' => 'name'],
        ['value' => 'Test user', 'property_path' => 'playerName'],
        ['value' => 'character.sex.female', 'property_path' => 'sex'],
        ['value' => 'Small but beautiful', 'property_path' => 'description'],
        ['value' => 'A long story...', 'property_path' => 'story'],
        ['value' => 'Loves ghost stories...', 'property_path' => 'facts'],

        ['value' => 0, 'property_path' => 'experienceSpent'],
    ],
];
