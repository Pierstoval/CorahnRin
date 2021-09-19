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

namespace CorahnRin\Security;

use CorahnRin\Document\Character;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use User\Document\User;

class CharacterSpendXpVoter extends Voter
{
    public const SPEND_XP = 'character.spend_xp';

    protected function supports($attribute, $subject): bool
    {
        return self::SPEND_XP === $attribute && $subject instanceof Character;
    }

    /**
     * @param Character $character
     */
    protected function voteOnAttribute($attribute, $character, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $character->getUser()->getId() === $user->getId();
    }
}
