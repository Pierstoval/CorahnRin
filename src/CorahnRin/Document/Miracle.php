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
 * @ODM\Document(repositoryClass="CorahnRin\Repository\MiraclesRepository")
 */
class Miracle
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
     * @ODM\Field(type="string", nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var Collection<Job>
     *
     * @ODM\EmbedMany(targetDocument="CorahnRin\Document\Job")
     */
    protected $minorForJobs;

    /**
     * @var Collection<Job>
     *
     * @ODM\EmbedMany(targetDocument="CorahnRin\Document\Job")
     */
    protected $majorForJobs;

    public function __construct()
    {
        $this->minorForJobs = new ArrayCollection();
        $this->majorForJobs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Job[]
     */
    public function isMajorForJobs(): iterable
    {
        return $this->majorForJobs;
    }

    /**
     * @return Job[]
     */
    public function isMinorForJobs(): iterable
    {
        return $this->minorForJobs;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }
}
