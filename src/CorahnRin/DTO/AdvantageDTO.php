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

use CorahnRin\Document\Advantage;

class AdvantageDTO
{
    /**
     * @var Advantage
     */
    private $advantage;

    /**
     * @var string
     */
    private $indication;

    /**
     * @var int
     */
    private $score;

    private function __construct()
    {
        // Disable public constructor
    }

    public static function create(Advantage $advantage, int $score, string $indication): self
    {
        $self = new self();

        $self->advantage = $advantage;
        $self->score = $score;
        $self->indication = $indication;

        return $self;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getAdvantage(): Advantage
    {
        return $this->advantage;
    }

    public function getIndication(): string
    {
        return $this->indication;
    }
}
