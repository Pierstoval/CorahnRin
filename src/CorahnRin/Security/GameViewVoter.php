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

use CorahnRin\Entity\Game;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use User\Entity\User;

class GameViewVoter extends Voter
{
    public const MANAGE_GAME = 'game.manage';

    protected function supports($attribute, $subject): bool
    {
        return self::MANAGE_GAME === $attribute && $subject instanceof Game;
    }

    /**
     * @param Game $game
     */
    protected function voteOnAttribute($attribute, $game, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!($user instanceof User)) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->getId() === $game->getGameMasterId();
    }
}
