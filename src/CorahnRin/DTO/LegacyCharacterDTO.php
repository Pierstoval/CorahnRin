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

namespace CorahnRin\DTO;

use CorahnRin\Data\Orientation;
use CorahnRin\Entity\Armor;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\CharacterProperties\CharacterDomains;
use CorahnRin\Entity\CharacterProperties\HealthCondition;
use CorahnRin\Entity\CharacterProperties\Money;
use CorahnRin\Entity\CombatArt;
use CorahnRin\Entity\Discipline;
use CorahnRin\Entity\Game;
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

class LegacyCharacterDTO
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
     * @var Game
     */
    protected $game;

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
     * @var HealthCondition
     */
    protected $maxHealth;

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
    protected $speed;

    /**
     * @var int
     */
    protected $speedBonus = 0;

    /**
     * @var int
     */
    protected $defense;

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
     * @var CharacterDomains
     */
    protected $domains;

    /**
     * @var array|Discipline[]
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

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): void
    {
        $this->game = $game;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
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

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getWays(): WaysDTO
    {
        return $this->ways;
    }

    public function setWays(WaysDTO $ways): void
    {
        $this->ways = $ways;
    }
}
