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
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * SocialClass.
 *
 *
 * @ODM\Document(repositoryClass="CorahnRin\Repository\SocialClassRepository")
 */
class SocialClass
{
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
     * @var string[]
     *
     * @ODM\Field(name="domains", type="collection")
     */
    private $domains = [];

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

    public function getDomains(): array
    {
        return $this->domains;
    }

    public function hasDomain(string $domain): bool
    {
        DomainsData::validateDomain($domain);

        return \in_array($domain, $this->domains, true);
    }
}
