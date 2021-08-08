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

namespace CorahnRin\Form\SpendXp;

use CorahnRin\DTO\SpendXp\CharacterSpendXpDTO;
use Main\Form\RangeButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CharacterSpendXpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CharacterSpendXpDTO $data */
        $data = $options['data'];

        $builder
            ->add('speed', RangeButtonType::class, [
                'label' => 'character_spend_xp.form.speed',
                'required' => false,
                'disabled' => 5 === $data->speed,
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'data-base' => $data->speed,
                ],
                'translation_domain' => 'corahn_rin',
            ])
            ->add('defense', RangeButtonType::class, [
                'label' => 'character_spend_xp.form.defense',
                'required' => false,
                'disabled' => 10 === $data->defense,
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'data-base' => $data->defense,
                ],
                'translation_domain' => 'corahn_rin',
            ])
            ->add('domains', DomainsSpendXpType::class, [
                'required' => false,
                'data' => $data->domains,
            ])
            ->add('disciplines', DisciplinesSpendXpType::class, [
                'required' => false,
                'data' => $data->disciplines,
            ])
        ;

        if ($data->getCharacter()->canHaveOghams()) {
            $builder
                ->add('ogham', OghamSpendXpType::class, [
                    'base_ogham' => $data->baseOgham,
                ])
            ;
        }
        if ($data->getCharacter()->canHaveMiracles()) {
            $builder
                ->add('miracles', MiraclesSpendXpType::class, [
                    'base_miracles' => $data->baseMiracles,
                    'job' => $data->getCharacter()->getJob(),
                ])
            ;
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $value = $view->vars['value'];
        if ($value instanceof CharacterSpendXpDTO) {
            $view['speed']->vars['value'] = $value->getBaseSpeed();
            $view['defense']->vars['value'] = $value->getBaseDefense();
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('data_class', CharacterSpendXpDTO::class)
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'character_spend_xp';
    }
}
