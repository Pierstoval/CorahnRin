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

use CorahnRin\Document\Discipline;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class DisciplinesFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public const ID_ACROBATICS = 1;
    public const ID_AGRICULTUR = 2;
    public const ID_CROSSBOW = 3;
    public const ID_ARCHITECTURE = 4;
    public const ID_BOW = 5;
    public const ID_BLUNT_WEAPONS = 6;
    public const ID_JET_WEAPONS = 7;
    public const ID_COMBAT_ARTIFACT = 8;
    public const ID_CARRIAGE = 9;
    public const ID_SPIEL = 10;
    public const ID_STAVES = 11;
    public const ID_JEWELRY = 12;
    public const ID_BOTANICAL = 13;
    public const ID_CAMOUFLAGE = 14;
    public const ID_CARTOGRAPHY = 15;
    public const ID_SINGING = 16;
    public const ID_CHARM = 17;
    public const ID_VARIGAL_CROSSROADS = 18;
    public const ID_BARE_HANDS_FIGHT = 19;
    public const ID_BLIND_COMBAT = 20;
    public const ID_COMEDY = 21;
    public const ID_COMMANDMENT = 22;
    public const ID_CONCENTRATION = 23;
    public const ID_CONFECTION = 24;
    public const ID_KNOWLEDGE_OF_MENTAL_DISORDERS = 25;
    public const ID_KNOWLEDGE_OF_AFACTION = 26;
    public const ID_KNOWLEDGE_FLUX = 27;
    public const ID_KNOWLEDGE_OF_THE_TEMPLE = 28;
    public const ID_RUNNING = 29;
    public const ID_COOKING = 30;
    public const ID_DANCE = 31;
    public const ID_DIPLOMACY = 32;
    public const ID_DISTILLATION = 33;
    public const ID_DOCTRINE_OF_THE_TEMPLE = 34;
    public const ID_ANIMAL_TRAINING = 35;
    public const ID_ENDURANCE = 36;
    public const ID_SWORDS = 37;
    public const ID_HORSE_RIDING = 38;
    public const ID_CLIMBING = 39;
    public const ID_ESOTERICISM = 40;
    public const ID_ETIQUETTE = 41;
    public const ID_EVALUATION = 42;
    public const ID_EVASION = 43;
    public const ID_FLUX_EXTRACTION = 44;
    public const ID_MINERAL_EXTRACTION = 45;
    public const ID_FAUNA_AND_FLORA = 46;
    public const ID_WROUGHT = 47;
    public const ID_STEALTH = 48;
    public const ID_GEOGRAPHY = 49;
    public const ID_GEOLOGY = 50;
    public const ID_AXES = 51;
    public const ID_HERALDRY = 52;
    public const ID_HERBALISM = 53;
    public const ID_HISTORY = 54;
    public const ID_HYPNOSIS = 55;
    public const ID_ENGINEERING = 56;
    public const ID_MUSIC_INSTRUMENT = 57;
    public const ID_DREAMS_INTERPRETATION = 58;
    public const ID_INTIMIDATION = 59;
    public const ID_GAMES = 60;
    public const ID_JUGGLING = 61;
    public const ID_SHORT_BLADES = 62;
    public const ID_OLD_LANGUAGE = 63;
    public const ID_LANGUAGES = 64;
    public const ID_LIP_READING = 65;
    public const ID_MAGIENTIST_MACHINERY = 66;
    public const ID_LEATHER = 67;
    public const ID_MECHANICAL = 68;
    public const ID_MEDICINE = 69;
    public const ID_TRADITIONAL_MEDICINE = 70;
    public const ID_MEDITATION = 71;
    public const ID_CARPENTRY = 72;
    public const ID_MIMICRY = 73;
    public const ID_MIRACLES = 74;
    public const ID_SWIMMING = 75;
    public const ID_NAVIGATION = 76;
    public const ID_OBSERVATION = 77;
    public const ID_ORIENTATION = 78;
    public const ID_MAGIENTIST_TOOL = 79;
    public const ID_PAINTING = 80;
    public const ID_PERSUASION = 81;
    public const ID_MENTAL_PHENOMENA = 82;
    public const ID_TRACKING = 83;
    public const ID_POLICY = 84;
    public const ID_POTTERY = 85;
    public const ID_FIRST_AID = 86;
    public const ID_MAGIENTIST_PRINCIPLES = 87;
    public const ID_FLUX_REFINING = 88;
    public const ID_CONTEMPLATION = 89;
    public const ID_REPAIRING_ARTIFACTS = 90;
    public const ID_DEMORTHEN_KNOWLEDGE = 91;
    public const ID_SCULPTURE = 92;
    public const ID_SHARPENED_SENSES = 93;
    public const ID_LOCKSMITH = 94;
    public const ID_SIGIL_RANN = 95;
    public const ID_VARIGAL_SIGNS = 96;
    public const ID_SPIRITUALITY = 97;
    public const ID_SURVIVAL = 98;
    public const ID_TRADITIONS_DEMORTHEN = 99;
    public const ID_USING_ARTIFACTS = 100;
    public const ID_VENTRILOQUISM = 101;
    public const ID_VIGILANCE = 102;
    public const ID_THEFT = 103;
    public const ID_ZOOLOGY = 104;
    public const ID_HASTE_WEAPONS = 105;
    public const ID_ASTRONOMY = 106;
    public const ID_LEGENDS = 107;
    public const ID_WORKFORCE = 108;
    public const ID_MIND_TREATMENT = 109;

    public function getEntityClass(): string
    {
        return Discipline::class;
    }

    public function getObjects(): iterable
    {
        $book = $this->getReference('corahnrin-book-'.BooksFixtures::ID_BOOK_1_UNIVERSE);

        return [
            [
                'id' => self::ID_ACROBATICS,
                'name' => 'Acrobaties',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.feats',
                ],
            ],
            [
                'id' => self::ID_AGRICULTUR,
                'name' => 'Agriculture',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.natural_environment',
                ],
            ],
            [
                'id' => self::ID_CROSSBOW,
                'name' => 'Arbalètes',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.shooting_and_throwing',
                ],
            ],
            [
                'id' => self::ID_ARCHITECTURE,
                'name' => 'Architecture',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_BOW,
                'name' => 'Arcs',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.shooting_and_throwing',
                ],
            ],
            [
                'id' => self::ID_BLUNT_WEAPONS,
                'name' => 'Armes contondantes',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_JET_WEAPONS,
                'name' => 'Armes de jet',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.shooting_and_throwing',
                ],
            ],
            [
                'id' => self::ID_COMBAT_ARTIFACT,
                'name' => 'Artefact de combat',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                    1 => 'domains.occultism',
                    2 => 'domains.science',
                    3 => 'domains.shooting_and_throwing',
                ],
            ],
            [
                'id' => self::ID_CARRIAGE,
                'name' => 'Attelage',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.travel',
                ],
            ],
            [
                'id' => self::ID_SPIEL,
                'name' => 'Baratin',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_STAVES,
                'name' => 'Bâtons',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_JEWELRY,
                'name' => 'Bijouterie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_BOTANICAL,
                'name' => 'Botanique',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_CAMOUFLAGE,
                'name' => 'Camouflage',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.stealth',
                ],
            ],
            [
                'id' => self::ID_CARTOGRAPHY,
                'name' => 'Cartographie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.travel',
                ],
            ],
            [
                'id' => self::ID_SINGING,
                'name' => 'Chant',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.performance',
                ],
            ],
            [
                'id' => self::ID_CHARM,
                'name' => 'Charme',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_VARIGAL_CROSSROADS,
                'name' => 'Chemins de traverse (Varigal)',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.travel',
                ],
            ],
            [
                'id' => self::ID_BARE_HANDS_FIGHT,
                'name' => 'Combat à mains nues',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_BLIND_COMBAT,
                'name' => 'Combat aveugle',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_COMEDY,
                'name' => 'Comédie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.performance',
                ],
            ],
            [
                'id' => self::ID_COMMANDMENT,
                'name' => 'Commandement',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_CONCENTRATION,
                'name' => 'Concentration',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                    1 => 'domains.prayer',
                ],
            ],
            [
                'id' => self::ID_CONFECTION,
                'name' => 'Confection',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_KNOWLEDGE_OF_MENTAL_DISORDERS,
                'name' => 'Conn. troubles mentaux',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_KNOWLEDGE_OF_AFACTION,
                'name' => 'Conn. d\'une faction',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_KNOWLEDGE_FLUX,
                'name' => 'Conn. des Flux',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.magience',
                ],
            ],
            [
                'id' => self::ID_KNOWLEDGE_OF_THE_TEMPLE,
                'name' => 'Conn. du Temple',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.prayer',
                ],
            ],
            [
                'id' => self::ID_RUNNING,
                'name' => 'Course',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.feats',
                ],
            ],
            [
                'id' => self::ID_COOKING,
                'name' => 'Cuisine',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_DANCE,
                'name' => 'Danse',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.performance',
                ],
            ],
            [
                'id' => self::ID_DIPLOMACY,
                'name' => 'Diplomatie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_DISTILLATION,
                'name' => 'Distillation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_DOCTRINE_OF_THE_TEMPLE,
                'name' => 'Doctrine du Temple',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_ANIMAL_TRAINING,
                'name' => 'Dressage d\'animaux',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.natural_environment',
                ],
            ],
            [
                'id' => self::ID_ENDURANCE,
                'name' => 'Endurance',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.feats',
                ],
            ],
            [
                'id' => self::ID_SWORDS,
                'name' => 'Épées',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_HORSE_RIDING,
                'name' => 'Équitation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.travel',
                ],
            ],
            [
                'id' => self::ID_CLIMBING,
                'name' => 'Escalade',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.feats',
                ],
            ],
            [
                'id' => self::ID_ESOTERICISM,
                'name' => 'Ésotérisme',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.occultism',
                ],
            ],
            [
                'id' => self::ID_ETIQUETTE,
                'name' => 'Étiquette d\'un milieu social',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_EVALUATION,
                'name' => 'Évaluation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.perception',
                ],
            ],
            [
                'id' => self::ID_EVASION,
                'name' => 'Évasion',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.feats',
                ],
            ],
            [
                'id' => self::ID_FLUX_EXTRACTION,
                'name' => 'Extraction de Flux',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.magience',
                ],
            ],
            [
                'id' => self::ID_MINERAL_EXTRACTION,
                'name' => 'Extraction minière',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_FAUNA_AND_FLORA,
                'name' => 'Faune et flore',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.natural_environment',
                ],
            ],
            [
                'id' => self::ID_WROUGHT,
                'name' => 'Forge',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_STEALTH,
                'name' => 'Furtivité',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.stealth',
                ],
            ],
            [
                'id' => self::ID_GEOGRAPHY,
                'name' => 'Géographie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_GEOLOGY,
                'name' => 'Géologie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_AXES,
                'name' => 'Haches',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_HERALDRY,
                'name' => 'Héraldique',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_HERBALISM,
                'name' => 'Herboristerie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                    1 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_HISTORY,
                'name' => 'Histoire',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_HYPNOSIS,
                'name' => 'Hypnose',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.occultism',
                ],
            ],
            [
                'id' => self::ID_ENGINEERING,
                'name' => 'Ingénierie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_MUSIC_INSTRUMENT,
                'name' => 'Instrument de musique',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.performance',
                ],
            ],
            [
                'id' => self::ID_DREAMS_INTERPRETATION,
                'name' => 'Interprétation des rêves',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.occultism',
                ],
            ],
            [
                'id' => self::ID_INTIMIDATION,
                'name' => 'Intimidation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_GAMES,
                'name' => 'Jeux',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.performance',
                ],
            ],
            [
                'id' => self::ID_JUGGLING,
                'name' => 'Jonglage',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.performance',
                ],
            ],
            [
                'id' => self::ID_SHORT_BLADES,
                'name' => 'Lames courtes',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_OLD_LANGUAGE,
                'name' => 'Langue ancienne',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                ],
            ],
            [
                'id' => self::ID_LANGUAGES,
                'name' => 'Langues',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_LIP_READING,
                'name' => 'Lecture sur les lèvres',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.perception',
                ],
            ],
            [
                'id' => self::ID_MAGIENTIST_MACHINERY,
                'name' => 'Machinerie magientiste',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                    1 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_LEATHER,
                'name' => 'Maroquinerie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_MECHANICAL,
                'name' => 'Mécanique',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_MEDICINE,
                'name' => 'Médecine',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.magience',
                    1 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_TRADITIONAL_MEDICINE,
                'name' => 'Médecine traditionnelle',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                ],
            ],
            [
                'id' => self::ID_MEDITATION,
                'name' => 'Méditation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                ],
            ],
            [
                'id' => self::ID_CARPENTRY,
                'name' => 'Menuiserie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_MIMICRY,
                'name' => 'Mimétisme',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.stealth',
                ],
            ],
            [
                'id' => self::ID_MIRACLES,
                'name' => 'Miracles',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.prayer',
                ],
            ],
            [
                'id' => self::ID_SWIMMING,
                'name' => 'Natation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.feats',
                ],
            ],
            [
                'id' => self::ID_NAVIGATION,
                'name' => 'Navigation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.travel',
                ],
            ],
            [
                'id' => self::ID_OBSERVATION,
                'name' => 'Observation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.perception',
                ],
            ],
            [
                'id' => self::ID_ORIENTATION,
                'name' => 'Orientation',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.natural_environment',
                    1 => 'domains.perception',
                    2 => 'domains.travel',
                ],
            ],
            [
                'id' => self::ID_MAGIENTIST_TOOL,
                'name' => 'Outil magientiste',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                    1 => 'domains.occultism',
                    2 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_PAINTING,
                'name' => 'Peinture',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_PERSUASION,
                'name' => 'Persuasion',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.relation',
                ],
            ],
            [
                'id' => self::ID_MENTAL_PHENOMENA,
                'name' => 'Phénomènes mentaux',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.occultism',
                ],
            ],
            [
                'id' => self::ID_TRACKING,
                'name' => 'Pistage',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.natural_environment',
                ],
            ],
            [
                'id' => self::ID_POLICY,
                'name' => 'Politique',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_POTTERY,
                'name' => 'Poterie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_FIRST_AID,
                'name' => 'Premiers soins',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.natural_environment',
                ],
            ],
            [
                'id' => self::ID_MAGIENTIST_PRINCIPLES,
                'name' => 'Principes magientistes',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_FLUX_REFINING,
                'name' => 'Raffinage de Flux',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.magience',
                ],
            ],
            [
                'id' => self::ID_CONTEMPLATION,
                'name' => 'Recueillement',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.prayer',
                ],
            ],
            [
                'id' => self::ID_REPAIRING_ARTIFACTS,
                'name' => 'Réparation d\'artefacts',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.magience',
                    1 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_DEMORTHEN_KNOWLEDGE,
                'name' => 'Savoirs demorthèn',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                ],
            ],
            [
                'id' => self::ID_SCULPTURE,
                'name' => 'Sculpture',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_SHARPENED_SENSES,
                'name' => 'Sens aiguisés',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.perception',
                ],
            ],
            [
                'id' => self::ID_LOCKSMITH,
                'name' => 'Serrurerie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.craft',
                ],
            ],
            [
                'id' => self::ID_SIGIL_RANN,
                'name' => 'Sigil Rann',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                ],
            ],
            [
                'id' => self::ID_VARIGAL_SIGNS,
                'name' => 'Signes (Varigal)',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.travel',
                ],
            ],
            [
                'id' => self::ID_SPIRITUALITY,
                'name' => 'Spiritualité',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.demorthen_mysteries',
                    1 => 'domains.prayer',
                    2 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_SURVIVAL,
                'name' => 'Survie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.natural_environment',
                ],
            ],
            [
                'id' => self::ID_TRADITIONS_DEMORTHEN,
                'name' => 'Traditions demorthèn',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_USING_ARTIFACTS,
                'name' => 'Utilisation d\'artefacts',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.magience',
                ],
            ],
            [
                'id' => self::ID_VENTRILOQUISM,
                'name' => 'Ventriloquie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.performance',
                ],
            ],
            [
                'id' => self::ID_VIGILANCE,
                'name' => 'Vigilance',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.perception',
                ],
            ],
            [
                'id' => self::ID_THEFT,
                'name' => 'Vol à la tire',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.stealth',
                ],
            ],
            [
                'id' => self::ID_ZOOLOGY,
                'name' => 'Zoologie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
            [
                'id' => self::ID_HASTE_WEAPONS,
                'name' => 'Armes d\'hast',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.close_combat',
                ],
            ],
            [
                'id' => self::ID_ASTRONOMY,
                'name' => 'Astronomie',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_LEGENDS,
                'name' => 'Légendes',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.erudition',
                ],
            ],
            [
                'id' => self::ID_WORKFORCE,
                'name' => 'Travail de force',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.feats',
                ],
            ],
            [
                'id' => self::ID_MIND_TREATMENT,
                'name' => 'Traitement de l\'esprit',
                'description' => '',
                'rank' => 'discipline.rank.professional',
                'book' => $book,
                'domains' => [
                    0 => 'domains.science',
                ],
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            BooksFixtures::class,
        ];
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-discipline-';
    }
}
