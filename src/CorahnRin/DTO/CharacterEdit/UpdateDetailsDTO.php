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

use CorahnRin\Document\Character;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateDetailsDTO
{
    /**
     * @var string
     *
     * @Assert\Type("string")
     */
    public $description = '';

    /**
     * @var string
     *
     * @Assert\Type("string")
     */
    public $story = '';

    /**
     * @var string
     *
     * @Assert\Type("string")
     */
    public $notableFacts = '';

    public static function fromCharacter(Character $character): self
    {
        $self = new self();

        $self->description = $character->getDescription() ?: '';
        $self->story = $character->getStory() ?: '';
        $self->notableFacts = $character->getFacts() ?: '';

        return $self;
    }
}
