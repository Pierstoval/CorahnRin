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
 * @ODM\Document(repositoryClass="CorahnRin\Repository\GeoEnvironmentsRepository")
 */
class GeoEnvironment
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
     * @ODM\Field(name="name", type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ODM\Field(name="description", type="string", nullable=true)
     */
    protected $description;

    /**
     * @var string
     *
     * @ODM\Field(name="domain", type="string")
     */
    protected $domain;

    public function __construct(int $id, string $name, string $description, string $domain)
    {
        DomainsData::validateDomain($domain);

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->domain = $domain;
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

    public function getDomain(): string
    {
        return $this->domain;
    }
}
