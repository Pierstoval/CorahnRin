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

class CharacterEditDTO
{
    /**
     * @var UpdateDetailsDTO
     *
     * @Assert\Valid
     */
    public $descriptionAndFacts;

    /**
     * @var UpdateInventoryDTO
     *
     * @Assert\Valid
     */
    public $inventory;

    public static function fromCharacter(Character $character): self
    {
        $self = new self();

        $self->descriptionAndFacts = UpdateDetailsDTO::fromCharacter($character);
        $self->inventory = UpdateInventoryDTO::fromCharacter($character);

        return $self;
    }
}
