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

namespace CorahnRin\Validator;

use CorahnRin\Constraint\HasNoSimilarInvitation;
use CorahnRin\Document\Character;
use CorahnRin\Document\Game;
use CorahnRin\Repository\GameInvitationRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class HasNoSimilarInvitationValidator extends ConstraintValidator
{
    private $invitationRepository;

    public function __construct(GameInvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    public function validate($characters, Constraint $constraint): void
    {
        if (!$constraint instanceof HasNoSimilarInvitation) {
            throw new UnexpectedTypeException($constraint, HasNoSimilarInvitation::class);
        }

        if (!($constraint->game instanceof Game)) {
            // Cannot validate if we have an already existing game.

            return;
        }

        foreach ($characters as $character) {
            if (!($character instanceof Character)) {
                throw new UnexpectedTypeException($character, Character::class);
            }
        }

        foreach ($this->invitationRepository->getForCharactersAndGame($characters, $constraint->game) as $invitation) {
            $this->context->addViolation($constraint->message, ['%character_name%' => $invitation->getCharacterName()]);
        }
    }
}
