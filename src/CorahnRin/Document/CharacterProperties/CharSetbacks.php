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
 * CharSetbacks.
 *
 * 
 * @ODM\Document
 */
class CharSetbacks
{
    /**
     * @var Character
     *
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * @ORM\ManyToOne(targetEntity="CorahnRin\Document\Character", inversedBy="setbacks")
     * @Assert\NotNull
     */
    protected $character;

    /**
     * @var Setback
     *
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * @ORM\ManyToOne(targetEntity="CorahnRin\Document\Setback")
     * @Assert\NotNull
     */
    protected $setback;

    /**
     * @var bool
     *
     * @ODM\Field(type="boolean")
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
