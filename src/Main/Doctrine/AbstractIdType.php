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

namespace Main\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\ODM\MongoDB\Types\Type;
use Main\Id\AbstractId;

abstract class AbstractIdType extends Type
{
    /**
     * @psalm-return class-string<AbstractId>
     */
    abstract public function getTypeClassName(): string;

    public function convertToPHPValue($value): static
    {
        /** @var class-string<AbstractId> $class */
        $class = $this->getTypeClassName();

        if (null === $value || $value instanceof $class) {
            return $value;
        }

        return $class::fromString($value);
    }

    public function convertToDatabaseValue($value): ?int
    {
        if (\is_string($value)) {
            if (!\is_numeric($value)) {
                throw new \InvalidArgumentException('Expect only numbers as ID.');
            }

            return (int) $value;
        }

        if (null === $value) {
            return null;
        }

        /** @var class-string<AbstractId> $class */
        $class = $this->getTypeClassName();

        if ($value instanceof $class) {
            /** @var AbstractId $value */
            return $value->getValue();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', $class]);
    }
}
