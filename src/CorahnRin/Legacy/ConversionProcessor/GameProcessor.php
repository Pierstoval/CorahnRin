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

namespace CorahnRin\Legacy\ConversionProcessor;

use CorahnRin\DTO\LegacyCharacterDTO;
use CorahnRin\Entity\Game;
use CorahnRin\Legacy\Exception\ProcessorException;
use CorahnRin\Legacy\Model\LegacyCharacterData;
use CorahnRin\Legacy\UserProviderForProcessors;
use CorahnRin\Repository\GameRepository;

class GameProcessor implements LegacyCharacterConversionProcessor
{
    private $gameRepository;
    private $userProvider;

    public function __construct(
        GameRepository $gameRepository,
        UserProviderForProcessors $userProvider
    ) {
        $this->gameRepository = $gameRepository;
        $this->userProvider = $userProvider;
    }

    public function process(LegacyCharacterDTO $characterToUpdate, LegacyCharacterData $legacyData): void
    {
        $game = null;
        $legacyGameId = $legacyData->getGameId();

        if ($legacyGameId) {
            $game = $this->gameRepository->find($legacyGameId);

            if (!$game) {
                $user = $this->userProvider->getUserFromLegacy($legacyData->getGameMasterEmail(), $legacyData->getGameMasterUsername());

                if (!$user) {
                    throw new ProcessorException(self::class, 'Error when importing game "'.$legacyData->getGameName().'", because no user was found.');
                }

                // We can create a new game from the legacy one.
                // But in the end, game can be re-associated and changed.
                // That's why we don't persist it here.
                $game = Game::fromLegacy(
                    $legacyData->getGameName(),
                    $legacyData->getGameNotes(),
                    $legacyData->getGameSummary(),
                    $user
                );
            }
        }

        if ($game) {
            $characterToUpdate->setGame($game);
        }
    }
}
