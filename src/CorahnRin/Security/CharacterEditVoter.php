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

class CharacterEditVoter extends Voter
{
    public const CHARACTER_EDIT = 'CHARACTER_EDIT';

    protected function supports($attribute, $subject): bool
    {
        return self::CHARACTER_EDIT === $attribute;
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

        if (!($character instanceof Character)) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->getId() === $character->getUser()->getId();
    }
}
