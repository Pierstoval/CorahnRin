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

namespace CorahnRin\Legacy\Model;

use CorahnRin\DTO\LegacyCharacterDTO;
use CorahnRin\Entity\Character;

class LegacyCharacterData
{
    /**
     * @var array
     */
    private $databaseRow;

    /**
     * @var LegacyCharacterDTO
     */
    private $dto;

    /**
     * @var mixed[]
     */
    private $decodedContent;

    private function __construct()
    {
    }

    public static function create(array $databaseRow, LegacyCharacterDTO $dto): self
    {
        $self = new self();

        $self->databaseRow = $databaseRow;

        $self->decodedContent = \json_decode($databaseRow['char_content'], true);

        $self->dto = $dto;

        return $self;
    }

    /*
     * From decoded content, mostly the "char_content" field in the legacy app.
     * Check LegacyCharacterRepository::getCharacterById().
     */

    public function getSex(): string
    {
        return 'Femme' === $this->decodedContent['details_personnage']['sexe']
            ? Character::FEMALE
            : Character::MALE;
    }

    public function getDescription(): string
    {
        return $this->decodedContent['details_personnage']['description'];
    }

    public function getAge(): int
    {
        return (int) $this->decodedContent['age'];
    }

    public function getOrientationName(): string
    {
        return $this->decodedContent['orientation']['name'];
    }

    public function getCombativeness(): int
    {
        return (int) $this->decodedContent['voies'][1]['val'];
    }

    public function getCreativity(): int
    {
        return (int) $this->decodedContent['voies'][2]['val'];
    }

    public function getEmpathy(): int
    {
        return (int) $this->decodedContent['voies'][3]['val'];
    }

    public function getReason(): int
    {
        return (int) $this->decodedContent['voies'][4]['val'];
    }

    public function getConviction(): int
    {
        return (int) $this->decodedContent['voies'][5]['val'];
    }

    /*
     * From database row, can correspond to data other than the character itself.
     * Check LegacyCharacterRepository::getCharacterById().
     */

    public function getGameId(): int
    {
        return (int) $this->databaseRow['game_id'];
    }

    public function getGameSummary(): string
    {
        return $this->databaseRow['game_name'];
    }

    public function getGameNotes(): string
    {
        return $this->databaseRow['game_notes'];
    }

    public function getGameName(): string
    {
        return $this->databaseRow['game_summary'];
    }

    public function getGameMasterEmail(): string
    {
        return $this->databaseRow['gm_user_email'];
    }

    public function getGameMasterUsername(): string
    {
        return $this->databaseRow['gm_user_name'];
    }

    public function getUserEmail(): string
    {
        return $this->databaseRow['user_email'];
    }

    public function getUsername()
    {
        return $this->databaseRow['user_name'];
    }

    public function getUserId(): int
    {
        return (int) $this->databaseRow['user_id'];
    }
}
