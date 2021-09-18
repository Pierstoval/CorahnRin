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

namespace CorahnRin\Document\CharacterProperties;

use CorahnRin\Exception\InvalidWoundChangeValue;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class HealthCondition
{
    /**
     * @var int
     *
     * @ODM\Field(name="good", type="integer")
     */
    private $good;

    /**
     * @var int
     *
     * @ODM\Field(name="okay", type="integer")
     */
    private $okay;

    /**
     * @var int
     *
     * @ODM\Field(name="bad", type="integer")
     */
    private $bad;

    /**
     * @var int
     *
     * @ODM\Field(name="critical", type="integer")
     */
    private $critical;

    /**
     * @var int
     *
     * @ODM\Field(name="agony", type="integer")
     */
    private $agony;

    public function __construct(int $good = 5, int $okay = 5, int $bad = 4, int $critical = 4, int $agony = 1)
    {
        $this->good = $good;
        $this->okay = $okay;
        $this->bad = $bad;
        $this->critical = $critical;
        $this->agony = $agony;
    }

    public function withdrawWounds(int $wounds): void
    {
        if ($wounds < 0) {
            throw new InvalidWoundChangeValue(false);
        }

        // TODO
    }

    public function healWounds(int $wounds): void
    {
        if ($wounds < 0) {
            throw new InvalidWoundChangeValue(true);
        }

        // TODO
    }

    public function getSum(): int
    {
        return $this->good + $this->okay + $this->bad + $this->critical + ((int) $this->agony);
    }

    public function getGood(): int
    {
        return $this->good;
    }

    public function getOkay(): int
    {
        return $this->okay;
    }

    public function getBad(): int
    {
        return $this->bad;
    }

    public function getCritical(): int
    {
        return $this->critical;
    }

    public function getAgony(): int
    {
        return $this->agony;
    }
}
