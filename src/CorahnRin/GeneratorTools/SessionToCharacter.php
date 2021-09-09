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

namespace CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainItem;
use CorahnRin\Data\DomainsData;
use CorahnRin\Data\Ways as WaysData;
use CorahnRin\DTO\AdvantageDTO;
use CorahnRin\DTO\Session\SessionCharacterDomainsDTO;
use CorahnRin\DTO\Session\SessionCharacterDTO;
use CorahnRin\DTO\Session\SessionDisciplineDTO;
use CorahnRin\DTO\SetbackDTO;
use CorahnRin\DTO\WaysDTO;
use CorahnRin\Document\Advantage;
use CorahnRin\Document\Armor;
use CorahnRin\Document\Character;
use CorahnRin\Document\CharacterProperties\Bonuses;
use CorahnRin\Document\CharacterProperties\HealthCondition;
use CorahnRin\Document\CharacterProperties\Money;
use CorahnRin\Document\CombatArt;
use CorahnRin\Document\Discipline;
use CorahnRin\Document\GeoEnvironment;
use CorahnRin\Document\Job;
use CorahnRin\Document\MentalDisorder;
use CorahnRin\Document\People;
use CorahnRin\Document\PersonalityTrait;
use CorahnRin\Document\Setback;
use CorahnRin\Document\SocialClass;
use CorahnRin\Document\Weapon;
use CorahnRin\Exception\CharacterException;
use CorahnRin\Exception\InvalidSessionToCharacterValue;
use CorahnRin\Repository\CharacterAdvantageRepository;
use CorahnRin\Repository\SetbacksRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\DocumentRepository;
use Doctrine\Persistence\ObjectRepository;
use EsterenMaps\Id\ZoneId;
use EsterenMaps\Repository\ZonesRepository;
use Pierstoval\Bundle\CharacterManagerBundle\Resolver\StepResolverInterface;
use User\Document\User;

final class SessionToCharacter
{
    private $resolver;
    private $em;
    private $domainsCalculator;
    private $corahnRinManagerName;

    /**
     * @var DomainItem[]
     */
    private $domains;

    /**
     * @var Setback[]
     */
    private $setbacks;

    /**
     * @var Advantage[]
     */
    private $advantages;

    /**
     * @var EntityRepository[]|ObjectRepository[]
     */
    private $repositories;

    private ZonesRepository $zonesRepository;

    public function __construct(
        StepResolverInterface $resolver,
        DomainsCalculator $domainsCalculator,
        ZonesRepository $zonesRepository,
        DocumentManager $em,
        string $corahnRinManagerName
    ) {
        $this->resolver = $resolver;
        $this->em = $em;
        $this->domainsCalculator = $domainsCalculator;
        $this->corahnRinManagerName = $corahnRinManagerName;
        $this->zonesRepository = $zonesRepository;
    }

    /**
     * @throws CharacterException
     */
    public function createCharacterFromGeneratorValues(array $values, ?User $connectedUser): Character
    {
        $steps = $this->resolver->getManagerSteps($this->corahnRinManagerName);

        $this->prepareNecessaryVariables();

        $generatorKeys = \array_keys($values);
        $stepsKeys = \array_keys($steps);

        \sort($generatorKeys);
        \sort($stepsKeys);

        if ($generatorKeys !== $stepsKeys) {
            throw new CharacterException('Generator seems not to be fully finished');
        }

        $characterDTO = new SessionCharacterDTO();

        $characterDTO->setName($values['19_description']['name']);
        $characterDTO->setUser($connectedUser);
        $this->setPeople($characterDTO, $values);
        $this->setJob($characterDTO, $values);
        $this->setBirthPlace($characterDTO, $values);
        $this->setGeoLiving($characterDTO, $values);
        $this->setSocialClass($characterDTO, $values);
        $this->setAge($characterDTO, $values);
        $this->setSetbacks($characterDTO, $values);
        $this->setWays($characterDTO, $values);
        $this->setTraits($characterDTO, $values);
        $this->setOrientation($characterDTO, $values);
        $this->setAdvantages($characterDTO, $values);
        $this->setMentalDisorder($characterDTO, $values);
        $this->setDisciplines($characterDTO, $values);
        $this->setCombatArts($characterDTO, $values);
        $this->setEquipment($characterDTO, $values);
        $this->setDescription($characterDTO, $values);
        $this->setExp($characterDTO, $values);
        $this->setMoney($characterDTO);
        $this->setDomains($characterDTO, $values);
        $this->setHealthCondition($characterDTO);
        $this->setPrecalculatedValues($characterDTO);

        return Character::createFromSession($characterDTO);
    }

    /**
     * @param string $class
     *
     * @return EntityRepository|ObjectRepository
     */
    private function getRepository($class)
    {
        if (isset($this->repositories[$class])) {
            return $this->repositories[$class];
        }

        return $this->repositories[$class] = $this->em->getRepository($class);
    }

    /**
     * Add some properties that will be used in other steps validators.
     * As a reminder, base repository is Orbitale's one, so using "_primary" will automatically index all objects by their id.
     */
    private function prepareNecessaryVariables(): void
    {
        /** @var SetbacksRepository $setbacksRepo */
        $setbacksRepo = $this->getRepository(Setback::class);
        $tmp = $setbacksRepo->findAll();
        $this->setbacks = [];
        foreach ($tmp as $item) {
            $this->setbacks[$item->getId()] = $item;
        }

        /** @var CharacterAdvantageRepository $advantagesRepo */
        $advantagesRepo = $this->getRepository(Advantage::class);
        $tmp = $advantagesRepo->findAll();
        $this->advantages = [];
        foreach ($tmp as $item) {
            $this->advantages[$item->getId()] = $item;
        }

        $this->domains = DomainsData::allAsObjects();
    }

    private function setPeople(SessionCharacterDTO $character, array $values): void
    {
        $people = $this->getRepository(People::class)->find($values['01_people']);

        if (!$people instanceof People) {
            throw new InvalidSessionToCharacterValue('people', $people, People::class);
        }

        $character->setPeople($people);
    }

    private function setJob(SessionCharacterDTO $character, array $values): void
    {
        $job = $this->getRepository(Job::class)->find($values['02_job']);

        if (!$job instanceof Job) {
            throw new InvalidSessionToCharacterValue('job', $job, Job::class);
        }

        $character->setJob($job);
    }

    private function setBirthPlace(SessionCharacterDTO $character, array $values): void
    {
        $zone = $this->zonesRepository->findIdByValue($values['03_birthplace']);

        if (!$zone instanceof ZoneId) {
            throw new InvalidSessionToCharacterValue('birthplace', $zone, ZoneId::class);
        }

        $character->setBirthPlace($zone);
    }

    private function setGeoLiving(SessionCharacterDTO $character, array $values): void
    {
        $geoEnv = $this->getRepository(GeoEnvironment::class)->find($values['04_geo']);

        if (!$geoEnv instanceof GeoEnvironment) {
            throw new InvalidSessionToCharacterValue('geo_environment', $geoEnv, GeoEnvironment::class);
        }

        $character->setGeoLiving($geoEnv);
    }

    private function setSocialClass(SessionCharacterDTO $character, array $values): void
    {
        $socialClass = $this->getRepository(SocialClass::class)->find($values['05_social_class']['id']);

        if (!$socialClass instanceof SocialClass) {
            throw new InvalidSessionToCharacterValue('social_class', $socialClass, SocialClass::class);
        }

        $character->setSocialClass($socialClass);

        $domains = $values['05_social_class']['domains'];
        $character->setSocialClassDomain1($domains[0]);
        $character->setSocialClassDomain2($domains[1]);
    }

    private function setAge(SessionCharacterDTO $character, array $values): void
    {
        $character->setAge($values['06_age']);
    }

    private function setSetbacks(SessionCharacterDTO $character, array $values): void
    {
        foreach ($values['07_setbacks'] as $id => $details) {
            $character->addSetback(SetbackDTO::create($this->setbacks[$id], $details['avoided']));
        }
    }

    private function setWays(SessionCharacterDTO $character, array $values): void
    {
        $character->setWays(WaysDTO::create(
            $values['08_ways'][WaysData::COMBATIVENESS],
            $values['08_ways'][WaysData::CREATIVITY],
            $values['08_ways'][WaysData::EMPATHY],
            $values['08_ways'][WaysData::REASON],
            $values['08_ways'][WaysData::CONVICTION]
        ));
    }

    private function setTraits(SessionCharacterDTO $character, array $values): void
    {
        $quality = $this->getRepository(PersonalityTrait::class)->find($values['09_traits']['quality']);

        if (!$quality instanceof PersonalityTrait) {
            throw new InvalidSessionToCharacterValue('quality', $quality, PersonalityTrait::class);
        }

        $flaw = $this->getRepository(PersonalityTrait::class)->find($values['09_traits']['flaw']);

        if (!$flaw instanceof PersonalityTrait) {
            throw new InvalidSessionToCharacterValue('flaw', $flaw, PersonalityTrait::class);
        }

        $character->setQuality($quality);
        $character->setFlaw($flaw);
    }

    private function setOrientation(SessionCharacterDTO $character, array $values): void
    {
        $character->setOrientation($values['10_orientation']);
    }

    private function setAdvantages(SessionCharacterDTO $character, array $values): void
    {
        foreach ($values['11_advantages']['advantages'] as $id => $value) {
            if (!$value) {
                continue;
            }
            $this->addAdvantageToCharacter(
                $character,
                $this->advantages[$id],
                $value,
                $values['11_advantages']['advantages_indications'][$id] ?? ''
            );
        }

        foreach ($values['11_advantages']['disadvantages'] as $id => $value) {
            if (!$value) {
                continue;
            }
            $this->addAdvantageToCharacter(
                $character,
                $this->advantages[$id],
                $value,
                $values['11_advantages']['advantages_indications'][$id] ?? ''
            );
        }
    }

    private function addAdvantageToCharacter(SessionCharacterDTO $character, Advantage $advantage, int $score, string $indication): void
    {
        $character->addAdvantage(AdvantageDTO::create($advantage, $score, $indication));
    }

    private function setMentalDisorder(SessionCharacterDTO $character, array $values): void
    {
        $mentalDisorder = $this->getRepository(MentalDisorder::class)->find($values['12_mental_disorder']);

        if (!$mentalDisorder instanceof MentalDisorder) {
            throw new InvalidSessionToCharacterValue('mental_disorder', $mentalDisorder, MentalDisorder::class);
        }

        $character->setMentalDisorder($mentalDisorder);
    }

    private function setDisciplines(SessionCharacterDTO $character, array $values): void
    {
        foreach ($values['16_disciplines']['disciplines'] as $domainId => $disciplines) {
            foreach ($disciplines as $id => $v) {
                $discipline = $this->getRepository(Discipline::class)->find($id);

                if (!$discipline instanceof Discipline) {
                    throw new InvalidSessionToCharacterValue('discipline', $discipline, Discipline::class);
                }

                $character->addDiscipline(SessionDisciplineDTO::createFromSession((string) $domainId, $discipline));
            }
        }
    }

    private function setCombatArts(SessionCharacterDTO $character, array $values): void
    {
        foreach ($values['17_combat_arts']['combatArts'] as $id => $v) {
            $combatArt = $this->getRepository(CombatArt::class)->find($id);

            if (!$combatArt instanceof CombatArt) {
                throw new InvalidSessionToCharacterValue('combat_art', $combatArt, CombatArt::class);
            }

            $character->addCombatArt($combatArt);
        }
    }

    private function setEquipment(SessionCharacterDTO $character, array $values): void
    {
        $character->setInventory($values['18_equipment']['equipment']);

        foreach ($values['18_equipment']['armors'] as $id => $value) {
            $armor = $this->getRepository(Armor::class)->find($id);

            if (!$armor instanceof Armor) {
                throw new InvalidSessionToCharacterValue('armor', $armor, Armor::class);
            }

            $character->addArmor($armor);
        }
        foreach ($values['18_equipment']['weapons'] as $id => $value) {
            $weapon = $this->getRepository(Weapon::class)->find($id);

            if (!$weapon instanceof Weapon) {
                throw new InvalidSessionToCharacterValue('weapon', $weapon, Weapon::class);
            }

            $character->addWeapon($weapon);
        }
    }

    private function setDescription(SessionCharacterDTO $character, array $values): void
    {
        $details = $values['19_description'];
        $character->setPlayerName(\trim($details['player_name']));
        $character->setSex($details['sex']);
        $character->setDescription(\trim($details['description']));
        $character->setStory(\trim($details['story']));
        $character->setFacts(\trim($details['facts']));
    }

    private function setExp(SessionCharacterDTO $character, array $values): void
    {
        $character->setExperienceActual((int) $values['17_combat_arts']['remainingExp']);
    }

    private function setMoney(SessionCharacterDTO $character): void
    {
        $money = new Money();

        $salary = $character->getJob()->getDailySalary();

        if ($salary > 0) {
            foreach ($character->getSetbacks() as $setback) {
                if (Bonuses::MONEY_0 === $setback->getSetback()->getMalus()) {
                    // Use salary only if job defines one AND character is not poor
                    $money->addEmber(30 * $salary);
                    $money->reallocate();

                    break;
                }
            }
        } else {
            // If salary is not set in job, character has 2d10 azure daols
            $azure = \random_int(1, 10) + \random_int(1, 10);
            foreach ($character->getSetbacks() as $setback) {
                if (Bonuses::MONEY_0 === $setback->getSetback()->getMalus()) {
                    // If character is poor, he has half money
                    $azure = (int) \floor($azure / 2);

                    break;
                }
            }
            $money->addAzure($azure);
            $money->reallocate();
        }

        $character->setMoney($money);
    }

    private function setDomains(SessionCharacterDTO $character, array $values): void
    {
        $domainsBaseValues = $this->domainsCalculator->calculateFromGeneratorData(
            $values['05_social_class']['domains'],
            $values['13_primary_domains']['ost'],
            $character->getGeoLiving(),
            $values['13_primary_domains']['domains'],
            $values['14_use_domain_bonuses']['domains']
        );

        $finalDomainsValues = $this->domainsCalculator->calculateFinalValues(
            $this->domains,
            $domainsBaseValues,
            \array_map('intval', $values['15_domains_spend_exp']['domains'])
        );

        $charDomain = SessionCharacterDomainsDTO::create();
        foreach ($this->domains as $domain) {
            $domainName = $domain->getTitle();
            $charDomain->setDomainValue($domainName, $finalDomainsValues[$domainName]);
        }

        $character->setDomains($charDomain);

        $character->setOstService($values['13_primary_domains']['ost']);
    }

    private function setHealthCondition(SessionCharacterDTO $character): void
    {
        $health = new HealthCondition();
        $good = $health->getGood();
        $okay = $health->getOkay();
        $bad = $health->getBad();
        $critical = $health->getCritical();

        foreach ($character->getAdvantages() as $charAdvantage) {
            $adv = $charAdvantage->getAdvantage();

            foreach ($adv->getBonusesFor() as $bonus) {
                if (isset($this->domains[$bonus])) {
                    continue;
                }

                $disadvantageRatio = $adv->isDisadvantage() ? -1 : 1;

                switch ($bonus) {
                    case Bonuses::MENTAL_RESISTANCE:
                        $character->setMentalResistanceBonus($character->getMentalResistanceBonus() + ($charAdvantage->getScore() * $disadvantageRatio));

                        break;

                    case Bonuses::HEALTH:
                        $score = $charAdvantage->getScore();
                        if ($score >= 1) {
                            $bad++;
                            $critical++;
                        }
                        if ($score >= 2) {
                            $critical++;
                        }
                        if ($score <= -1) {
                            $okay--;
                            $critical--;
                        }
                        if ($score <= -2) {
                            $critical--;
                        }

                        break;

                    case Bonuses::STAMINA:
                        $character->setStaminaBonus($character->getStaminaBonus() + ($charAdvantage->getScore() * $disadvantageRatio));

                        break;

                    case Bonuses::TRAUMA:
                        $character->setPermanentTrauma($character->getPermanentTrauma() + $charAdvantage->getScore());

                        break;

                    case Bonuses::DEFENSE:
                        $character->setDefenseBonus($character->getDefenseBonus() + ($charAdvantage->getScore() * $disadvantageRatio));

                        break;

                    case Bonuses::SPEED:
                        $character->setSpeedBonus($character->getSpeedBonus() + ($charAdvantage->getScore() * $disadvantageRatio));

                        break;

                    case Bonuses::SURVIVAL:
                        $character->setSurvival($character->getSurvival() + ($charAdvantage->getScore() * $disadvantageRatio));

                        break;

                    case Bonuses::MONEY_100G:
                        $character->getMoney()->addFrost(100);

                        break;

                    case Bonuses::MONEY_20G:
                        $character->getMoney()->addFrost(20);

                        break;

                    case Bonuses::MONEY_50G:
                        $character->getMoney()->addFrost(50);

                        break;

                    case Bonuses::MONEY_50A:
                        $character->getMoney()->addAzure(50);

                        break;

                    case Bonuses::MONEY_20A:
                        $character->getMoney()->addAzure(20);

                        break;

                    default:
                        throw new \RuntimeException("Invalid bonus {$bonus}");
                }
            }
        }

        $health = new HealthCondition($good, $okay, $bad, $critical);
        $character->setHealthCondition($health);
    }

    private function setPrecalculatedValues(SessionCharacterDTO $character): void
    {
        // Rindath
        $rindathMax =
            $character->getWays()->getCombativeness()
            + $character->getWays()->getCreativity()
            + $character->getWays()->getEmpathy()
        ;

        // FIXME: This uses the discipline by name, this should be removed for proper abstraction

        $sigilRann = false;
        foreach ($character->getDisciplines() as $discipline) {
            if ('Sigil Rann' === $discipline->getDiscipline()->getName()) {
                $sigilRann = true;

                break;
            }
        }
        if ($sigilRann) {
            // Default discipline can only be a score of 6.
            // Rindath is increased by sigil rann according to this formula:
            // Bonus = (Score - 5) * 5
            // With a score of 6, well, it's 1*5, so... 5.
            // That's all for me, thanks.
            // (same calculation for Miracles & Exaltation by the way)
            $rindathMax += 5;
        }
        $character->setRindathMax($rindathMax);

        // Exaltation
        $exaltationMax = $character->getWays()->getConviction() * 3;
        $miracles = false;
        foreach ($character->getDisciplines() as $discipline) {
            if ('Miracles' === $discipline->getDiscipline()->getName()) {
                $miracles = true;

                break;
            }
        }
        if ($miracles) {
            // See "sigil rann" formula a few lines above to know why it's 5.
            $exaltationMax += 5;
        }
        $character->setExaltationMax($exaltationMax);
    }
}
