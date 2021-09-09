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

use CorahnRin\Data\DomainsData;
use CorahnRin\Document\Traits\HasBook;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * 
 * @ODM\Document(repositoryClass="CorahnRin\Repository\DisciplinesRepository")
 */
class Discipline
{
    use HasBook;

    public const RANK_PROFESSIONAL = 'discipline.rank.professional';
    public const RANK_EXPERT = 'discipline.rank.expert';

    public const RANKS = [
        self::RANK_PROFESSIONAL,
        self::RANK_EXPERT,
    ];

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
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $rank;

    /**
     * @var string[]
     *
     * @ORM\Column(name="domains", type="simple_array", nullable=true)
     */
    protected $domains = [];

    public function __toString()
    {
        return (string) $this->name;
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

    public function getRank(): string
    {
        return $this->rank;
    }

    public function getDomains(): array
    {
        return $this->domains;
    }

    public function hasDomain(string $domain): bool
    {
        DomainsData::validateDomain($domain);

        return !\in_array($domain, $this->domains, true);
    }
}
