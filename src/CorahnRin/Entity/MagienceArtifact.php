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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="artifacts")
 * @ORM\Entity
 */
class MagienceArtifact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=70, nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    protected $price;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    protected $consumption;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    protected $consumptionInterval;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $tank;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=false)
     */
    protected $resistance;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $vulnerability;

    /**
     * @var null|string
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $handling;

    /**
     * @var null|int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $damage;

    /**
     * @var Flux
     *
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Flux")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $flux;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $created;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getConsumption(): int
    {
        return $this->consumption;
    }

    public function getConsumptionInterval(): int
    {
        return $this->consumptionInterval;
    }

    public function getTank(): ?int
    {
        return $this->tank;
    }

    public function hasTank(): bool
    {
        return null !== $this->tank;
    }

    public function getResistance(): int
    {
        return $this->resistance;
    }

    public function getVulnerability(): ?string
    {
        return $this->vulnerability;
    }

    public function getHandling(): ?string
    {
        return $this->handling;
    }

    public function getDamage(): ?int
    {
        return $this->damage;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    public function getFlux(): Flux
    {
        return $this->flux;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }

    public function getDeleted(): \DateTime
    {
        return $this->deleted;
    }
}
