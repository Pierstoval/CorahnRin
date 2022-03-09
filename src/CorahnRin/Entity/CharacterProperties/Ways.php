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

use CorahnRin\Data\Ways as WaysData;
use CorahnRin\DTO\WaysDTO;
use CorahnRin\Exception\InvalidWay;

class Ways
{
    private int $combativeness;
    private int $creativity;
    private int $empathy;
    private int $reason;
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

    /**
     * @return array{
     *     "ways.combativeness": int,
     *     "ways.creativity": int,
     *     "ways.empathy": int,
     *     "ways.reason": int,
     *     "ways.conviction": int
     * }
     */
    public function toArray(): array
    {
        return [
            WaysData::COMBATIVENESS => $this->combativeness,
            WaysData::CREATIVITY => $this->creativity,
            WaysData::EMPATHY => $this->empathy,
            WaysData::REASON => $this->reason,
            WaysData::CONVICTION => $this->conviction,
        ];
    }
}
