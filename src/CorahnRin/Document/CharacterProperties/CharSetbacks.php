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
 * @ODM\EmbeddedDocument
 */
class CharSetbacks
{
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
     * @ODM\Field(type="bool")
     */
    private bool $isAvoided = false;

    private function __construct()
    {
    }

    public static function createFromSessionDTO(SetbackDTO $setbackDTO): self
    {
        $self = new self();

        $self->setback = $setbackDTO->getSetback();
        $self->isAvoided = $setbackDTO->isAvoided();

        return $self;
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
