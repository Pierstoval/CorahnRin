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

interface FormDTOInterface
{
    public static function createFromEntity(object $entity): static;

    public static function getEntityMutatorMethodName(): string;
}
