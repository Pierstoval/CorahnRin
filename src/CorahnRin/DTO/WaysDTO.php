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

class WaysDTO
{
    protected $combativeness = 0;
    protected $creativity = 0;
    protected $empathy = 0;
    protected $reason = 0;
    protected $conviction = 0;

    private function __construct()
    {
    }

    public static function create(int $combativeness, int $creativity, int $empathy, int $reason, int $conviction): self
    {
        $self = new self();

        $self->combativeness = $combativeness;
        $self->creativity = $creativity;
        $self->empathy = $empathy;
        $self->reason = $reason;
        $self->conviction = $conviction;

        return $self;
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
