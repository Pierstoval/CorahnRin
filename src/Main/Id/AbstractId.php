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

namespace Main\Id;

use InvalidArgumentException;
use Stringable;

abstract class AbstractId implements Stringable
{
    private int $id;

    final protected function __construct(int $id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return (string) $this->id;
    }

    public function getValue(): int
    {
        return $this->id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function from(mixed $id): static
    {
        if (\is_int($id)) {
            return static::fromInt($id);
        }

        if (\is_string($id)) {
            return static::fromString($id);
        }

        throw new InvalidArgumentException(\sprintf(
            'Unsupported ID type "%s".',
            \get_debug_type($id),
        ));
    }

    public static function fromInt(int $id): static
    {
        return new static($id);
    }

    public static function fromString(string $id): static
    {
        return new static((int) $id);
    }

    public function equals(self $id): bool
    {
        return $id->getValue() === $this->id;
    }
}
