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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Weapons.
 *
 * 
 * @ODM\Document(repositoryClass="CorahnRin\Repository\WeaponsRepository")
 */
class Weapon
{
    /**
     * @var int
     *
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * 
     */
    private $id;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=false)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ODM\Field(type="integer")
     */
    private $damage;

    /**
     * @var int
     *
     * @ODM\Field(type="integer")
     */
    private $price;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    private $availability;

    /**
     * @var bool
     *
     * @ODM\Field(type="boolean")
     */
    private $melee = true;

    /**
     * @var int
     *
     * @ODM\Field(name="weapon_range", type="integer")
     */
    private $range;

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

    public function getDamage()
    {
        return $this->damage;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getAvailability()
    {
        return $this->availability;
    }

    public function getMelee()
    {
        return $this->melee;
    }

    public function getRange()
    {
        return $this->range;
    }
}
