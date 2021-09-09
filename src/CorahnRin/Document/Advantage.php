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

namespace CorahnRin\Document;

use CorahnRin\Data\DomainsData;
use CorahnRin\Document\CharacterProperties\Bonuses;
use CorahnRin\Document\Traits\HasBook;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * 
 * @ODM\Document(repositoryClass="CorahnRin\Repository\CharacterAdvantageRepository")
 */
class Advantage
{
    use HasBook;

    public const INDICATION_TYPE_SINGLE_VALUE = 'single_value';
    public const INDICATION_TYPE_SINGLE_CHOICE = 'single_choice';

    public const INDICATION_TYPES = [
        self::INDICATION_TYPE_SINGLE_VALUE,
        self::INDICATION_TYPE_SINGLE_CHOICE,
    ];

    public const POSSIBLE_BONUSES = [
        Bonuses::MONEY_100G,
        Bonuses::MONEY_50G,
        Bonuses::MONEY_20G,
        Bonuses::MONEY_10G,
        Bonuses::MONEY_50A,
        Bonuses::MONEY_20A,
        Bonuses::LUCK,
        Bonuses::MENTAL_RESISTANCE,
        Bonuses::HEALTH,
        Bonuses::STAMINA,
        Bonuses::TRAUMA,
        Bonuses::DEFENSE,
        Bonuses::SPEED,
        Bonuses::SURVIVAL,
        DomainsData::CRAFT['title'],
        DomainsData::CLOSE_COMBAT['title'],
        DomainsData::STEALTH['title'],
        DomainsData::MAGIENCE['title'],
        DomainsData::NATURAL_ENVIRONMENT['title'],
        DomainsData::DEMORTHEN_MYSTERIES['title'],
        DomainsData::OCCULTISM['title'],
        DomainsData::PERCEPTION['title'],
        DomainsData::PRAYER['title'],
        DomainsData::FEATS['title'],
        DomainsData::RELATION['title'],
        DomainsData::PERFORMANCE['title'],
        DomainsData::SCIENCE['title'],
        DomainsData::SHOOTING_AND_THROWING['title'],
        DomainsData::TRAVEL['title'],
        DomainsData::ERUDITION['title'],
    ];

    /**
     * @var int
     *
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * 
     */
    protected $id;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=false)
     */
    protected $nameFemale;

    /**
     * @var int
     *
     * @ODM\Field(type="smallint")
     */
    protected $xp;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ODM\Field(name="bonus_count", type="smallint", options={"default" = 0}, nullable=false)
     */
    protected $bonusCount;

    /**
     * @var string[]
     *
     * @ODM\Field(name="bonuses_for", type="simple_array", nullable=true)
     */
    protected $bonusesFor;

    /**
     * @var string
     *
     * @ODM\Field(name="requires_indication", type="string", nullable=true)
     */
    protected $requiresIndication;

    /**
     * If type is "choice", the list will be fetched from self::$bonusesFor.
     *
     * @var string
     *
     * @ODM\Field(name="indication_type", type="string", nullable=false, options={"default" = "single_value"})
     */
    protected $indicationType = self::INDICATION_TYPE_SINGLE_VALUE;

    /**
     * @var bool
     *
     * @ODM\Field(type="boolean")
     */
    protected $isDisadvantage;

    /**
     * @var string
     *
     * @ODM\Field(name="avtg_group", type="string", nullable=true)
     */
    protected $group = '';

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getNameFemale()
    {
        return $this->nameFemale;
    }

    public function getXp(): int
    {
        return $this->xp;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getBonusesFor(): array
    {
        return $this->bonusesFor;
    }

    public function getRequiresIndication(): ?string
    {
        return $this->requiresIndication;
    }

    public function getIndicationType(): string
    {
        return $this->indicationType;
    }

    public function isDisadvantage()
    {
        return $this->isDisadvantage;
    }

    public function getBonusCount(): int
    {
        return $this->bonusCount;
    }

    public function getGroup(): string
    {
        return (string) $this->group;
    }
}
