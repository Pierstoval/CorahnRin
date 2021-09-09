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

namespace CorahnRin\Legacy;

use CorahnRin\DTO\LegacyCharacterDTO;
use CorahnRin\Document\Character;

class LegacyToNewCharacterConverter
{
    public function createCharacterFromDto(LegacyCharacterDTO $characterDTO): Character
    {
        $character = new Character($characterDTO->getName());

        $setter = $this->createCharacterSetterCallback($character);

        $this->doCreateCharacterFromDto($setter, $characterDTO);

        return $character;
    }

    private function doCreateCharacterFromDto(callable $setter, LegacyCharacterDTO $characterDTO): void
    {
        $setter('orientation', $characterDTO->getOrientation());

        // TODO
    }

    /**
     * To not alter the business logic by exposing many useless setters only used in legacy imports,
     * we use this hack to be able to set private properties in the character without setters.
     * The only place where a character can be updated is the business logic.
     * Legacy import is a really specific and isolated case.
     */
    private function createCharacterSetterCallback(Character $character): callable
    {
        $closure = function (string $property, $value): void {
            if (!\property_exists($this, $property)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Cannot set property "%s" to Character since this property does not exist.',
                    $property
                ));
            }

            $this->{$property} = $value;
        };

        return $closure->bindTo($character, Character::class);
    }
}
