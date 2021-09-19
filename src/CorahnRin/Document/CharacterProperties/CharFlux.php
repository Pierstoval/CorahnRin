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

use CorahnRin\Document\Flux;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ODM\EmbeddedDocument
 */
class CharFlux
{
    /**
     * @ODM\Field(type="int")
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Flux")
     * @Assert\NotNull
     */
    private Flux $flux;

    /**
     * @ODM\Field(type="int")
     * @Assert\NotNull
     * @Assert\GreaterThanOrEqual(value=0)
     */
    private int $quantity;

    public function getFlux(): Flux
    {
        return $this->flux;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
