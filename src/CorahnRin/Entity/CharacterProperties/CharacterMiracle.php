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

use CorahnRin\Entity\Character;
use CorahnRin\Entity\Miracle;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="characters_miracles")
 *
 * @ORM\Entity
 */
class CharacterMiracle
{
    /**
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Character", inversedBy="miracles")
     *
     * @ORM\JoinColumn(name="character_id", nullable=false)
     */
    private Character $character;

    /**
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Miracle")
     *
     * @ORM\JoinColumn(name="miracle_id", nullable=false)
     */
    private Miracle $miracle;

    /**
     * @ORM\Column(type="integer", name="is_major", type="boolean")
     */
    private bool $isMajor;

    private function __construct()
    {
    }

    public static function create(Character $character, Miracle $miracle, bool $isMajor): self
    {
        $self = new self();

        $self->character = $character;
        $self->miracle = $miracle;
        $self->isMajor = $isMajor;

        return $self;
    }

    public function getName(): string
    {
        return $this->miracle->getName();
    }

    public function getMiracleId(): int
    {
        return $this->miracle->getId();
    }

    public function isMajor(): bool
    {
        return $this->isMajor;
    }

    public function isMinor(): bool
    {
        return !$this->isMajor;
    }
}
