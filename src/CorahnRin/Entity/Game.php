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

use CorahnRin\DTO\CreateGameDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use User\Entity\User;

/**
 * @ORM\Entity(repositoryClass="CorahnRin\Repository\GameRepository")
 *
 * @ORM\Table(name="games", uniqueConstraints={@ORM\UniqueConstraint(name="idgUnique", columns={"name", "game_master_id"})})
 */
class Game
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=140, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $gmNotes;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     *
     * @ORM\JoinColumn(name="game_master_id", nullable=false)
     */
    private $gameMaster;

    /**
     * @var Character[]|Collection
     *
     * @ORM\OneToMany(targetEntity="CorahnRin\Entity\Character", mappedBy="game")
     */
    private $characters;

    private function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public static function fromCreateForm(CreateGameDTO $dto, User $creator): self
    {
        $self = new self();

        $self->gameMaster = $creator;
        $self->name = $dto->name;
        $self->summary = $dto->summary;

        return $self;
    }

    public static function fromLegacy(string $name, string $notes, string $summary, User $user): self
    {
        $self = new self();

        $self->gameMaster = $user;
        $self->name = $name;
        $self->summary = $summary;
        $self->gmNotes = $notes;

        return $self;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getGmNotes(): string
    {
        return $this->gmNotes;
    }

    public function getGameMaster(): User
    {
        return $this->gameMaster;
    }

    public function getCharacters(): iterable
    {
        return $this->characters;
    }

    public function getGameMastersName(): string
    {
        return $this->gameMaster->getUsername();
    }

    public function getGameMasterId(): int
    {
        return $this->gameMaster->getId();
    }
}
