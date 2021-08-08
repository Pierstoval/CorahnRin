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

use CorahnRin\DTO\SpendXp\DisciplineDomainScoreSpendXpDTO;
use CorahnRin\Entity\Discipline;
use Main\Form\RangeButtonType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisciplineDomainScoreSpendXpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('discipline', EntityType::class, [
                'label' => false,
                'class' => Discipline::class,
                'disabled' => true,
                'block_prefix' => 'disciplines_spend_xp_score_hidden',
            ])
            ->add('domain', HiddenType::class, [
                'label' => false,
            ])
            ->add('score', RangeButtonType::class, [
                'disabled' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', DisciplineDomainScoreSpendXpDTO::class);
        $resolver->setAllowedTypes('data', DisciplineDomainScoreSpendXpDTO::class);
    }

    public function getBlockPrefix(): string
    {
        return 'discipline_domain_score_spend_xp';
    }
}
