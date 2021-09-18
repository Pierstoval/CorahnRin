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

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use User\Util\TokenGenerator;

/**
 * @ODM\Document(repositoryClass="CorahnRin\Repository\GameInvitationRepository")
 *
 */
class GameInvitation
{
    /**
     * @var int
     *
     * @ODM\Field(type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     *
     */
    private $id;

    /**
     * @var Game
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Game")
     */
    private $game;

    /**
     * @var Character
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Character")
     */
    private $character;

    /**
     * @var string
     *
     * @ODM\Field(name="token", type="string")
     */
    private $token;

    private function __construct()
    {
    }

    public static function fromGame(Character $character, Game $game): self
    {
        $self = new self();

        $self->game = $game;
        $self->character = $character;
        $self->token = TokenGenerator::generateToken();

        return $self;
    }

    public function getGameMasterId(): int
    {
        return $this->game->getGameMasterId();
    }

    public function getGameId(): int
    {
        return $this->game->getId();
    }

    public function getGameMasterName(): string
    {
        return $this->game->getGameMastersName();
    }

    public function getGameName(): string
    {
        return $this->game->getName();
    }

    public function getGameSummary(): string
    {
        return $this->game->getSummary();
    }

    public function getCharacterId(): int
    {
        return $this->character->getId();
    }

    public function getCharacterName(): string
    {
        return $this->character->getName();
    }

    public function getCharacterNameSlug(): string
    {
        return $this->character->getNameSlug();
    }

    public function getInvitedPlayerName(): string
    {
        return $this->character->getPlayerName();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
