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

namespace CorahnRin\Exception;

class InvalidSessionToCharacterValue extends \InvalidArgumentException
{
    public function __construct(string $propertyName, $value, string $expectedType)
    {
        parent::__construct(\sprintf(
            'Invalid character value for property %s. Expected argument of type "%s", "%s" given',
            $propertyName,
            $expectedType,
            \is_object($value) ? \get_class($value) : \gettype($value)
        ));
    }
}
