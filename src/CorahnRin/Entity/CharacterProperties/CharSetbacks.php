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

use CorahnRin\DTO\SetbackDTO;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\Setback;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharSetbacks.
 *
 * @ORM\Table(name="characters_setbacks")
 *
 * @ORM\Entity
 */
class CharSetbacks
{
    /**
     * @var Character
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Character", inversedBy="setbacks")
     *
     * @Assert\NotNull
     */
    protected $character;

    /**
     * @var Setback
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Setback")
     *
     * @Assert\NotNull
     */
    protected $setback;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isAvoided = false;

    private function __construct()
    {
    }

    public static function createFromSessionDTO(Character $character, SetbackDTO $setbackDTO): self
    {
        $self = new self();

        $self->character = $character;
        $self->setback = $setbackDTO->getSetback();
        $self->isAvoided = $setbackDTO->isAvoided();

        return $self;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getSetback(): Setback
    {
        return $this->setback;
    }

    public function isAvoided(): bool
    {
        return $this->isAvoided;
    }
}
