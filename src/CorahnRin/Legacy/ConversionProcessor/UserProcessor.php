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
use CorahnRin\Legacy\Exception\LegacyCharacterUserMandatoryProcessorException;
use CorahnRin\Legacy\Model\LegacyCharacterData;
use CorahnRin\Legacy\UserProviderForProcessors;

class UserProcessor implements LegacyCharacterConversionProcessor, PrioritizedLegacyProcessor
{
    private $userProvider;

    public function __construct(UserProviderForProcessors $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public static function getPriority(): int
    {
        return 100;
    }

    public function process(LegacyCharacterDTO $characterToUpdate, LegacyCharacterData $legacyData): void
    {
        $user = null;

        if (null === $user && $legacyData->getUserId()) {
            $user = $this->userProvider->getUserFromLegacy($legacyData->getUserEmail(), $legacyData->getUsername());
        }

        if (!$user) {
            throw new LegacyCharacterUserMandatoryProcessorException(self::class, $legacyData->getUsername(), $legacyData->getUserEmail());
        }

        $characterToUpdate->setUser($user);
    }
}
