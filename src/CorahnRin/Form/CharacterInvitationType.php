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

use CorahnRin\Constraint\HasNoSimilarInvitation;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\Game;
use CorahnRin\Repository\CharactersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CharacterInvitationType extends AbstractType
{
    public function getParent()
    {
        return EntityType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $options['invitation_constraint']->game = $options['game'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'games.create.charactersToInvite',
            'translation_domain' => 'corahn_rin',
            'class' => Character::class,
            'choice_label' => 'getName',
            'required' => false,
            'query_builder' => static function (CharactersRepository $repository) {
                return $repository->getInvitableCharactersBuilder();
            },
            'multiple' => true,
            'constraints' => [
                $constraint = new HasNoSimilarInvitation(),
                new Assert\Count(['min' => 1]),
            ],
            'invitation_constraint' => $constraint,
            'game' => null,
        ]);

        $resolver->setAllowedTypes('game', ['null', Game::class]);
        $resolver->setAllowedTypes('invitation_constraint', HasNoSimilarInvitation::class);
    }
}
