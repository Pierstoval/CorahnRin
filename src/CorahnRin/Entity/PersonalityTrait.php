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

namespace CorahnRin\Entity;

use CorahnRin\Entity\Traits\HasBook;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\TraitsRepository")
 *
 * @ORM\Table(name="traits")
 */
class PersonalityTrait
{
    use HasBook;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    protected $nameFemale;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $isQuality;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_major", type="boolean")
     */
    protected $major;

    /**
     * @var string
     *
     * @ORM\Column(name="way", type="string")
     */
    protected $way;

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
