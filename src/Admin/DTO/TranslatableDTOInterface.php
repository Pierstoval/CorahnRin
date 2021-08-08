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

namespace Admin\DTO;

/**
 * To be used with TranslatableDTOTrait.
 */
interface TranslatableDTOInterface
{
    /**
     * Must return a key=>value pair.
     * Keys must correspond to a field in the *entity*.
     * Values must correspond to the *DTO field name* that stores translations for the entity property.
     */
    public static function getTranslatableFields(): array;

    public function setTranslationsFromEntity(object $entity, array $options): void;
}
