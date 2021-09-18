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
 * @ODM\Document(repositoryClass="CorahnRin\Repository\TraitsRepository")
 * 
 */
class PersonalityTrait
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
     * @ODM\Field(type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=false)
     */
    private $nameFemale;

    /**
     * @var bool
     *
     * @ODM\Field(type="boolean")
     */
    private $isQuality;

    /**
     * @var bool
     *
     * @ODM\Field(name="is_major", type="boolean")
     */
    private $major;

    /**
     * @var string
     *
     * @ODM\Field(name="way", type="string")
     */
    private $way;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function getNameFemale(): string
    {
        return (string) $this->nameFemale;
    }

    public function isQuality()
    {
        return (bool) $this->isQuality;
    }

    public function isMajor()
    {
        return (bool) $this->major;
    }

    public function getWay(): string
    {
        return (string) $this->way;
    }
}
