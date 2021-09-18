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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 *
 * @ODM\Document(repositoryClass="CorahnRin\Repository\SetbacksRepository")
 */
class Setback
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
     * @ODM\Field(type="string", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ODM\Field(type="string")
     */
    private $malus = '';

    /**
     * Unlucky means that we pick another setback after picking this one.
     *
     * @var bool
     *
     * @ODM\Field(name="is_unlucky", type="boolean")
     */
    private $isUnlucky = false;

    /**
     * Lucky means we pick another setback and it'll be marked as "avoided".
     *
     * @var bool
     *
     * @ODM\Field(name="is_lucky", type="boolean")
     */
    private $isLucky = false;

    /**
     * It can disable either advantages or disadvantages, as they're in the same table.
     *
     * @var Advantage[]|Collection
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\Advantage")
     */
    private array|Collection|ArrayCollection $disabledAdvantages;

    public function __construct()
    {
        $this->disabledAdvantages = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isUnlucky(): bool
    {
        return $this->isUnlucky;
    }

    public function isLucky(): bool
    {
        return $this->isLucky;
    }

    public function getMalus(): string
    {
        return $this->malus;
    }

    /**
     * @return Collection&iterable<Advantage>
     */
    public function getDisabledAdvantages(): iterable
    {
        return $this->disabledAdvantages;
    }
}
