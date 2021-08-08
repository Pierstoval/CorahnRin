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

class SessionCharacterDomainsDTO
{
    private $craft = 0;
    private $closeCombat = 0;
    private $stealth = 0;
    private $magience = 0;
    private $naturalEnvironment = 0;
    private $demorthenMysteries = 0;
    private $occultism = 0;
    private $perception = 0;
    private $prayer = 0;
    private $feats = 0;
    private $relation = 0;
    private $performance = 0;
    private $science = 0;
    private $shootingAndThrowing = 0;
    private $travel = 0;
    private $erudition = 0;

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function setDomainValue(string $domain, int $value): void
    {
        DomainsData::validateDomainBaseValue($domain, $value);

        $propertyName = DomainsData::getAsObject($domain)->getShortName();

        $this->{$propertyName} = $value;
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
}
