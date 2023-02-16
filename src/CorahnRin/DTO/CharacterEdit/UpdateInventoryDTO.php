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

namespace CorahnRin\DTO\CharacterEdit;

use CorahnRin\Entity\Character;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateInventoryDTO
{
    /**
     * @var array|string[]
     *
     * @Assert\All({
     *
     *     @Assert\Type("string"),
     *
     *     @Assert\NotBlank
     * })
     */
    private $preciousObjects = [];

    /**
     * @var array|string[]
     *
     * @Assert\All({
     *
     *     @Assert\Type("string"),
     *
     *     @Assert\NotBlank
     * })
     */
    private $items = [];

    public static function fromCharacter(Character $character): self
    {
        $self = new self();

        $self->items = self::unicize($character->getInventory()) ?: [''];
        $self->preciousObjects = self::unicize($character->getTreasures()) ?: [''];

        return $self;
    }

    public function getPreciousObjects(): array
    {
        return $this->preciousObjects;
    }

    public function getUnicizedPreciousObjects(): array
    {
        return self::unicize($this->preciousObjects);
    }

    public function setPreciousObjects(array $preciousObjects): void
    {
        $this->preciousObjects = $preciousObjects;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getUnicizedItems(): array
    {
        return self::unicize($this->items);
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    private static function unicize(array $elements): array
    {
        return \array_unique(\array_filter(\array_map('trim', $elements)));
    }
}
