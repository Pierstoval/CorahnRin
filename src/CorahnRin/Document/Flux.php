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

namespace CorahnRin\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Flux.
 *
 * 
 * @ODM\Document
 */
class Flux
{
    /**
     * @var int
     *
     * @ODM\Field(name="id", type="int", nullable=false)
     * @ODM\Id(type="int", strategy="INCREMENT")
     * 
     */
    private $id;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=true)
     */
    private $description;

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
