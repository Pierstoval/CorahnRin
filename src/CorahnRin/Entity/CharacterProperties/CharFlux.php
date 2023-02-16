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
use CorahnRin\Entity\Flux;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharFlux.
 *
 * @ORM\Table(name="characters_flux")
 *
 * @ORM\Entity
 */
class CharFlux
{
    /**
     * @var Character
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Character", inversedBy="flux")
     *
     * @Assert\NotNull
     */
    protected $character;

    /**
     * @var Flux
     *
     * @ORM\Column(type="integer")
     *
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Flux")
     *
     * @Assert\NotNull
     */
    protected $flux;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     *
     * @Assert\NotNull
     *
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
