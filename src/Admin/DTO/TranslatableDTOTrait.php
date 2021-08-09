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

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

trait TranslatableDTOTrait
{
    private ?PropertyAccessorInterface $_accessor = null;

    public function setTranslationsFromEntity(object $entity, array $options): void
    {
        if (!$this instanceof TranslatableDTOInterface) {
            throw new UnexpectedTypeException($this, TranslatableDTOInterface::class);
        }

        if (!$this->_accessor) {
            $this->_accessor = (PropertyAccess::createPropertyAccessor());
        }

        [
            'translations' => $translations,
            'locales' => $locales,
        ] = $options;

        $translatableFields = static::getTranslatableFields();

        if ($translations) {
            foreach ($translations as $locale => $values) {
                foreach ($values as $entityPropertyName => $value) {
                    if (!isset($translatableFields[$entityPropertyName])) {
                        throw new \InvalidArgumentException(\sprintf(
                            'Impossible to find field %s in DTO %s.',
                            $entityPropertyName,
                            static::class
                        ));
                    }

                    $dtoPropertyName = $translatableFields[$entityPropertyName];

                    $this->{$dtoPropertyName}[$locale] = $value;
                }
            }
        }

        foreach ($locales as $locale) {
            foreach ($translatableFields as $entityPropertyName => $dtoPropertyName) {
                if (isset($this->{$dtoPropertyName}[$locale])) {
                    continue;
                }
                $this->{$dtoPropertyName}[$locale] = $this->_accessor->getValue($entity, $entityPropertyName);
            }
        }
    }
}
