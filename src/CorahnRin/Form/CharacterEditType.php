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

namespace CorahnRin\Form;

use CorahnRin\DTO\CharacterEdit\CharacterEditDTO;
use CorahnRin\Form\CharacterEdit\DescriptionAndFactsType;
use CorahnRin\Form\CharacterEdit\InventoryType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descriptionAndFacts', DescriptionAndFactsType::class, [
                'label' => 'character_edit.description_and_facts',
            ])
            ->add('inventory', InventoryType::class, [
                'label' => 'character_edit.inventory',
                'block_name' => 'inventory',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CharacterEditDTO::class,
            'translation_domain' => 'corahn_rin',
        ]);
    }
}
