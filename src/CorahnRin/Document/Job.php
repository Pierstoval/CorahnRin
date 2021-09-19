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
 * @ODM\Document(repositoryClass="CorahnRin\Repository\JobsRepository")
 */
class Job
{
    use HasBook;

    /**
     * @var int
     *
     * @ODM\Field(name="id", type="int", nullable=false)
     * @ODM\Id(type="int", strategy="INCREMENT")
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
     * @var int
     *
     * @ODM\Field(name="daily_salary", type="int")
     */
    private $dailySalary = 0;

    /**
     * @var string
     *
     * @ODM\Field(name="primary_domain", type="string")
     */
    private $primaryDomain;

    /**
     * @var string[]
     *
     * @ODM\Field(name="secondary_domains", type="collection", nullable=true)
     */
    private $secondaryDomains = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDailySalary(): int
    {
        return $this->dailySalary;
    }

    public function setDailySalary(int $dailySalary): void
    {
        $this->dailySalary = $dailySalary;
    }

    public function getPrimaryDomain(): string
    {
        return $this->primaryDomain;
    }

    public function setPrimaryDomain(string $primaryDomain): void
    {
        $this->primaryDomain = $primaryDomain;
    }

    public function getSecondaryDomains(): array
    {
        return $this->secondaryDomains;
    }

    public function setSecondaryDomains(array $secondaryDomains): void
    {
        $this->secondaryDomains = $secondaryDomains;
    }

    public function addSecondaryDomain(string $domain): void
    {
        DomainsData::validateDomain($domain);

        $this->secondaryDomains[] = $domain;
    }

    public function removeSecondaryDomain(string $domain): void
    {
        DomainsData::validateDomain($domain);

        if (!\in_array($domain, $this->secondaryDomains, true)) {
            throw new \InvalidArgumentException(\sprintf('Current social class does not have specified domain %s', $domain));
        }

        unset($this->secondaryDomains[\array_search($domain, $this->secondaryDomains, true)]);

        $this->secondaryDomains = \array_values($this->secondaryDomains);
    }

    public function hasSecondaryDomain(string $domain): bool
    {
        DomainsData::validateDomain($domain);

        return !\in_array($domain, $this->secondaryDomains, true);
    }
}
