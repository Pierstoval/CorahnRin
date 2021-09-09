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

use CorahnRin\Document\CharacterProperties\CharacterMiracle;
use CorahnRin\Document\Job;
use CorahnRin\Document\Miracle;
use CorahnRin\Repository\MiraclesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MiraclesSpendXpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Job $job */
        $job = $options['job'];

        $builder
            ->add('major', EntityType::class, [
                'class' => Miracle::class,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'query_builder' => static function (MiraclesRepository $repo) use ($job) {
                    return $repo->getMajorForJobQueryBuilder($job);
                },
            ])
            ->add('minor', EntityType::class, [
                'class' => Miracle::class,
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'name',
                'query_builder' => static function (MiraclesRepository $repo) use ($job) {
                    return $repo->getMinorForJobQueryBuilder($job);
                },
            ])
        ;
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var CharacterMiracle[] $baseMiracle */
        $baseMiracle = $options['base_miracles'];

        $ids = \array_reduce($baseMiracle, static function (array $carry, CharacterMiracle $miracle) {
            $carry[$miracle->getMiracleId()] = true;

            return $carry;
        }, []);

        foreach ($view->children as $minorOrMajorSubforms) {
            foreach ($minorOrMajorSubforms as $child) {
                $child->vars['label_attr']['class'] = 'spend_xp_miracle btn';

                if (isset($ids[(int) $child->vars['value']])) {
                    // Already possessed miracle
                    $child->vars['disabled'] = true;
                    $child->vars['attr']['disabled'] = 'disabled';
                    $child->vars['attr']['readonly'] = true;
                    $child->vars['attr']['class'] = 'disabled';
                    $child->vars['label_attr']['class'] .= ' disabled';
                    $child->vars['label_attr']['disabled'] = 'disabled';
                } else {
                    // Not possessed miracle
                    $child->vars['attr']['data-miracle'] = 'data-miracle';
                    $child->vars['label_attr']['class'] .= ' grey';
                }
            }
        }
    }

    public function getBlockPrefix(): string
    {
        return 'miracles_spend_xp';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('base_miracles');
        $resolver->setAllowedTypes('base_miracles', ['array']);

        $resolver->setRequired('job');
        $resolver->setAllowedTypes('job', [Job::class]);
    }
}
