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

use CorahnRin\Data\DomainsData;
use CorahnRin\DTO\SpendXp\DomainsSpendXpDTO;
use Main\Form\RangeButtonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DomainsSpendXpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DomainsSpendXpDTO $data */
        $data = $options['data'];

        foreach (DomainsData::ALL as $title => $domain) {
            $builder->add($domain['short_name'], RangeButtonType::class, [
                'label' => $title,
                'disabled' => 5 === $data->{$domain['short_name']},
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'data-base' => $data->{$domain['short_name']},
                ],
                'translation_domain' => 'corahn_rin',
            ]);
        }
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $value = $view->vars['value'];
        if ($value instanceof DomainsSpendXpDTO) {
            $baseData = $value->baseDomainsData();

            foreach (DomainsData::ALL as $title => $domain) {
                $view[$domain['short_name']]->vars['value'] = $baseData[$domain['short_name']];
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', DomainsSpendXpDTO::class);
    }

    public function getBlockPrefix(): string
    {
        return 'domains_spend_xp';
    }
}
