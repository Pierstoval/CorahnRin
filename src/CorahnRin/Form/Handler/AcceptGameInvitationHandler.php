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

namespace CorahnRin\Form\Handler;

use CorahnRin\Document\Game;
use CorahnRin\Document\GameInvitation;
use CorahnRin\Repository\GameInvitationRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class AcceptGameInvitationHandler
{
    private $em;
    private $invitationRepository;

    public function __construct(DocumentManager $em, GameInvitationRepository $invitationRepository)
    {
        $this->em = $em;
        $this->invitationRepository = $invitationRepository;
    }

    public function handle(GameInvitation $invitation): Game
    {
        $game = $invitation->getGame();
        $character = $invitation->getCharacter();

        $character->inviteToGame($invitation);

        $this->em->persist($character);
        $this->em->remove($invitation);

        $sameCharacterInvitations = $this->invitationRepository->findForCharacter($character);

        foreach ($sameCharacterInvitations as $sameCharacterInvitation) {
            $this->em->remove($sameCharacterInvitation);
        }

        $this->em->flush();

        return $game;
    }
}
