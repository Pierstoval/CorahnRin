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

namespace CorahnRin\Document\CharacterProperties;

use CorahnRin\DTO\SetbackDTO;
use CorahnRin\Document\Character;
use CorahnRin\Document\Setback;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document
 */
class CharSetbacks
{
    /**
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     */
    private int $id;

    /**
     * @var Character
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Character", inversedBy="setbacks")
     * @Assert\NotNull
     */
    private Character $character;

    /**
     * @var Setback
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Setback")
     * @Assert\NotNull
     */
    private Setback $setback;

    /**
     * @var bool
     *
     * @ODM\Field(type="boolean")
     */
    private bool $isAvoided = false;

    private function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
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
