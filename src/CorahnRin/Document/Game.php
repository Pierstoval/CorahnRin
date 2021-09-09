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

use CorahnRin\DTO\CreateGameDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use User\Document\User;

/**
 * @ODM\Document(repositoryClass="CorahnRin\Repository\GameRepository")
 * 
 */
class Game
{
    use TimestampableDocument;

    /**
     * @var int
     *
     * @ODM\Field(type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
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
    private $summary;

    /**
     * @var string
     *
     * @ODM\Field(type="string", nullable=true)
     */
    private $gmNotes;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User\Document\User")
     * @ORM\JoinColumn(name="game_master_id", nullable=false)
     */
    private $gameMaster;

    /**
     * @var Character[]|Collection
     *
     * @ODM\EmbedMany(targetDocument="CorahnRin\Document\Character")
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
