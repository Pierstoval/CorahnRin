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

use CorahnRin\DTO\Admin\OghamAdminDTO;
use CorahnRin\Document\Traits\HasBook;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Ogham.
 *
 * 
 * @ODM\Document(repositoryClass="CorahnRin\Repository\OghamRepository")
 */
class Ogham
{
    use HasBook;

    /**
     * @var int
     *
     * @ODM\Field(name="id", type="int", nullable=false)
     * @ODM\Id(type="int", strategy="INCREMENT")
     * 
     */
    private ?int $id;

    /**
     * @ODM\Field(name="name", type="string", nullable=false)
     */
    private string $name;

    /**
     * @var string
     *
     * @ODM\Field(name="description", type="string", nullable=true)
     */
    private ?string $description;

    /**
     * @ODM\Field(name="type", type="string")
     */
    private string $type;

    private function __construct()
    {
    }

    public static function fromAdmin(OghamAdminDTO $dto): self
    {
        $self = new self();

        $self->name = $dto->name;
        $self->type = $dto->type;
        $self->description = $dto->description;
        $self->book = $dto->book;

        return $self;
    }

    public function updateFromAdmin(OghamAdminDTO $dto): void
    {
        $this->name = $dto->name;
        $this->type = $dto->type;
        $this->description = $dto->description;
        $this->book = $dto->book;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
