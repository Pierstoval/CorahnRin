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

use CorahnRin\Document\Character;
use CorahnRin\Document\Flux;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\Document
 */
class CharFlux
{
    /**
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     */
    private int $id;

    /**
     * @var Character
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Character", inversedBy="flux")
     * @Assert\NotNull
     */
    private Character $character;

    /**
     * @var Flux
     *
     * @ODM\Field(type="integer")
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Flux")
     * @Assert\NotNull
     */
    private Flux $flux;

    /**
     * @var int
     *
     * @ODM\Field(type="integer")
     * @Assert\NotNull
     * @Assert\GreaterThanOrEqual(value=0)
     */
    private int $quantity;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFlux(): Flux
    {
        return $this->flux;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }
}
