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

namespace EsterenMaps\Model;

final class MapSize
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    private function __construct()
    {
    }

    public static function create(int $width, int $height): self
    {
        $self = new self();

        $self->width = $width;
        $self->height = $height;

        return $self;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
