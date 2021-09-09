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
 * CharFlux.
 *
 * 
 * @ODM\Document
 */
class CharFlux
{
    /**
     * @var Character
     *
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * @ORM\ManyToOne(targetEntity="CorahnRin\Document\Character", inversedBy="flux")
     * @Assert\NotNull
     */
    protected $character;

    /**
     * @var Flux
     *
     * @ODM\Field(type="integer")
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * @ORM\ManyToOne(targetEntity="CorahnRin\Document\Flux")
     * @Assert\NotNull
     */
    protected $flux;

    /**
     * @var int
     *
     * @ODM\Field(type="smallint")
     * @Assert\NotNull
     * @Assert\GreaterThanOrEqual(value=0)
     */
    protected $quantity;

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
