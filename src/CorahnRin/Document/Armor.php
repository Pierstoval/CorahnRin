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

use CorahnRin\Document\Traits\HasBook;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * 
 * @ODM\Document(repositoryClass="CorahnRin\Repository\ArmorsRepository")
 */
class Armor
{
    use HasBook;

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

    /**
     * @var int
     *
     * @ODM\Field(type="int")
     */
    private $protection;

    /**
     * @var int
     *
     * @ODM\Field(type="int")
     */
    private $price;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    private $availability;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getProtection()
    {
        return $this->protection;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getAvailability()
    {
        return $this->availability;
    }
}
