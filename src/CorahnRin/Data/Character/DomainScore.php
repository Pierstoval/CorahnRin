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

namespace CorahnRin\Data\Character;

class DomainScore
{
    private $domain;

    private $wayScore;

    private $base;

    private $bonusOrMalus;

    public function __construct(
        string $domain,
        int $wayValue,
        int $base,
        int $bonusOrMalus
    ) {
        $this->domain = $domain;
        $this->wayScore = $wayValue;
        $this->base = $base;
        $this->bonusOrMalus = $bonusOrMalus;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getWayScore(): int
    {
        return $this->wayScore;
    }

    public function getBase(): int
    {
        return $this->base;
    }

    public function getBonusOrMalus(): int
    {
        return $this->bonusOrMalus;
    }

    public function getTotal(): int
    {
        return $this->wayScore + $this->base + $this->bonusOrMalus;
    }
}
