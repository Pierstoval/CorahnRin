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
use CorahnRin\Mailer\CampaignInvitationMailer;
use Doctrine\ODM\MongoDB\DocumentManager;

class InviteCharactersToGameHandler
{
    private $mailer;
    private $em;

    public function __construct(
        CampaignInvitationMailer $mailer,
        DocumentManager $em
    ) {
        $this->mailer = $mailer;
        $this->em = $em;
    }

    public function handle(iterable $characters, Game $game, string $hostname): void
    {
        $invitations = [];

        foreach ($characters as $character) {
            $invitation = GameInvitation::fromGame($character, $game);

            $invitations[] = $invitation;

            $this->em->persist($invitation);
        }

        $this->em->flush();

        foreach ($invitations as $invitation) {
            $this->mailer->sendInvitationMail($invitation, $hostname);
        }
    }
}
