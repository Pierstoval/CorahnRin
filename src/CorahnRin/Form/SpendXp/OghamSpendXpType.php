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

use CorahnRin\Document\Ogham;
use CorahnRin\Repository\OghamRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OghamSpendXpType extends AbstractType
{
    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var array<Ogham>|Collection<Ogham> $baseOgham */
        $baseOgham = $options['base_ogham'];

        if ($baseOgham instanceof Collection) {
            $baseOgham = $baseOgham->toArray();
        }

        $ids = \array_reduce($baseOgham, static function (array $carry, Ogham $ogham) {
            $carry[$ogham->getId()] = true;

            return $carry;
        }, []);

        foreach ($view->children as $child) {
            $child->vars['label_attr']['class'] = 'spend_xp_ogham btn';

            if (isset($ids[(int) $child->vars['value']])) {
                // Already possessed ogham
                $child->vars['disabled'] = true;
                $child->vars['attr']['disabled'] = 'disabled';
                $child->vars['attr']['readonly'] = true;
                $child->vars['attr']['class'] = 'disabled';
                $child->vars['label_attr']['class'] .= ' disabled';
                $child->vars['label_attr']['disabled'] = 'disabled';
            } else {
                // Not possessed ogham
                $child->vars['attr']['data-ogham'] = 'data-ogham';
                $child->vars['label_attr']['class'] .= ' grey';
            }
        }
    }

    public function getParent(): string
    {
        return EntityType::class;
    }

    public function getBlockPrefix()
    {
        return 'ogham_spend_xp';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Ogham::class,
            'required' => false,
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'name',
            'query_builder' => function (OghamRepository $repo) {
                return $repo->getQueryBuilderSortedByName();
            },
        ]);

        $resolver->setRequired('base_ogham');
        $resolver->setAllowedTypes('base_ogham', ['array']);
    }
}
