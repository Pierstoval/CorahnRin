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

namespace CorahnRin\DTO\SpendXp;

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\Character;
use Symfony\Component\Validator\Constraints as Assert;

class DomainsSpendXpDTO
{
    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.craft"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[craft]", groups={"base"})
     */
    public $craft = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.closeCombat"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[closeCombat]", groups={"base"})
     */
    public $closeCombat = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.stealth"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[stealth]", groups={"base"})
     */
    public $stealth = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.magience"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[magience]", groups={"base"})
     */
    public $magience = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.naturalEnvironment"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[naturalEnvironment]", groups={"base"})
     */
    public $naturalEnvironment = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.demorthenMysteries"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[demorthenMysteries]", groups={"base"})
     */
    public $demorthenMysteries = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.occultism"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[occultism]", groups={"base"})
     */
    public $occultism = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.perception"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[perception]", groups={"base"})
     */
    public $perception = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.prayer"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[prayer]", groups={"base"})
     */
    public $prayer = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.feats"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[feats]", groups={"base"})
     */
    public $feats = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.relation"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[relation]", groups={"base"})
     */
    public $relation = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.performance"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[performance]", groups={"base"})
     */
    public $performance = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.science"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[science]", groups={"base"})
     */
    public $science = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.shootingAndThrowing"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[shootingAndThrowing]", groups={"base"})
     */
    public $shootingAndThrowing = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.travel"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[travel]", groups={"base"})
     */
    public $travel = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.erudition"
     * )
     * @Assert\NotNull(groups={"base"})
     * @Assert\GreaterThanOrEqual(propertyPath="baseDomainsData[erudition]", groups={"base"})
     */
    public $erudition = 0;

    /**
     * @var int[]
     */
    private $baseDomainsData = [];

    public static function fromCharacter(Character $character): self
    {
        $object = new self();

        $object->baseDomainsData = $character->getDomains()->toArray();

        foreach (DomainsData::ALL as $title => $domain) {
            $object->{$domain['short_name']} = $object->baseDomainsData[$domain['short_name']];
        }

        return $object;
    }

    /**
     * @return int[]
     */
    public function baseDomainsData(): array
    {
        return $this->baseDomainsData;
    }

    public function getSpentXp(): int
    {
        $spent = 0;

        $baseValues = $this->baseDomainsData();

        foreach ($this->toArray() as $domain => $score) {
            $spent += ($score - $baseValues[$domain]) * 10;
        }

        return $spent;
    }

    /**
     * @return int[]
     */
    public function toArray(): array
    {
        $data = [];

        foreach (DomainsData::ALL as $title => $domain) {
            $data[$domain['short_name']] = $this->{$domain['short_name']};
        }

        return $data;
    }
}
