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
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * 
     */
    protected $id;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ODM\Field(type="smallint")
     */
    protected $protection;

    /**
     * @var int
     *
     * @ODM\Field(type="smallint")
     */
    protected $price;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    protected $availability;

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
