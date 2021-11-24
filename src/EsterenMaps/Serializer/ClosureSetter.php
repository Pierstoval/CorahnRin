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

namespace EsterenMaps\Serializer;

use Closure;
use InvalidArgumentException;

final class ClosureSetter
{
    public static function getSetter(string $type, object $object): Closure
    {
        return Closure::bind(
            function (string $property, mixed $value): void {
                if (!\property_exists($this, $property)) {
                    throw new InvalidArgumentException(\sprintf(
                        'Property "%s" does not exist in class "%s".',
                        $property,
                        static::class,
                    ));
                }
                $this->{$property} = $value;
            },
            $object,
            $type
        );
    }
}
