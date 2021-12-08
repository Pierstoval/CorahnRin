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

namespace CorahnRin\Constraint;

use CorahnRin\Entity\Game;
use CorahnRin\Validator\HasNoSimilarInvitationValidator;
use Symfony\Component\Validator\Constraint;

class HasNoSimilarInvitation extends Constraint
{
    public string $message = 'corahn_rin.character_invitations.similar_exists';

    public ?Game $game = null;

    public function validatedBy(): string
    {
        return HasNoSimilarInvitationValidator::class;
    }
}
