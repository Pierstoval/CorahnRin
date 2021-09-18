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
 *
 * @ODM\Document
 */
class MentalDisorderWay
{
    /**
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     */
    private int $id;

    /**
     * @var MentalDisorder
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\MentalDisorder")
     */
    private MentalDisorder $disorder;

    /**
     * @var string
     *
     * @ODM\Id(type="integer", strategy="INCREMENT")
     * @ODM\Field(name="way", type="string")
     */
    private string $way;

    /**
     * @var bool
     *
     * @ODM\Field(type="boolean")
     */
    private bool $major = false;

    public function __toString()
    {
        return $this->disorder->getName().' - '.$this->way;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDisorder()
    {
        return $this->disorder;
    }

    public function getWay(): string
    {
        return $this->way;
    }

    public function isMajor(): bool
    {
        return $this->major;
    }

    public function isMinor(): bool
    {
        return !$this->major;
    }
}
