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

use CorahnRin\Data\Ways as WaysData;
use CorahnRin\DTO\WaysDTO;
use CorahnRin\Exception\InvalidWay;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 * 
 */
class Ways
{
    /**
     * @var int
     *
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * 
     */
    private int $id;

    /**
     * @var int
     *
     * @ODM\Field(name="combativeness", type="integer")
     */
    private int $combativeness;

    /**
     * @var int
     *
     * @ODM\Field(name="creativity", type="integer")
     */
    private int $creativity;

    /**
     * @var int
     *
     * @ODM\Field(name="empathy", type="integer")
     */
    private int $empathy;

    /**
     * @var int
     *
     * @ODM\Field(name="reason", type="integer")
     */
    private int $reason;

    /**
     * @var int
     *
     * @ODM\Field(name="conviction", type="integer")
     */
    private int $conviction;

    private function __construct()
    {
    }

    public static function create(
        int $combativeness,
        int $creativity,
        int $empathy,
        int $reason,
        int $conviction
    ): self {
        $self = new self();

        $self->combativeness = $combativeness;
        $self->creativity = $creativity;
        $self->empathy = $empathy;
        $self->reason = $reason;
        $self->conviction = $conviction;

        return $self;
    }

    public static function createFromSession(WaysDTO $dto): self
    {
        return self::create(
            $dto->getCombativeness(),
            $dto->getCreativity(),
            $dto->getEmpathy(),
            $dto->getReason(),
            $dto->getConviction()
        );
    }

    public function getWay(string $way): int
    {
        WaysData::validateWay($way);

        return match ($way) {
            WaysData::COMBATIVENESS => $this->combativeness,
            WaysData::CREATIVITY => $this->creativity,
            WaysData::EMPATHY => $this->empathy,
            WaysData::REASON => $this->reason,
            WaysData::CONVICTION => $this->conviction,
            default => throw new InvalidWay($way),
        };
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCombativeness(): int
    {
        return $this->combativeness;
    }

    public function getCreativity(): int
    {
        return $this->creativity;
    }

    public function getEmpathy(): int
    {
        return $this->empathy;
    }

    public function getReason(): int
    {
        return $this->reason;
    }

    public function getConviction(): int
    {
        return $this->conviction;
    }
}
