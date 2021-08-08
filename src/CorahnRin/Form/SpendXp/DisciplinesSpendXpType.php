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
use CorahnRin\DTO\SpendXp\DisciplinesSpendXpDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisciplinesSpendXpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DisciplinesSpendXpDTO $data */
        $data = $options['data'];

        foreach (DomainsData::ALL as $title => $domain) {
            $builder->add($domain['short_name'], CollectionType::class, [
                'entry_type' => DisciplineDomainScoreSpendXpType::class,
                'label' => $title,
                'required' => false,
                'translation_domain' => 'corahn_rin',
                'data' => $data->{$domain['short_name']},
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('data_class', DisciplinesSpendXpDTO::class)
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'disciplines_spend_xp';
    }
}
