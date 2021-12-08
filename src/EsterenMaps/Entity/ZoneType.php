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

namespace EsterenMaps\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class ZoneType
{
    private int $id;

    private string $name;

    private string $description;

    private string $color;

    private ?ZoneType $parent;

    /**
     * @var Collection|Zone[]
     */
    private array|Collection $zones;

    private array $children = [];

    public function __construct()
    {
        $this->zones = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Collection&iterable<Zone>
     */
    public function getZones(): array|Collection
    {
        return $this->zones;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
