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

use CorahnRin\Data\DomainsData;
use CorahnRin\DTO\AdvantageDTO;
use CorahnRin\DTO\SetbackDTO;
use CorahnRin\Entity\Advantage;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\CharacterProperties\CharacterAdvantageItem;
use CorahnRin\Entity\CharacterProperties\CharacterDomains;
use CorahnRin\Entity\CharacterProperties\CharDisciplines;
use CorahnRin\Entity\CharacterProperties\CharSetbacks;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Entity\CharacterProperties\Ways;
use CorahnRin\Entity\Setback;
use DataFixtures\UsersFixtures;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use EsterenMaps\Id\ZoneId;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class CharactersFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public const ZONE_ID_DEFAULT = 1;

    public function getReferencePrefix(): ?string
    {
        return 'corahnrin-character-';
    }

    public function getMethodNameForReference(): string
    {
        return 'getNameSlug';
    }

    public function getDependencies(): array
    {
        return [
            TraitsFixtures::class,
            JobsFixtures::class,
            DisordersFixtures::class,
            SocialClassesFixtures::class,
            PeoplesFixtures::class,
            GeoEnvironmentsFixtures::class,
            UsersFixtures::class,
            DisciplinesFixtures::class,
        ];
    }

    protected function getEntityClass(): string
    {
        return Character::class;
    }

    protected function getObjects(): array
    {
        $lipsum = new \joshtronic\LoremIpsum();

        $defaults = $this->getDefaults();

        return [
            [
                'name' => 'Steren Slaine',
                'nameSlug' => 'steren-slaine',
                'playerName' => 'Nico',
                'sex' => 'character.sex.female',
                'description' => 'Just a simple description',
                'story' => 'Just a simple story',
                'facts' => 'Just a simple facts that are undeniable, y\'know',
                'inventory' => [
                    'Torch',
                    'Tent',
                ],
                'treasures' => [
                    'Black Moon manual',
                ],
                'money' => new Money(10, 5, 1),
                'orientation' => 'character.orientation.rational',
                'geoLiving' => $this->getReference('corahnrin-geo-environment-'.GeoEnvironmentsFixtures::ID_RURAL),
                'temporaryTrauma' => 0,
                'permanentTrauma' => 2,
                'hardening' => 0,
                'age' => 24,
                'mentalResistanceBonus' => 1,
                'ways' => Ways::create(1, 4, 3, 4, 3),
                'health' => new HealthCondition(),
                'maxHealth' => new HealthCondition(),
                'stamina' => 10,
                'staminaBonus' => 0,
                'survival' => 3,
                'speedBonus' => 0,
                'defenseBonus' => 0,
                'rindath' => 0,
                'rindathMax' => 0,
                'exaltation' => 0,
                'exaltationMax' => 0,
                'experienceActual' => 0,
                'experienceSpent' => 0,
                'people' => $this->getReference('corahnrin-people-'.PeoplesFixtures::ID_TRI_KAZEL),
                'armors' => [],
                'artifacts' => [],
                'miracles' => [],
                'ogham' => [],
                'weapons' => [],
                'combatArts' => [],
                'socialClass' => $this->getReference('corahnrin-social-class-'.SocialClassesFixtures::ID_NOBLETY),
                'socialClassDomain1' => DomainsData::SCIENCE['title'],
                'socialClassDomain2' => DomainsData::ERUDITION['title'],
                'ostService' => DomainsData::CLOSE_COMBAT['title'],
                'mentalDisorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_HALLUCINATION),
                'job' => $this->getReference('corahnrin-jobs-'.JobsFixtures::ID_OCCULTIST),
                'birthPlace' => ZoneId::fromInt(self::ZONE_ID_DEFAULT),
                'flaw' => $this->getReference('corahnrin-traits-'.TraitsFixtures::ID_WEAK_CHARACTER),
                'quality' => $this->getReference('corahnrin-traits-'.TraitsFixtures::ID_THOUGHTFUL),
                'advantages' => static function (Character $character, ArrayFixture $fixture) {
                    /** @var Advantage $scholarAdvantage */
                    $scholarAdvantage = $fixture->getReference('corahnrin-advantage-'.AdvantagesFixtures::ID_SCHOLAR);

                    /** @var Advantage $solidMindAdvantage */
                    $solidMindAdvantage = $fixture->getReference('corahnrin-advantage-'.AdvantagesFixtures::ID_SOLID_MIND);

                    /** @var Advantage $brilliantAdvantage */
                    $brilliantAdvantage = $fixture->getReference('corahnrin-advantage-'.AdvantagesFixtures::ID_BRILLIANT);

                    /** @var Advantage $shyAdvantage */
                    $shyAdvantage = $fixture->getReference('corahnrin-advantage-'.AdvantagesFixtures::ID_SHY);

                    $scholarDTO = AdvantageDTO::create($scholarAdvantage, 1, 'domains.occultism');
                    $solidMindDTO = AdvantageDTO::create($solidMindAdvantage, 1, '');
                    $brilliantDTO = AdvantageDTO::create($brilliantAdvantage, 2, '');
                    $shyDTO = AdvantageDTO::create($shyAdvantage, 2, '');

                    return [
                        CharacterAdvantageItem::createFromSessionDTO($character, $scholarDTO),
                        CharacterAdvantageItem::createFromSessionDTO($character, $solidMindDTO),
                        CharacterAdvantageItem::createFromSessionDTO($character, $brilliantDTO),
                        CharacterAdvantageItem::createFromSessionDTO($character, $shyDTO),
                    ];
                },
                'domains' => function (Character $character) {
                    return $this->createCharacterDomains($character, [
                        DomainsData::CRAFT['title'] => 3,
                        DomainsData::CLOSE_COMBAT['title'] => 1,
                        DomainsData::STEALTH['title'] => 3,
                        DomainsData::MAGIENCE['title'] => 0,
                        DomainsData::NATURAL_ENVIRONMENT['title'] => 1,
                        DomainsData::DEMORTHEN_MYSTERIES['title'] => 0,
                        DomainsData::OCCULTISM['title'] => 5,
                        DomainsData::PERCEPTION['title'] => 5,
                        DomainsData::PRAYER['title'] => 0,
                        DomainsData::FEATS['title'] => 2,
                        DomainsData::RELATION['title'] => 5,
                        DomainsData::PERFORMANCE['title'] => 3,
                        DomainsData::SCIENCE['title'] => 5,
                        DomainsData::SHOOTING_AND_THROWING['title'] => 0,
                        DomainsData::TRAVEL['title'] => 3,
                        DomainsData::ERUDITION['title'] => 5,
                    ]);
                },
                'disciplines' => $this->createCharacterDisciplines([
                    // Erudition
                    ['id' => DisciplinesFixtures::ID_DOCTRINE_OF_THE_TEMPLE, 'score' => 7, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_HERALDRY, 'score' => 7, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_HISTORY, 'score' => 7, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_MAGIENTIST_PRINCIPLES, 'score' => 6, 'domain' => DomainsData::ERUDITION['title']],

                    // Occultism
                    ['id' => DisciplinesFixtures::ID_ESOTERICISM, 'score' => 10, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_HYPNOSIS, 'score' => 6, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_MENTAL_PHENOMENA, 'score' => 6, 'domain' => DomainsData::OCCULTISM['title']],

                    // Perception
                    ['id' => DisciplinesFixtures::ID_OBSERVATION, 'score' => 6, 'domain' => DomainsData::PERCEPTION['title']],
                ]),
                'flux' => [],
                'setbacks' => static function (Character $character, ArrayFixture $fixture) {
                    $sessionDTO = SetbackDTO::create($fixture->getReference('corahnrin-setbacks-'.SetbacksFixtures::ID_POOR), false);

                    return [
                        CharSetbacks::createFromSessionDTO($character, $sessionDTO),
                    ];
                },
                'user' => $this->getReference('user-pierstoval'),
                'game' => null,
            ],
            [
                'name' => 'Bloated character',
                'nameSlug' => 'bloated-character',
                'playerName' => 'Alex',
                'sex' => 'character.sex.male',
                'description' => 'A character that has EVERYTHING and MANY BIG BONUSES.... Nunc mattis elit sem, non posuere felis euismod ac. Praesent feugiat pretium massa, quis facilisis odio. Nulla blandit leo quis nulla feugiat ultrices. Fusce eu molestie velit. Class aptent posuere.',
                'story' => $lipsum->paragraphs(10),
                'facts' => $lipsum->paragraphs(10),
                'inventory' => $lipsum->wordsArray(100),
                'treasures' => $lipsum->wordsArray(100),
                'money' => new Money(250, 250, 250),
                'orientation' => 'character.orientation.rational',
                'geoLiving' => $this->getReference('corahnrin-geo-environment-'.GeoEnvironmentsFixtures::ID_RURAL),
                'temporaryTrauma' => 13,
                'permanentTrauma' => 5,
                'hardening' => 18,
                'age' => 35,
                'mentalResistanceBonus' => 15,
                'ways' => Ways::create(5, 5, 5, 5, 5),
                'health' => new HealthCondition(),
                'maxHealth' => new HealthCondition(),
                'stamina' => 10,
                'staminaBonus' => 10,
                'survival' => 3,
                'speedBonus' => 10,
                'defenseBonus' => 10,
                'rindath' => 50,
                'rindathMax' => 50,
                'exaltation' => 50,
                'exaltationMax' => 50,
                'experienceActual' => 500,
                'experienceSpent' => 10000,
                'people' => $this->getReference('corahnrin-people-'.PeoplesFixtures::ID_TRI_KAZEL),
                'armors' => [
                    $this->getReference('corahnrin-armors-1'),
                    $this->getReference('corahnrin-armors-2'),
                    $this->getReference('corahnrin-armors-3'),
                    $this->getReference('corahnrin-armors-4'),
                    $this->getReference('corahnrin-armors-5'),
                    $this->getReference('corahnrin-armors-6'),
                    $this->getReference('corahnrin-armors-7'),
                    $this->getReference('corahnrin-armors-8'),
                    $this->getReference('corahnrin-armors-9'),
                    $this->getReference('corahnrin-armors-10'),
                    $this->getReference('corahnrin-armors-11'),
                    $this->getReference('corahnrin-armors-12'),
                    $this->getReference('corahnrin-armors-13'),
                    $this->getReference('corahnrin-armors-14'),
                    $this->getReference('corahnrin-armors-15'),
                ],
                'artifacts' => [],
                'miracles' => [],
                'ogham' => [],
                'weapons' => [
                    $this->getReference('corahnrin-weapons-1'),
                    $this->getReference('corahnrin-weapons-2'),
                    $this->getReference('corahnrin-weapons-3'),
                    $this->getReference('corahnrin-weapons-4'),
                    $this->getReference('corahnrin-weapons-5'),
                    $this->getReference('corahnrin-weapons-6'),
                    $this->getReference('corahnrin-weapons-7'),
                    $this->getReference('corahnrin-weapons-8'),
                    $this->getReference('corahnrin-weapons-9'),
                    $this->getReference('corahnrin-weapons-10'),
                    $this->getReference('corahnrin-weapons-11'),
                    $this->getReference('corahnrin-weapons-12'),
                    $this->getReference('corahnrin-weapons-13'),
                    $this->getReference('corahnrin-weapons-14'),
                    $this->getReference('corahnrin-weapons-15'),
                    $this->getReference('corahnrin-weapons-16'),
                    $this->getReference('corahnrin-weapons-17'),
                    $this->getReference('corahnrin-weapons-18'),
                    $this->getReference('corahnrin-weapons-19'),
                    $this->getReference('corahnrin-weapons-20'),
                    $this->getReference('corahnrin-weapons-21'),
                    $this->getReference('corahnrin-weapons-22'),
                ],
                'combatArts' => [
                    $this->getReference('corahnrin-combat-arts-1'),
                    $this->getReference('corahnrin-combat-arts-2'),
                    $this->getReference('corahnrin-combat-arts-3'),
                    $this->getReference('corahnrin-combat-arts-4'),
                    $this->getReference('corahnrin-combat-arts-5'),
                ],
                'socialClass' => $this->getReference('corahnrin-social-class-'.SocialClassesFixtures::ID_NOBLETY),
                'socialClassDomain1' => DomainsData::SCIENCE['title'],
                'socialClassDomain2' => DomainsData::ERUDITION['title'],
                'ostService' => DomainsData::CLOSE_COMBAT['title'],
                'mentalDisorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_HALLUCINATION),
                'job' => $this->getReference('corahnrin-jobs-'.JobsFixtures::ID_OCCULTIST),
                'birthPlace' => ZoneId::fromInt(self::ZONE_ID_DEFAULT),
                'flaw' => $this->getReference('corahnrin-traits-'.TraitsFixtures::ID_WEAK_CHARACTER),
                'quality' => $this->getReference('corahnrin-traits-'.TraitsFixtures::ID_THOUGHTFUL),
                'advantages' => static function (Character $character, ArrayFixture $fixture) {
                    $r = static function ($id, int $score, string $indication = '') use ($character, $fixture) {
                        /** @var Advantage $ref */
                        $ref = $fixture->getReference('corahnrin-advantage-'.$id);

                        $dto = AdvantageDTO::create($ref, $score, $indication);

                        return CharacterAdvantageItem::createFromSessionDTO($character, $dto);
                    };

                    return [
                        $r(AdvantagesFixtures::ID_INFLUENT_ALLY, 1, 'A very special friend'),
                        $r(AdvantagesFixtures::ID_FINANCIAL_EASE_5, 1, ''),
                        $r(AdvantagesFixtures::ID_GOOD_HEALTH, 2, ''),
                        $r(AdvantagesFixtures::ID_SOLID_MIND, 2, ''),
                        $r(AdvantagesFixtures::ID_STRONG, 2, ''),
                        $r(AdvantagesFixtures::ID_FAST, 2, ''),
                        $r(AdvantagesFixtures::ID_BRILLIANT, 2, ''),
                        $r(AdvantagesFixtures::ID_SCHOLAR, 1, DomainsData::ERUDITION['title']),
                        $r(AdvantagesFixtures::ID_FINE_NOSE, 2, ''),
                        $r(AdvantagesFixtures::ID_FINE_TONGUE, 2, ''),

                        $r(AdvantagesFixtures::ID_CRIPPLED, 2, ''),
                        $r(AdvantagesFixtures::ID_SLOW, 2, ''),
                        $r(AdvantagesFixtures::ID_UNLUCKY, 2, ''),
                        $r(AdvantagesFixtures::ID_NEARSIGHTED, 2, ''),
                        $r(AdvantagesFixtures::ID_POOR, 1, ''),
                        $r(AdvantagesFixtures::ID_PHOBIA, 1, 'Some phobia'),
                    ];
                },
                'domains' => function (Character $character) {
                    return $this->createCharacterDomains($character, [
                        DomainsData::CRAFT['title'] => 5,
                        DomainsData::CLOSE_COMBAT['title'] => 5,
                        DomainsData::STEALTH['title'] => 5,
                        DomainsData::MAGIENCE['title'] => 5,
                        DomainsData::NATURAL_ENVIRONMENT['title'] => 5,
                        DomainsData::DEMORTHEN_MYSTERIES['title'] => 5,
                        DomainsData::OCCULTISM['title'] => 5,
                        DomainsData::PERCEPTION['title'] => 5,
                        DomainsData::PRAYER['title'] => 5,
                        DomainsData::FEATS['title'] => 5,
                        DomainsData::RELATION['title'] => 5,
                        DomainsData::PERFORMANCE['title'] => 5,
                        DomainsData::SCIENCE['title'] => 5,
                        DomainsData::SHOOTING_AND_THROWING['title'] => 5,
                        DomainsData::TRAVEL['title'] => 5,
                        DomainsData::ERUDITION['title'] => 5,
                    ]);
                },
                'disciplines' => $this->createCharacterDisciplines([
                    ['id' => DisciplinesFixtures::ID_ACROBATICS, 'score' => 15, 'domain' => DomainsData::FEATS['title']],
                    ['id' => DisciplinesFixtures::ID_AGRICULTUR, 'score' => 15, 'domain' => DomainsData::NATURAL_ENVIRONMENT['title']],
                    ['id' => DisciplinesFixtures::ID_CROSSBOW, 'score' => 15, 'domain' => DomainsData::SHOOTING_AND_THROWING['title']],
                    ['id' => DisciplinesFixtures::ID_ARCHITECTURE, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_BOW, 'score' => 15, 'domain' => DomainsData::SHOOTING_AND_THROWING['title']],
                    ['id' => DisciplinesFixtures::ID_BLUNT_WEAPONS, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_JET_WEAPONS, 'score' => 15, 'domain' => DomainsData::SHOOTING_AND_THROWING['title']],
                    ['id' => DisciplinesFixtures::ID_COMBAT_ARTIFACT, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_COMBAT_ARTIFACT, 'score' => 15, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_COMBAT_ARTIFACT, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_COMBAT_ARTIFACT, 'score' => 15, 'domain' => DomainsData::SHOOTING_AND_THROWING['title']],
                    ['id' => DisciplinesFixtures::ID_CARRIAGE, 'score' => 15, 'domain' => DomainsData::TRAVEL['title']],
                    ['id' => DisciplinesFixtures::ID_SPIEL, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_STAVES, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_JEWELRY, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_BOTANICAL, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_CAMOUFLAGE, 'score' => 15, 'domain' => DomainsData::STEALTH['title']],
                    ['id' => DisciplinesFixtures::ID_CARTOGRAPHY, 'score' => 15, 'domain' => DomainsData::TRAVEL['title']],
                    ['id' => DisciplinesFixtures::ID_SINGING, 'score' => 15, 'domain' => DomainsData::PERFORMANCE['title']],
                    ['id' => DisciplinesFixtures::ID_CHARM, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_VARIGAL_CROSSROADS, 'score' => 15, 'domain' => DomainsData::TRAVEL['title']],
                    ['id' => DisciplinesFixtures::ID_BARE_HANDS_FIGHT, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_BLIND_COMBAT, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_COMEDY, 'score' => 15, 'domain' => DomainsData::PERFORMANCE['title']],
                    ['id' => DisciplinesFixtures::ID_COMMANDMENT, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_CONCENTRATION, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_CONCENTRATION, 'score' => 15, 'domain' => DomainsData::PRAYER['title']],
                    ['id' => DisciplinesFixtures::ID_CONFECTION, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_KNOWLEDGE_OF_MENTAL_DISORDERS, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_KNOWLEDGE_OF_AFACTION, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_KNOWLEDGE_FLUX, 'score' => 15, 'domain' => DomainsData::MAGIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_KNOWLEDGE_OF_THE_TEMPLE, 'score' => 15, 'domain' => DomainsData::PRAYER['title']],
                    ['id' => DisciplinesFixtures::ID_RUNNING, 'score' => 15, 'domain' => DomainsData::FEATS['title']],
                    ['id' => DisciplinesFixtures::ID_COOKING, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_DANCE, 'score' => 15, 'domain' => DomainsData::PERFORMANCE['title']],
                    ['id' => DisciplinesFixtures::ID_DIPLOMACY, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_DISTILLATION, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_DOCTRINE_OF_THE_TEMPLE, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_ANIMAL_TRAINING, 'score' => 15, 'domain' => DomainsData::NATURAL_ENVIRONMENT['title']],
                    ['id' => DisciplinesFixtures::ID_ENDURANCE, 'score' => 15, 'domain' => DomainsData::FEATS['title']],
                    ['id' => DisciplinesFixtures::ID_SWORDS, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_HORSE_RIDING, 'score' => 15, 'domain' => DomainsData::TRAVEL['title']],
                    ['id' => DisciplinesFixtures::ID_CLIMBING, 'score' => 15, 'domain' => DomainsData::FEATS['title']],
                    ['id' => DisciplinesFixtures::ID_ESOTERICISM, 'score' => 15, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_ETIQUETTE, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_EVALUATION, 'score' => 15, 'domain' => DomainsData::PERCEPTION['title']],
                    ['id' => DisciplinesFixtures::ID_EVASION, 'score' => 15, 'domain' => DomainsData::FEATS['title']],
                    ['id' => DisciplinesFixtures::ID_FLUX_EXTRACTION, 'score' => 15, 'domain' => DomainsData::MAGIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_MINERAL_EXTRACTION, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_FAUNA_AND_FLORA, 'score' => 15, 'domain' => DomainsData::NATURAL_ENVIRONMENT['title']],
                    ['id' => DisciplinesFixtures::ID_WROUGHT, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_STEALTH, 'score' => 15, 'domain' => DomainsData::STEALTH['title']],
                    ['id' => DisciplinesFixtures::ID_GEOGRAPHY, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_GEOLOGY, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_AXES, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_HERALDRY, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_HERBALISM, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_HERBALISM, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_HISTORY, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_HYPNOSIS, 'score' => 15, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_ENGINEERING, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_MUSIC_INSTRUMENT, 'score' => 15, 'domain' => DomainsData::PERFORMANCE['title']],
                    ['id' => DisciplinesFixtures::ID_DREAMS_INTERPRETATION, 'score' => 15, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_INTIMIDATION, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_GAMES, 'score' => 15, 'domain' => DomainsData::PERFORMANCE['title']],
                    ['id' => DisciplinesFixtures::ID_JUGGLING, 'score' => 15, 'domain' => DomainsData::PERFORMANCE['title']],
                    ['id' => DisciplinesFixtures::ID_SHORT_BLADES, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_OLD_LANGUAGE, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_LANGUAGES, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_LIP_READING, 'score' => 15, 'domain' => DomainsData::PERCEPTION['title']],
                    ['id' => DisciplinesFixtures::ID_MAGIENTIST_MACHINERY, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_MAGIENTIST_MACHINERY, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_LEATHER, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_MECHANICAL, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_MEDICINE, 'score' => 15, 'domain' => DomainsData::MAGIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_MEDICINE, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_TRADITIONAL_MEDICINE, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_MEDITATION, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_CARPENTRY, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_MIMICRY, 'score' => 15, 'domain' => DomainsData::STEALTH['title']],
                    ['id' => DisciplinesFixtures::ID_MIRACLES, 'score' => 15, 'domain' => DomainsData::PRAYER['title']],
                    ['id' => DisciplinesFixtures::ID_SWIMMING, 'score' => 15, 'domain' => DomainsData::FEATS['title']],
                    ['id' => DisciplinesFixtures::ID_NAVIGATION, 'score' => 15, 'domain' => DomainsData::TRAVEL['title']],
                    ['id' => DisciplinesFixtures::ID_OBSERVATION, 'score' => 15, 'domain' => DomainsData::PERCEPTION['title']],
                    ['id' => DisciplinesFixtures::ID_ORIENTATION, 'score' => 15, 'domain' => DomainsData::PERCEPTION['title']],
                    ['id' => DisciplinesFixtures::ID_ORIENTATION, 'score' => 15, 'domain' => DomainsData::NATURAL_ENVIRONMENT['title']],
                    ['id' => DisciplinesFixtures::ID_ORIENTATION, 'score' => 15, 'domain' => DomainsData::TRAVEL['title']],
                    ['id' => DisciplinesFixtures::ID_MAGIENTIST_TOOL, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_MAGIENTIST_TOOL, 'score' => 15, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_MAGIENTIST_TOOL, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_PAINTING, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_PERSUASION, 'score' => 15, 'domain' => DomainsData::RELATION['title']],
                    ['id' => DisciplinesFixtures::ID_MENTAL_PHENOMENA, 'score' => 15, 'domain' => DomainsData::OCCULTISM['title']],
                    ['id' => DisciplinesFixtures::ID_TRACKING, 'score' => 15, 'domain' => DomainsData::NATURAL_ENVIRONMENT['title']],
                    ['id' => DisciplinesFixtures::ID_POLICY, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_POTTERY, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_FIRST_AID, 'score' => 15, 'domain' => DomainsData::NATURAL_ENVIRONMENT['title']],
                    ['id' => DisciplinesFixtures::ID_MAGIENTIST_PRINCIPLES, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_FLUX_REFINING, 'score' => 15, 'domain' => DomainsData::MAGIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_CONTEMPLATION, 'score' => 15, 'domain' => DomainsData::PRAYER['title']],
                    ['id' => DisciplinesFixtures::ID_REPAIRING_ARTIFACTS, 'score' => 15, 'domain' => DomainsData::MAGIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_REPAIRING_ARTIFACTS, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_DEMORTHEN_KNOWLEDGE, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_SCULPTURE, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_SHARPENED_SENSES, 'score' => 15, 'domain' => DomainsData::PERCEPTION['title']],
                    ['id' => DisciplinesFixtures::ID_LOCKSMITH, 'score' => 15, 'domain' => DomainsData::CRAFT['title']],
                    ['id' => DisciplinesFixtures::ID_SIGIL_RANN, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_VARIGAL_SIGNS, 'score' => 15, 'domain' => DomainsData::TRAVEL['title']],
                    ['id' => DisciplinesFixtures::ID_SPIRITUALITY, 'score' => 15, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                    ['id' => DisciplinesFixtures::ID_SPIRITUALITY, 'score' => 15, 'domain' => DomainsData::PRAYER['title']],
                    ['id' => DisciplinesFixtures::ID_SPIRITUALITY, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_SURVIVAL, 'score' => 15, 'domain' => DomainsData::NATURAL_ENVIRONMENT['title']],
                    ['id' => DisciplinesFixtures::ID_TRADITIONS_DEMORTHEN, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_USING_ARTIFACTS, 'score' => 15, 'domain' => DomainsData::MAGIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_VENTRILOQUISM, 'score' => 15, 'domain' => DomainsData::PERFORMANCE['title']],
                    ['id' => DisciplinesFixtures::ID_VIGILANCE, 'score' => 15, 'domain' => DomainsData::PERCEPTION['title']],
                    ['id' => DisciplinesFixtures::ID_THEFT, 'score' => 15, 'domain' => DomainsData::STEALTH['title']],
                    ['id' => DisciplinesFixtures::ID_ZOOLOGY, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                    ['id' => DisciplinesFixtures::ID_HASTE_WEAPONS, 'score' => 15, 'domain' => DomainsData::CLOSE_COMBAT['title']],
                    ['id' => DisciplinesFixtures::ID_ASTRONOMY, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_LEGENDS, 'score' => 15, 'domain' => DomainsData::ERUDITION['title']],
                    ['id' => DisciplinesFixtures::ID_WORKFORCE, 'score' => 15, 'domain' => DomainsData::FEATS['title']],
                    ['id' => DisciplinesFixtures::ID_MIND_TREATMENT, 'score' => 15, 'domain' => DomainsData::SCIENCE['title']],
                ]),
                'flux' => [],
                'setbacks' => static function (Character $character, ArrayFixture $fixture) {
                    $r = static function ($id, bool $avoided = false) use ($character, $fixture) {
                        /** @var Setback $ref */
                        $ref = $fixture->getReference('corahnrin-setbacks-'.$id);

                        $dto = SetbackDTO::create($ref, $avoided);

                        return CharSetbacks::createFromSessionDTO($character, $dto);
                    };

                    return [
                        $r(SetbacksFixtures::ID_UNLUCKY),
                        $r(SetbacksFixtures::ID_AFTEREFFECT),
                        $r(SetbacksFixtures::ID_ADVERSARY),
                        $r(SetbacksFixtures::ID_RUMOR),
                        $r(SetbacksFixtures::ID_TRAGIC_LOVE),
                        $r(SetbacksFixtures::ID_DISEASE),
                        $r(SetbacksFixtures::ID_VIOLENCE),
                        $r(SetbacksFixtures::ID_SOLITUDE, true),
                        $r(SetbacksFixtures::ID_POOR, true),
                        $r(SetbacksFixtures::ID_LUCKY),
                    ];
                },
                'user' => $this->getReference('user-pierstoval'),
                'game' => null,
            ],
            \array_merge($defaults, [
                'name' => 'Character inside a game',
                'nameSlug' => 'character-inside-a-game',
                'user' => $this->getReference('user-game-master'),
                'game' => $this->getReference('corahnrin-game-'.GamesFixtures::ID_WITH_CHARACTER),
            ]),
            \array_merge($defaults, [
                'name' => 'Invited character',
                'nameSlug' => 'invited-character',
                'experienceActual' => 500,
                'disciplines' => $this->createCharacterDisciplines([
                    ['id' => DisciplinesFixtures::ID_SPIEL, 'score' => 6, 'domain' => DomainsData::RELATION['title']],
                ]),
            ]),
            \array_merge($defaults, [
                'name' => 'Character to invite',
                'nameSlug' => 'character-to-invite',
            ]),
            \array_merge($defaults, [
                'name' => 'Character to spend ogham with',
                'nameSlug' => 'character-to-spend-ogham-with',
                'job' => $this->getReference('corahnrin-jobs-'.JobsFixtures::ID_DEMORTHEN),
                'user' => $this->getReference('user-lambda-user'),
                'experienceActual' => 500,
                'domains' => static function (Character $character) use ($defaults) {
                    /** @var CharacterDomains $domains */
                    $domains = $defaults['domains']($character);
                    $domains->setDomainValue(DomainsData::DEMORTHEN_MYSTERIES['title'], 5);

                    return $domains;
                },
                'disciplines' => $this->createCharacterDisciplines([
                    ['id' => DisciplinesFixtures::ID_SIGIL_RANN, 'score' => 6, 'domain' => DomainsData::DEMORTHEN_MYSTERIES['title']],
                ]),
            ]),
            \array_merge($defaults, [
                'name' => 'Character to spend miracles with',
                'nameSlug' => 'character-to-spend-miracles-with',
                'job' => $this->getReference('corahnrin-jobs-'.JobsFixtures::ID_BLADE_KNIGHT),
                'user' => $this->getReference('user-lambda-user'),
                'experienceActual' => 500,
                'domains' => static function (Character $character) use ($defaults) {
                    /** @var CharacterDomains $domains */
                    $domains = $defaults['domains']($character);
                    $domains->setDomainValue(DomainsData::PRAYER['title'], 5);

                    return $domains;
                },
                'disciplines' => $this->createCharacterDisciplines([
                    ['id' => DisciplinesFixtures::ID_MIRACLES, 'score' => 6, 'domain' => DomainsData::PRAYER['title']],
                ]),
            ]),
        ];
    }

    private function createCharacterDomains(Character $character, array $domains): CharacterDomains
    {
        $charDomains = CharacterDomains::createEmpty($character);

        foreach ($domains as $key => $value) {
            $charDomains->setDomainValue($key, $value);
        }

        return $charDomains;
    }

    /**
     * Must return a closure since the CharDisciplines object can only
     * be instantiated with an instance of a Character object.
     * Closure is automatically executed by the ArrayFixture ♥.
     */
    private function createCharacterDisciplines(array $disciplines): \Closure
    {
        return static function (Character $character, self $fixture) use ($disciplines) {
            $characterDisciplines = new ArrayCollection();

            foreach ($disciplines as $data) {
                $charDiscipline = CharDisciplines::create(
                    $character,
                    $fixture->getReference('corahnrin-discipline-'.$data['id']),
                    $data['domain'],
                    $data['score']
                );
                $characterDisciplines->add($charDiscipline);
            }

            return $characterDisciplines;
        };
    }

    private function getDefaults(): array
    {
        // Character name & nameSlug are not populated so you are forced to specify them.

        return [
            'playerName' => 'Some user',
            'sex' => 'character.sex.female',
            'description' => 'This character should be used to test being inside a game',
            'story' => '',
            'facts' => '',
            'inventory' => [],
            'treasures' => [],
            'money' => new Money(0, 0, 0),
            'orientation' => 'character.orientation.rational',
            'geoLiving' => $this->getReference('corahnrin-geo-environment-'.GeoEnvironmentsFixtures::ID_RURAL),
            'temporaryTrauma' => 0,
            'permanentTrauma' => 0,
            'hardening' => 0,
            'age' => 20,
            'mentalResistanceBonus' => 0,
            'ways' => Ways::create(1, 4, 3, 4, 3),
            'health' => new HealthCondition(),
            'maxHealth' => new HealthCondition(),
            'stamina' => 10,
            'staminaBonus' => 0,
            'survival' => 3,
            'speedBonus' => 0,
            'defenseBonus' => 0,
            'rindath' => 0,
            'rindathMax' => 0,
            'exaltation' => 0,
            'exaltationMax' => 0,
            'experienceActual' => 0,
            'experienceSpent' => 0,
            'people' => $this->getReference('corahnrin-people-'.PeoplesFixtures::ID_TRI_KAZEL),
            'armors' => [],
            'artifacts' => [],
            'miracles' => [],
            'ogham' => [],
            'weapons' => [],
            'combatArts' => [],
            'socialClass' => $this->getReference('corahnrin-social-class-'.SocialClassesFixtures::ID_NOBLETY),
            'socialClassDomain1' => DomainsData::SCIENCE['title'],
            'socialClassDomain2' => DomainsData::ERUDITION['title'],
            'ostService' => DomainsData::CLOSE_COMBAT['title'],
            'mentalDisorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_HALLUCINATION),
            'job' => $this->getReference('corahnrin-jobs-'.JobsFixtures::ID_OCCULTIST),
            'birthPlace' => ZoneId::fromInt(self::ZONE_ID_DEFAULT),
            'flaw' => $this->getReference('corahnrin-traits-'.TraitsFixtures::ID_WEAK_CHARACTER),
            'quality' => $this->getReference('corahnrin-traits-'.TraitsFixtures::ID_THOUGHTFUL),
            'advantages' => [],
            'domains' => function (Character $character) {
                return $this->createCharacterDomains($character, [
                    DomainsData::CRAFT['title'] => 3,
                    DomainsData::CLOSE_COMBAT['title'] => 1,
                    DomainsData::STEALTH['title'] => 3,
                    DomainsData::MAGIENCE['title'] => 0,
                    DomainsData::NATURAL_ENVIRONMENT['title'] => 1,
                    DomainsData::DEMORTHEN_MYSTERIES['title'] => 0,
                    DomainsData::OCCULTISM['title'] => 5,
                    DomainsData::PERCEPTION['title'] => 5,
                    DomainsData::PRAYER['title'] => 0,
                    DomainsData::FEATS['title'] => 2,
                    DomainsData::RELATION['title'] => 5,
                    DomainsData::PERFORMANCE['title'] => 3,
                    DomainsData::SCIENCE['title'] => 5,
                    DomainsData::SHOOTING_AND_THROWING['title'] => 0,
                    DomainsData::TRAVEL['title'] => 3,
                    DomainsData::ERUDITION['title'] => 5,
                ]);
            },
            'disciplines' => [],
            'flux' => [],
            'setbacks' => [],
            'user' => $this->getReference('user-lambda-user'),
            'game' => null,
        ];
    }
}
