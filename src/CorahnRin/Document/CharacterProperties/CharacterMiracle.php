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

use CorahnRin\Document\Miracle;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class CharacterMiracle
{
    /**
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Miracle")
     */
    private Miracle $miracle;

    /**
     * @ODM\Field(type="int", name="is_major", type="bool")
     */
    private bool $isMajor;

    private function __construct()
    {
    }

    public static function create(Miracle $miracle, bool $isMajor): self
    {
        $self = new self();

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
