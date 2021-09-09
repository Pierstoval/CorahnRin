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
 * @ODM\Document(repositoryClass="CorahnRin\Repository\CombatArtsRepository")
 */
class CombatArt
{
    use HasBook;

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
     * @ODM\Field(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ODM\Field(name="description", type="string")
     */
    private $description;

    /**
     * @var bool
     *
     * @ODM\Field(name="ranged", type="boolean")
     */
    private $ranged;

    /**
     * @var bool
     *
     * @ODM\Field(name="melee", type="boolean")
     */
    private $melee;

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

    public function getRanged()
    {
        return $this->ranged;
    }

    public function getMelee()
    {
        return $this->melee;
    }
}
