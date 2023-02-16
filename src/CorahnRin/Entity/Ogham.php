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

use CorahnRin\DTO\Admin\OghamAdminDTO;
use CorahnRin\Entity\Traits\HasBook;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ogham.
 *
 * @ORM\Table(name="ogham")
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\OghamRepository")
 */
class Ogham
{
    use HasBook;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected ?int $id;

    /**
     * @ORM\Column(name="name", type="string", length=70, nullable=false, unique=true)
     */
    protected string $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected ?string $description;

    /**
     * @ORM\Column(name="type", type="text", length=50)
     */
    protected string $type;

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
