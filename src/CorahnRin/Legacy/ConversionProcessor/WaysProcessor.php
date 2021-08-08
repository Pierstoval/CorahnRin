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
use CorahnRin\DTO\WaysDTO;
use CorahnRin\Legacy\Model\LegacyCharacterData;

class WaysProcessor implements LegacyCharacterConversionProcessor
{
    public function process(LegacyCharacterDTO $characterToUpdate, LegacyCharacterData $legacyData): void
    {
        $waysObject = WaysDTO::create(
            $legacyData->getCombativeness(),
            $legacyData->getCreativity(),
            $legacyData->getEmpathy(),
            $legacyData->getReason(),
            $legacyData->getConviction()
        );

        $characterToUpdate->setWays($waysObject);
    }
}
