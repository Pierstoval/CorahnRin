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

namespace DataFixtures\CorahnRin;

use CorahnRin\Entity\Advantage;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class AdvantagesFixtures extends ArrayFixture implements ORMFixtureInterface
{
    public const ID_ISOLATED_ALLY = 1;
    public const ID_INFLUENT_ALLY = 3;
    public const ID_FINANCIAL_EASE_1 = 4;
    public const ID_FINANCIAL_EASE_2 = 5;
    public const ID_FINANCIAL_EASE_3 = 6;
    public const ID_FINANCIAL_EASE_4 = 7;
    public const ID_FINANCIAL_EASE_5 = 8;
    public const ID_GOOD_HEALTH = 10;
    public const ID_SOLID_MIND = 14;
    public const ID_STRONG = 15;
    public const ID_FAST = 19;
    public const ID_BRILLIANT = 20;
    public const ID_SCHOLAR = 23;
    public const ID_FINE_NOSE = 24;
    public const ID_FINE_TONGUE = 25;

    public const ID_CRIPPLED = 26;
    public const ID_SLOW = 36;
    public const ID_UNLUCKY = 38;
    public const ID_BAD_HEALTH = 39;
    public const ID_NEARSIGHTED = 41;
    public const ID_POOR = 42;
    public const ID_PHOBIA = 43;

    public const ID_SHY = 44;
    public const ID_TRAUMA = 45;
    public const FINANCIAL_EASE_IDS = [
        self::ID_FINANCIAL_EASE_1,
        self::ID_FINANCIAL_EASE_2,
        self::ID_FINANCIAL_EASE_3,
        self::ID_FINANCIAL_EASE_4,
        self::ID_FINANCIAL_EASE_5,
    ];

    public function getEntityClass(): string
    {
        return Advantage::class;
    }

    public function getObjects(): iterable
    {
        return [
            [
                'id' => self::ID_ISOLATED_ALLY,
                'name' => 'Allié isolé',
                'nameFemale' => 'Allié isolé',
                'xp' => 20,
                'description' => 'Un allié dans un village, prévôt, marchand, artisan...',
                'bonusCount' => 0,
                'bonusesFor' => [
                ],
                'requiresIndication' => 'advantages.indication.ally_isolated',
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.ally',
            ],
            [
                'id' => 2,
                'name' => 'Allié mentor',
                'nameFemale' => 'Allié mentor',
                'xp' => 40,
                'description' => 'Un mentor ou un professeur qui vous donne un bonus de +1 dans un Domaine',
                'bonusCount' => 0,
                'bonusesFor' => [
                ],
                'requiresIndication' => 'advantages.indication.ally_mentor',
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.ally',
            ],
            [
                'id' => self::ID_INFLUENT_ALLY,
                'name' => 'Allié influent',
                'nameFemale' => 'Allié influent',
                'xp' => 50,
                'description' => 'Un important homme politique, chef de guilde ou de clan, qui a un pouvoir important dans tout le pays',
                'bonusCount' => 0,
                'bonusesFor' => [
                ],
                'requiresIndication' => 'advantages.indication.ally_influent',
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.ally',
            ],
            [
                'id' => self::ID_FINANCIAL_EASE_1,
                'name' => 'Aisance financière 1',
                'nameFemale' => 'Aisance financière 1',
                'xp' => 10,
                'description' => '+20 daols d\'azur à la création du personnage',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'money_azure_20',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.financial_ease',
            ],
            [
                'id' => self::ID_FINANCIAL_EASE_2,
                'name' => 'Aisance financière 2',
                'nameFemale' => 'Aisance financière 2',
                'xp' => 20,
                'description' => '+50 daols d\'azur à la création du personnage',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'money_azure_50',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.financial_ease',
            ],
            [
                'id' => self::ID_FINANCIAL_EASE_3,
                'name' => 'Aisance financière 3',
                'nameFemale' => 'Aisance financière 3',
                'xp' => 30,
                'description' => '+10 daols de givre à la création du personnage',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'money_frost_10',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.financial_ease',
            ],
            [
                'id' => self::ID_FINANCIAL_EASE_4,
                'name' => 'Aisance financière 4',
                'nameFemale' => 'Aisance financière 4',
                'xp' => 40,
                'description' => '+50 daols de givre à la création du personnage',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'money_frost_50',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.financial_ease',
            ],
            [
                'id' => self::ID_FINANCIAL_EASE_5,
                'name' => 'Aisance financière 5',
                'nameFemale' => 'Aisance financière 5',
                'xp' => 50,
                'description' => '+100 daols de givre à la création du personnage',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'money_frost_100',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => 'advantages.group.financial_ease',
            ],
            [
                'id' => 9,
                'name' => 'Beau',
                'nameFemale' => 'Belle',
                'xp' => 30,
                'description' => '+1 aux jets de Relation et Représentation',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.relation',
                    'domains.performance',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_GOOD_HEALTH,
                'name' => 'Bonne santé',
                'nameFemale' => 'Bonne santé',
                'xp' => 40,
                'description' => '+1 case d\'état de santé, +1 aux jets de Vigueur face à la maladie et aux poisons',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'health',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 11,
                'name' => 'Bonne vue',
                'nameFemale' => 'Bonne vue',
                'xp' => 30,
                'description' => '+1 aux jets de Perception concernant la vue et Tir et Lancer',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.perception',
                    'domains.shooting_and_throwing',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 12,
                'name' => 'Charismatique',
                'nameFemale' => 'Charismatique',
                'xp' => 30,
                'description' => '+1 aux jets de Relation et Représentation',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.relation',
                    'domains.performance',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 13,
                'name' => 'Endurant',
                'nameFemale' => 'Endurante',
                'xp' => 30,
                'description' => '+1 au score de Vigueur et aux jets de Prouesses concernant l\'endurance',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'stamina',
                    'domains.feats',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_SOLID_MIND,
                'name' => 'Esprit solide',
                'nameFemale' => 'Esprit solide',
                'xp' => 30,
                'description' => '+1 au score de Résistance Mentale',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'mental_resistance',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_STRONG,
                'name' => 'Fort',
                'nameFemale' => 'Forte',
                'xp' => 40,
                'description' => '+1 aux jets de Prouesses concernant la force, Combat au Contact, Tir & lancer',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.close_combat',
                    'domains.feats',
                    'domains.shooting_and_throwing',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 16,
                'name' => 'Intuitif',
                'nameFemale' => 'Intuitive',
                'xp' => 40,
                'description' => '+1 aux jets de Mystères Demorthèn, Voyage et Relation',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.demorthen_mysteries',
                    'domains.relation',
                    'domains.travel',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 17,
                'name' => 'Leste',
                'nameFemale' => 'Leste',
                'xp' => 40,
                'description' => '+1 au score de Défense, et aux jets de Discrétion et de Prouesses concernant l\'agilité',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'defense',
                    'domains.stealth',
                    'domains.feats',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 18,
                'name' => 'Ouïe fine',
                'nameFemale' => 'Ouïe fine',
                'xp' => 20,
                'description' => '+1 aux jets de Perception auditive',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.performance',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_FAST,
                'name' => 'Rapide',
                'nameFemale' => 'Rapide',
                'xp' => 20,
                'description' => '+1 au score de Rapidité',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'speed',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_BRILLIANT,
                'name' => 'Vif d\'esprit',
                'nameFemale' => 'Vive d\'esprit',
                'xp' => 40,
                'description' => '+1 aux jets de Science, Magience et Occultisme',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.magience',
                    'domains.occultism',
                    'domains.science',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 21,
                'name' => 'Chanceux',
                'nameFemale' => 'Chanceuse',
                'xp' => 30,
                'description' => '+1 aux jets de Chance',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'luck',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 22,
                'name' => 'Instinct de survie',
                'nameFemale' => 'Instinct de survie',
                'xp' => 30,
                'description' => '+1 point de Survie',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'survival',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_SCHOLAR,
                'name' => 'Lettré',
                'nameFemale' => 'Lettrée',
                'xp' => 20,
                'description' => 'Le personnage sait lire et écrire, et choisit un bonus de +1 au choix : Erudition, Magience, Science ou Occultisme',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'domains.erudition',
                    'domains.science',
                    'domains.magience',
                    'domains.occultism',
                ],
                'requiresIndication' => 'advantages.indication.scholar',
                'indicationType' => 'single_choice',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_FINE_NOSE,
                'name' => 'Nez fin',
                'nameFemale' => 'Nez fin',
                'xp' => 10,
                'description' => '+1 aux jets de Perception concernant l\'odorat',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.perception',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => 25,
                'name' => 'Palais fin',
                'nameFemale' => 'Palais fin',
                'xp' => 10,
                'description' => '+1 aux jets de Perception concernant le goût',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.perception',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 0,
                'group' => null,
            ],
            [
                'id' => self::ID_CRIPPLED,
                'name' => 'Boiteux',
                'nameFemale' => 'Boiteuse',
                'xp' => 30,
                'description' => '-1 en Rapidité et en Défense',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'defense',
                    'speed',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 27,
                'name' => 'Dépendance',
                'nameFemale' => 'Dépendance',
                'xp' => 20,
                'description' => '-1 en Vigueur, et une addiction (tabac, alcool, drogue...)',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'stamina',
                ],
                'requiresIndication' => 'advantages.indication.dependence',
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 28,
                'name' => 'Douillet',
                'nameFemale' => 'Douillette',
                'xp' => 20,
                'description' => '-1 au score de Vigueur et aux jets de Prouesses concernant l\'endurance',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'stamina',
                    'domains.feats',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 29,
                'name' => 'Ennemi',
                'nameFemale' => 'Ennemi',
                'xp' => 30,
                'description' => 'Une personne en veut au PJ et fera tout pour lui nuire',
                'bonusCount' => 0,
                'bonusesFor' => [
                ],
                'requiresIndication' => 'advantages.indication.enemy',
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 30,
                'name' => 'Esprit faible',
                'nameFemale' => 'Esprit faible',
                'xp' => 20,
                'description' => '-1 au score de Résistance Mentale',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'mental_resistance',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 31,
                'name' => 'Faible',
                'nameFemale' => 'Faible',
                'xp' => 30,
                'description' => '-1 aux jets de Prouesses concernant la force, Combat au Contact, Tir & lancer',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.close_combat',
                    'domains.feats',
                    'domains.shooting_and_throwing',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 32,
                'name' => 'Lent d\'esprit',
                'nameFemale' => 'Lente d\'esprit',
                'xp' => 30,
                'description' => '-1 aux jets de Science, Magience et Occultisme',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.science',
                    'domains.occultism',
                    'domains.magience',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 33,
                'name' => 'Fragile',
                'nameFemale' => 'Fragile',
                'xp' => 20,
                'description' => '-1 point de Survie',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'survival',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 34,
                'name' => 'Obtus',
                'nameFemale' => 'Obtuse',
                'xp' => 30,
                'description' => '-1 aux jets de Mystères Demorthèn, Voyage et Relation',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.demorthen_mysteries',
                    'domains.travel',
                    'domains.relation',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 35,
                'name' => 'Laid',
                'nameFemale' => 'Laide',
                'xp' => 20,
                'description' => '-1 aux jets de Relation et Représentation',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.relation',
                    'domains.performance',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_SLOW,
                'name' => 'Lent',
                'nameFemale' => 'Lente',
                'xp' => 10,
                'description' => '-1 au score de Rapidité',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'speed',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 37,
                'name' => 'Mal entendant',
                'nameFemale' => 'Mal entendante',
                'xp' => 20,
                'description' => '-1 aux jets de Perception auditive',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.perception',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_UNLUCKY,
                'name' => 'Malchanceux',
                'nameFemale' => 'Malchanceuse',
                'xp' => 10,
                'description' => '-1 aux jets de Chance',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'luck',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_BAD_HEALTH,
                'name' => 'Maladif',
                'nameFemale' => 'Maladive',
                'xp' => 30,
                'description' => '-1 case d\'état de santé, -1 point de Vigueur',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'health',
                    'stamina',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 40,
                'name' => 'Maladroit',
                'nameFemale' => 'Maladroite',
                'xp' => 30,
                'description' => '-1 au score de Défense, et aux jets de Discrétion et de Prouesses concernant l\'agilité',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'defense',
                    'domains.stealth',
                    'domains.feats',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_NEARSIGHTED,
                'name' => 'Myope',
                'nameFemale' => 'Myope',
                'xp' => 20,
                'description' => '-1 aux jets de Perception concernant la vue et Tir et Lancer',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.perception',
                    'domains.shooting_and_throwing',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_POOR,
                'name' => 'Pauvre',
                'nameFemale' => 'Pauvre',
                'xp' => 10,
                'description' => 'Le PJ ne disposera que du quart de la somme en Daols fournie à la création',
                'bonusCount' => 0,
                'bonusesFor' => [
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_PHOBIA,
                'name' => 'Phobie',
                'nameFemale' => 'Phobie',
                'xp' => 40,
                'description' => '+1 point de trauma, et souffre du désordre Phobie en plus de son désordre actuel',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'trauma',
                ],
                'requiresIndication' => 'advantages.indication.phobia',
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_SHY,
                'name' => 'Timide',
                'nameFemale' => 'Timide',
                'xp' => 10,
                'description' => '-1 aux jets de Relation et Représentation',
                'bonusCount' => 0,
                'bonusesFor' => [
                    'domains.relation',
                    'domains.performance',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => self::ID_TRAUMA,
                'name' => 'Traumatisme',
                'nameFemale' => 'Traumatisme',
                'xp' => 10,
                'description' => '+1 point de trauma',
                'bonusCount' => 2,
                'bonusesFor' => [
                    'trauma',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 46,
                'name' => 'Anosmie',
                'nameFemale' => 'Anosmie',
                'xp' => 5,
                'description' => '-1 aux jets de Perception concernant l\'odorat',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.perception',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
            [
                'id' => 47,
                'name' => 'Agueusie',
                'nameFemale' => 'Agueusie',
                'xp' => 5,
                'description' => '-1 aux jets de Perception concernant le goût',
                'bonusCount' => 1,
                'bonusesFor' => [
                    'domains.perception',
                ],
                'requiresIndication' => null,
                'indicationType' => 'single_value',
                'isDisadvantage' => 1,
                'group' => null,
            ],
        ];
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-advantage-';
    }
}
