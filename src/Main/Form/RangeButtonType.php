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

namespace Main\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;

class RangeButtonType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Transforms numbers to strings & vice-versa
        $builder->addModelTransformer($this);
    }

    public function getBlockPrefix(): string
    {
        return 'range_button';
    }

    public function getParent(): string
    {
        return RangeType::class;
    }

    public function transform($value): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!\is_numeric($value)) {
            throw new TransformationFailedException(\sprintf(
                'Expected range-button value to be a number, "%s" given',
                \get_debug_type($value)
            ));
        }

        return (string) $value;
    }

    public function reverseTransform($value): ?int
    {
        if (null === $value) {
            return null;
        }

        if (!\is_numeric($value)) {
            throw new TransformationFailedException(\sprintf(
                'Expected range-button value to be a number, "%s" given',
                \get_debug_type($value)
            ));
        }

        return (int) $value;
    }
}
