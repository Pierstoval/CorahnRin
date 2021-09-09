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
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * 
 * @ODM\Document(repositoryClass="CorahnRin\Repository\MentalDisorderRepository")
 */
class MentalDisorder
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
     * @ODM\Field(type="string", nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=true)
     */
    protected $description;

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }
}
