<?php

declare(strict_types=1);

namespace Admin\DTO;

interface FormDTOInterface
{
    public static function createFromEntity(object $entity): static;

    public static function getEntityMutatorMethodName(): string;
}
