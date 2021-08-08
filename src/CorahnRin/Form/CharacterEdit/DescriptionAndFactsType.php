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

namespace CorahnRin\Form\CharacterEdit;

use CorahnRin\DTO\CharacterEdit\UpdateDetailsDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptionAndFactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'label' => 'character_edit.description_and_facts.form.description',
                'required' => false,
            ])
            ->add('story', TextareaType::class, [
                'label' => 'character_edit.description_and_facts.form.story',
                'required' => false,
            ])
            ->add('notableFacts', TextareaType::class, [
                'label' => 'character_edit.description_and_facts.form.notableFacts',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateDetailsDTO::class,
            'translation_domain' => 'corahn_rin',
        ]);
    }
}
