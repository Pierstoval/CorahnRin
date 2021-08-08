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

use CorahnRin\Data\Orientation;
use CorahnRin\DTO\LegacyCharacterDTO;
use CorahnRin\Legacy\Model\LegacyCharacterData;

class OrientationProcessor implements LegacyCharacterConversionProcessor
{
    public function process(LegacyCharacterDTO $characterToUpdate, LegacyCharacterData $legacyData): void
    {
        $legacyOrientation = $legacyData->getOrientationName();

        if ('Instinctive' === $legacyOrientation) {
            $orientation = Orientation::INSTINCTIVE;
        } elseif ('Rationnelle' === $legacyOrientation) {
            $orientation = Orientation::RATIONAL;
        } else {
            throw new \InvalidArgumentException(\sprintf('Invalid personality orientation "%s".', $legacyOrientation));
        }

        $characterToUpdate->setOrientation($orientation);
    }
}
