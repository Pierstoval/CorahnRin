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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="miracles")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\MiraclesRepository")
 */
class Miracle
{
    use HasBook;

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
     * @ORM\Column(type="string", length=70, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var Collection<Job>
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Job")
     * @ORM\JoinTable(name="minor_miracles_jobs",
     *     joinColumns={@ORM\JoinColumn(name="miracle_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="job_id", referencedColumnName="id")}
     * )
     */
    protected $minorForJobs;

    /**
     * @var Collection<Job>
     *
     * @ORM\ManyToMany(targetEntity="CorahnRin\Entity\Job")
     * @ORM\JoinTable(name="major_miracles_jobs",
     *     joinColumns={@ORM\JoinColumn(name="miracle_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="job_id", referencedColumnName="id")}
     * )
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
