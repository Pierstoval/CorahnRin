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

namespace CorahnRin\Entity\CharacterProperties;

use CorahnRin\Data\Character\DomainScore;
use CorahnRin\Data\DomainsData;
use CorahnRin\DTO\Session\SessionCharacterDomainsDTO;
use CorahnRin\DTO\SpendXp\DomainsSpendXpDTO;
use CorahnRin\Entity\Advantage;
use CorahnRin\Entity\Character;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 *
 * @ORM\Table(name="characters_domains")
 */
class CharacterDomains
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Character
     *
     * @ORM\OneToOne(targetEntity="CorahnRin\Entity\Character", mappedBy="domains")
     *
     * @ORM\JoinColumn(name="character_id", referencedColumnName="id", nullable=true, unique=true)
     */
    private $character;

    /**
     * @ORM\Column(name="craft", type="smallint")
     */
    private $craft = 0;

    /**
     * @ORM\Column(name="close_combat", type="smallint")
     */
    private $closeCombat = 0;

    /**
     * @ORM\Column(name="stealth", type="smallint")
     */
    private $stealth = 0;

    /**
     * @ORM\Column(name="magience", type="smallint")
     */
    private $magience = 0;

    /**
     * @ORM\Column(name="natural_environment", type="smallint")
     */
    private $naturalEnvironment = 0;

    /**
     * @ORM\Column(name="demorthen_mysteries", type="smallint")
     */
    private $demorthenMysteries = 0;

    /**
     * @ORM\Column(name="occultism", type="smallint")
     */
    private $occultism = 0;

    /**
     * @ORM\Column(name="perception", type="smallint")
     */
    private $perception = 0;

    /**
     * @ORM\Column(name="prayer", type="smallint")
     */
    private $prayer = 0;

    /**
     * @ORM\Column(name="feats", type="smallint")
     */
    private $feats = 0;

    /**
     * @ORM\Column(name="relation", type="smallint")
     */
    private $relation = 0;

    /**
     * @ORM\Column(name="performance", type="smallint")
     */
    private $performance = 0;

    /**
     * @ORM\Column(name="science", type="smallint")
     */
    private $science = 0;

    /**
     * @ORM\Column(name="shooting_and_throwing", type="smallint")
     */
    private $shootingAndThrowing = 0;

    /**
     * @ORM\Column(name="travel", type="smallint")
     */
    private $travel = 0;

    /**
     * @ORM\Column(name="erudition", type="smallint")
     */
    private $erudition = 0;

    /**
     * Some Character advantages or disadvantages may give bonuses or maluses to certain domains.
     * This property must be calculated each time it's used.
     *
     * @var null|int[]
     */
    private $bonuses;

    private function __construct()
    {
    }

    public static function createEmpty(Character $character): self
    {
        $self = new self();

        $self->character = $character;

        return $self;
    }

    public static function createFromSession(Character $character, SessionCharacterDomainsDTO $getDomains): self
    {
        $self = self::createEmpty($character);

        $self->craft = $getDomains->getCraft();
        $self->closeCombat = $getDomains->getCloseCombat();
        $self->stealth = $getDomains->getStealth();
        $self->magience = $getDomains->getMagience();
        $self->naturalEnvironment = $getDomains->getNaturalEnvironment();
        $self->demorthenMysteries = $getDomains->getDemorthenMysteries();
        $self->occultism = $getDomains->getOccultism();
        $self->perception = $getDomains->getPerception();
        $self->prayer = $getDomains->getPrayer();
        $self->feats = $getDomains->getFeats();
        $self->relation = $getDomains->getRelation();
        $self->performance = $getDomains->getPerformance();
        $self->science = $getDomains->getScience();
        $self->shootingAndThrowing = $getDomains->getShootingAndThrowing();
        $self->travel = $getDomains->getTravel();
        $self->erudition = $getDomains->getErudition();

        return $self;
    }

    public function getDomainScore(string $domain): int
    {
        $propertyName = DomainsData::getShortName($domain);

        return $this->{$propertyName};
    }

    public function getDomainScoreWithBonus(string $domain): int
    {
        $value = $this->getDomainScore($domain);

        $this->calculateBonuses();

        $value += $this->bonuses[$domain];

        return $value;
    }

    public function setDomainValue(string $domain, int $value): void
    {
        $this->setDomainPropertyValue($domain, $value);
    }

    /**
     * Keys are domain names, values are domain scores.
     *
     * @return DomainScore[]
     */
    public function getScores(): array
    {
        $this->calculateBonuses();

        $data = [];

        foreach (DomainsData::allAsObjects() as $domain) {
            $propertyName = $domain->getShortName();
            $domainName = $domain->getTitle();
            $data[$domainName] = new DomainScore(
                $domainName,
                $this->character->getWayScore($domain->getWay()),
                $this->{$propertyName},
                $this->bonuses[$domainName]
            );
        }

        return $data;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getCraft(): int
    {
        return $this->craft;
    }

    public function getCloseCombat(): int
    {
        return $this->closeCombat;
    }

    public function getStealth(): int
    {
        return $this->stealth;
    }

    public function getMagience(): int
    {
        return $this->magience;
    }

    public function getNaturalEnvironment(): int
    {
        return $this->naturalEnvironment;
    }

    public function getDemorthenMysteries(): int
    {
        return $this->demorthenMysteries;
    }

    public function getOccultism(): int
    {
        return $this->occultism;
    }

    public function getPerception(): int
    {
        return $this->perception;
    }

    public function getPrayer(): int
    {
        return $this->prayer;
    }

    public function getFeats(): int
    {
        return $this->feats;
    }

    public function getRelation(): int
    {
        return $this->relation;
    }

    public function getPerformance(): int
    {
        return $this->performance;
    }

    public function getScience(): int
    {
        return $this->science;
    }

    public function getShootingAndThrowing(): int
    {
        return $this->shootingAndThrowing;
    }

    public function getTravel(): int
    {
        return $this->travel;
    }

    public function getErudition(): int
    {
        return $this->erudition;
    }

    public function toArray(): array
    {
        return [
            'craft' => $this->craft,
            'closeCombat' => $this->closeCombat,
            'stealth' => $this->stealth,
            'magience' => $this->magience,
            'naturalEnvironment' => $this->naturalEnvironment,
            'demorthenMysteries' => $this->demorthenMysteries,
            'occultism' => $this->occultism,
            'perception' => $this->perception,
            'prayer' => $this->prayer,
            'feats' => $this->feats,
            'relation' => $this->relation,
            'performance' => $this->performance,
            'science' => $this->science,
            'shootingAndThrowing' => $this->shootingAndThrowing,
            'travel' => $this->travel,
            'erudition' => $this->erudition,
        ];
    }

    public function updateFromSpendingXp(DomainsSpendXpDTO $domains): void
    {
        foreach ($domains->toArray() as $domain => $score) {
            $this->{$domain} = $score;
        }
    }

    private function setDomainPropertyValue(string $domain, int $value): void
    {
        DomainsData::validateDomainBaseValue($domain, $value);

        $propertyName = DomainsData::getAsObject($domain)->getShortName();

        $this->{$propertyName} = $value;
    }

    private function calculateBonuses(): void
    {
        if (null !== $this->bonuses) {
            return;
        }

        $this->bonuses = [];

        foreach (DomainsData::ALL as $domain => $v) {
            $this->bonuses[$domain] = 0;
        }

        foreach ($this->character->getAllAdvantages() as $charAdvantage) {
            $advantage = $charAdvantage->getAdvantage();
            $factor = $advantage->isDisadvantage() ? -1 : 1;

            if (
                !$advantage->getRequiresIndication()
                || (
                    $advantage->getRequiresIndication()
                    && Advantage::INDICATION_TYPE_SINGLE_VALUE === $advantage->getIndicationType()
                )
            ) {
                foreach ($advantage->getBonusesFor() as $bonus) {
                    if (DomainsData::isDomainValid($bonus)) {
                        $this->bonuses[$bonus] += ($charAdvantage->getScore() * $factor);
                    }
                }

                continue;
            }

            if (
                $advantage->getRequiresIndication()
                && Advantage::INDICATION_TYPE_SINGLE_CHOICE === $advantage->getIndicationType()
                && DomainsData::isDomainValid($charAdvantage->getIndication())
            ) {
                $this->bonuses[$charAdvantage->getIndication()] += ($charAdvantage->getScore() * $factor);
            }
        }
    }
}
