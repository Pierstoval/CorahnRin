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

use CorahnRin\Document\Setback;

class SetbackDTO
{
    /**
     * @var Setback
     */
    private $setback;

    private $avoided = false;

    private function __construct()
    {
        // Disable public constructor
    }

    public static function create(Setback $setback, bool $avoided): self
    {
        $self = new self();

        $self->setback = $setback;
        $self->avoided = $avoided;

        return $self;
    }

    public function getSetback(): Setback
    {
        return $this->setback;
    }

    public function isAvoided(): bool
    {
        return $this->avoided;
    }
}
