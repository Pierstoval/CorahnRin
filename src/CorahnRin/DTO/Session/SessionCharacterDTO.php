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

namespace CorahnRin\DTO\Session;

use CorahnRin\Data\DomainsData;
use CorahnRin\Data\Orientation;
use CorahnRin\DTO\AdvantageDTO;
use CorahnRin\DTO\SetbackDTO;
use CorahnRin\DTO\WaysDTO;
use CorahnRin\Entity\Armor;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Entity\CombatArt;
use CorahnRin\Entity\GeoEnvironment;
use CorahnRin\Entity\Job;
use CorahnRin\Entity\MagienceArtifact;
use CorahnRin\Entity\MentalDisorder;
use CorahnRin\Entity\People;
use CorahnRin\Entity\PersonalityTrait;
use CorahnRin\Entity\SocialClass;
use CorahnRin\Entity\Weapon;
use EsterenMaps\Id\ZoneId;
use User\Entity\User;

class SessionCharacterDTO
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $playerName;

    /**
     * @var string
     */
    protected $sex;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $story;

    /**
     * @var string
     */
    protected $facts;

    /**
     * @var array
     */
    protected $inventory;

    /**
     * @var Money
     */
    protected $money;

    /**
     * @var string
     */
    protected $orientation;

    /**
     * @var GeoEnvironment
     */
    protected $geoLiving;

    /**
     * @var int
     */
    protected $permanentTrauma = 0;

    /**
     * @var int
     */
    protected $age;

    /**
     * @var int
     */
    protected $mentalResistanceBonus = 0;

    /**
     * @var WaysDTO
     */
    protected $ways;

    /**
     * @var HealthCondition
     */
    protected $healthCondition;

    /**
     * @var int
     */
    protected $staminaBonus = 0;

    /**
     * @var int
     */
    protected $survival = 3;

    /**
     * @var int
     */
    protected $speedBonus = 0;

    /**
     * @var int
     */
    protected $defenseBonus = 0;

    /**
     * @var int
     */
    protected $rindathMax;

    /**
     * @var int
     */
    protected $exaltationMax;

    /**
     * @var int
     */
    protected $experienceActual;

    /**
     * @var People
     */
    protected $people;

    /**
     * @var Armor[]|array
     */
    protected $armors = [];

    /**
     * @var array|MagienceArtifact[]
     */
    protected $artifacts = [];

    /**
     * @var array|Weapon[]
     */
    protected $weapons = [];

    /**
     * @var array|CombatArt[]
     */
    protected $combatArts = [];

    /**
     * @var SocialClass
     */
    protected $socialClass;

    /**
     * @var string
     */
    protected $socialClassDomain1;

    /**
     * @var string
     */
    protected $socialClassDomain2;

    /**
     * @var string
     */
    protected $ostService;

    /**
     * @var MentalDisorder
     */
    protected $mentalDisorder;

    /**
     * @var Job
     */
    protected $job;

    /**
     * @var ZoneId
     */
    protected $birthPlace;

    /**
     * @var PersonalityTrait
     */
    protected $flaw;

    /**
     * @var PersonalityTrait
     */
    protected $quality;

    /**
     * @var AdvantageDTO[]|array
     */
    protected $advantages = [];

    /**
     * @var SessionCharacterDomainsDTO
     */
    protected $domains;

    /**
     * @var array|SessionDisciplineDTO[]
     */
    protected $disciplines = [];

    /**
     * @var array|SetbackDTO[]
     */
    protected $setbacks = [];

    /**
     * @var User
     */
    protected $user;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = \trim(\strip_tags($name));
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function setPlayerName(string $playerName): void
    {
        $this->playerName = \trim(\strip_tags($playerName));
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function setSex(string $sex): void
    {
        if (Character::MALE !== $sex && Character::FEMALE !== $sex) {
            throw new \InvalidArgumentException(\sprintf(
                'Sex must be either "%s" or "%s", "%s" given.',
                Character::MALE,
                Character::FEMALE,
                $sex
            ));
        }

        $this->sex = $sex;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = \trim(\strip_tags($description));
    }

    public function getStory(): string
    {
        return $this->story;
    }

    public function setStory(string $story): void
    {
        $this->story = \trim(\strip_tags($story));
    }

    public function getFacts(): string
    {
        return $this->facts;
    }

    public function setFacts(string $facts): void
    {
        $this->facts = \trim(\strip_tags($facts));
    }

    public function getInventory(): array
    {
        return $this->inventory;
    }

    public function setInventory(array $inventory): void
    {
        foreach ($inventory as $k => $item) {
            $item = \trim(\strip_tags($item));
            if (!$item) {
                unset($inventory[$k]);

                continue;
            }

            if (!\is_string($item) || \is_numeric($item)) {
                throw new \InvalidArgumentException('Provided item must be a non-numeric string.');
            }
        }

        $this->inventory = $inventory;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function setMoney(Money $money): void
    {
        $this->money = $money;
    }

    public function getOrientation(): string
    {
        return $this->orientation;
    }

    public function setOrientation(string $orientation): void
    {
        if (!\array_key_exists($orientation, Orientation::ALL)) {
            throw new \InvalidArgumentException(\sprintf(
                'Orientation must be one value in "%s", "%s" given.',
                \implode('", "', \array_keys(Orientation::ALL)),
                $orientation
            ));
        }

        $this->orientation = $orientation;
    }

    public function getGeoLiving(): GeoEnvironment
    {
        return $this->geoLiving;
    }

    public function setGeoLiving(GeoEnvironment $geoLiving): void
    {
        $this->geoLiving = $geoLiving;
    }

    public function getPermanentTrauma(): int
    {
        return $this->permanentTrauma;
    }

    public function setPermanentTrauma(int $permanentTrauma): void
    {
        if ($permanentTrauma < 0) {
            throw new \InvalidArgumentException('Permanent trauma must be equal or superior to zero.');
        }

        $this->permanentTrauma = $permanentTrauma;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        if ($age < 1) {
            throw new \InvalidArgumentException('Age must be equal or superior to one.');
        }

        $this->age = $age;
    }

    public function getMentalResistanceBonus(): int
    {
        return $this->mentalResistanceBonus;
    }

    public function setMentalResistanceBonus(int $mentalResistanceBonus): void
    {
        if ($mentalResistanceBonus < 0) {
            throw new \InvalidArgumentException('Mental resistance bonus cannot be inferior to zero.');
        }

        $this->mentalResistanceBonus = $mentalResistanceBonus;
    }

    public function getWays(): WaysDTO
    {
        return $this->ways;
    }

    public function setWays(WaysDTO $ways): void
    {
        $this->ways = $ways;
    }

    public function getHealthCondition(): HealthCondition
    {
        return $this->healthCondition;
    }

    public function setHealthCondition(HealthCondition $healthCondition): void
    {
        $this->healthCondition = $healthCondition;
    }

    public function getStaminaBonus(): int
    {
        return $this->staminaBonus;
    }

    public function setStaminaBonus(int $staminaBonus): void
    {
        if ($staminaBonus < 0) {
            throw new \InvalidArgumentException('Stamina bonus cannot be inferior to zero.');
        }

        $this->staminaBonus = $staminaBonus;
    }

    public function getSurvival(): int
    {
        return $this->survival;
    }

    public function setSurvival(int $survival): void
    {
        if ($survival < 0 || $survival > 5) {
            throw new \InvalidArgumentException(\sprintf('Survival score must be a value between 0 and 5 at creation, %s given.', $survival));
        }

        $this->survival = $survival;
    }

    public function getSpeedBonus(): int
    {
        return $this->speedBonus;
    }

    public function setSpeedBonus(int $speedBonus): void
    {
        $this->speedBonus = $speedBonus;
    }

    public function getDefenseBonus(): int
    {
        return $this->defenseBonus;
    }

    public function setDefenseBonus(int $defenseBonus): void
    {
        $this->defenseBonus = $defenseBonus;
    }

    public function getRindathMax(): int
    {
        return $this->rindathMax;
    }

    public function setRindathMax(int $rindathMax): void
    {
        $this->rindathMax = $rindathMax;
    }

    public function getExaltationMax(): int
    {
        return $this->exaltationMax;
    }

    public function setExaltationMax(int $exaltationMax): void
    {
        $this->exaltationMax = $exaltationMax;
    }

    public function getExperienceActual(): int
    {
        return $this->experienceActual;
    }

    public function setExperienceActual(int $experienceActual): void
    {
        $this->experienceActual = $experienceActual;
    }

    public function getPeople(): People
    {
        return $this->people;
    }

    public function setPeople(People $people): void
    {
        $this->people = $people;
    }

    public function getArmors(): array
    {
        return $this->armors;
    }

    public function addArmor(Armor $armor): void
    {
        $this->armors[] = $armor;
    }

    public function getArtifacts(): array
    {
        return $this->artifacts;
    }

    public function addArtifact(MagienceArtifact $artifact): void
    {
        $this->artifacts[] = $artifact;
    }

    public function getWeapons(): array
    {
        return $this->weapons;
    }

    public function addWeapon(Weapon $weapon): void
    {
        $this->weapons[] = $weapon;
    }

    public function getCombatArts(): array
    {
        return $this->combatArts;
    }

    public function addCombatArt(CombatArt $combatArt): void
    {
        $this->combatArts[] = $combatArt;
    }

    public function setCombatArts($combatArts): void
    {
        $this->combatArts = $combatArts;
    }

    public function getSocialClass(): SocialClass
    {
        return $this->socialClass;
    }

    public function setSocialClass(SocialClass $socialClass): void
    {
        $this->socialClass = $socialClass;
    }

    public function getSocialClassDomain1(): string
    {
        return $this->socialClassDomain1;
    }

    public function setSocialClassDomain1(string $socialClassDomain1): void
    {
        DomainsData::validateDomain($socialClassDomain1);

        $this->socialClassDomain1 = $socialClassDomain1;
    }

    public function getSocialClassDomain2(): string
    {
        return $this->socialClassDomain2;
    }

    public function setSocialClassDomain2(string $socialClassDomain2): void
    {
        DomainsData::validateDomain($socialClassDomain2);

        $this->socialClassDomain2 = $socialClassDomain2;
    }

    public function getOstService(): string
    {
        return $this->ostService;
    }

    public function setOstService(string $ostService): void
    {
        DomainsData::validateDomain($ostService);

        $this->ostService = $ostService;
    }

    public function getMentalDisorder(): MentalDisorder
    {
        return $this->mentalDisorder;
    }

    public function setMentalDisorder(MentalDisorder $mentalDisorder): void
    {
        $this->mentalDisorder = $mentalDisorder;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function setJob(Job $job): void
    {
        $this->job = $job;
    }

    public function getBirthPlace(): ZoneId
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(ZoneId $birthPlace): void
    {
        $this->birthPlace = $birthPlace;
    }

    public function getFlaw(): PersonalityTrait
    {
        return $this->flaw;
    }

    public function setFlaw(PersonalityTrait $flaw): void
    {
        $this->flaw = $flaw;
    }

    public function getQuality(): PersonalityTrait
    {
        return $this->quality;
    }

    public function setQuality(PersonalityTrait $quality): void
    {
        $this->quality = $quality;
    }

    /**
     * @return AdvantageDTO[]
     */
    public function getAdvantages(): array
    {
        return $this->advantages;
    }

    public function addAdvantage(AdvantageDTO $advantage): void
    {
        $this->advantages[] = $advantage;
    }

    public function getDomains(): SessionCharacterDomainsDTO
    {
        return $this->domains;
    }

    public function setDomains(SessionCharacterDomainsDTO $domains): void
    {
        $this->domains = $domains;
    }

    /**
     * @return SessionDisciplineDTO[]
     */
    public function getDisciplines(): array
    {
        return $this->disciplines;
    }

    public function addDiscipline(SessionDisciplineDTO $discipline): void
    {
        $this->disciplines[] = $discipline;
    }

    /**
     * @return SetbackDTO[]
     */
    public function getSetbacks(): array
    {
        return $this->setbacks;
    }

    public function addSetback(SetbackDTO $sessionSetbackDTO): void
    {
        $this->setbacks[] = $sessionSetbackDTO;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}
