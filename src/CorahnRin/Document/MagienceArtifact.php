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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * 
 * @ODM\Document
 */
class MagienceArtifact
{
    /**
     *
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * 
     */
    protected int $id;

    /**
     *
     * @ODM\Field(type="string", nullable=false, unique=true)
     */
    protected string $name;

    /**
     * @ODM\Field(type="string", nullable=true)
     */
    protected string $description;

    /**
     *
     * @ODM\Field(type="smallint")
     */
    protected int $price;

    /**
     * @ODM\Field(type="smallint")
     */
    protected int $consumption;

    /**
     * @ODM\Field(type="smallint")
     */
    protected int $consumptionInterval;

    /**
     *
     * @ODM\Field(type="smallint", nullable=true)
     */
    protected ?int $tank;

    /**
     *
     * @ODM\Field(type="smallint", nullable=false)
     */
    protected int $resistance;

    /**
     *
     * @ODM\Field(type="string", nullable=true)
     */
    protected ?string $vulnerability;

    /**
     *
     * @ODM\Field(type="string", nullable=true)
     */
    protected ?string $handling;

    /**
     *
     * @ODM\Field(type="smallint", nullable=true)
     */
    protected ?int $damage;

    /**
     *
     * @ODM\EmbedOne(targetDocument="CorahnRin\Document\Flux", nullable=false)
     */
    protected Flux $flux;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ODM\Field(type="datetime", nullable=false)
     */
    protected \DateTime $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ODM\Field(type="datetime", nullable=false)
     */
    protected \DateTime $updated;

    /**
     *
     * @ODM\Field(name="deleted", type="datetime", nullable=true)
     */
    protected \DateTime $deleted;

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
