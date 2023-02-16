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

namespace CorahnRin\Step;

use CorahnRin\Exception\CharacterException;
use CorahnRin\GeneratorTools\SessionToCharacter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use User\Entity\User;

class Step20Finish extends AbstractStepAction
{
    private $sessionToCharacter;
    private $authChecker;
    private $tokenStorage;

    public function __construct(
        SessionToCharacter $sessionToCharacter,
        AuthorizationCheckerInterface $authChecker,
        TokenStorageInterface $tokenStorage
    ) {
        $this->sessionToCharacter = $sessionToCharacter;
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): Response
    {
        $this->updateCharacterStep(true);

        $user = null;
        $token = $this->tokenStorage->getToken();

        if ($token) {
            $user = $token->getUser();
            if (!$user instanceof User) {
                $user = null;
            }
        }

        try {
            $character = $this->sessionToCharacter->createCharacterFromGeneratorValues($this->getCurrentCharacter(), $user);
        } catch (CharacterException $e) {
            $character = null;
        }

        if (!$character) {
            $this->flashMessage('errors.character_not_complete');

            return $this->goToStep(1);
        }

        $canSaveCharacter = $this->authChecker->isGranted('ROLE_ADMIN');

        if (
            $this->request->isMethod('POST')
        ) {
            if ($canSaveCharacter) {
                $this->clearAllStepsValues();
                $this->em->persist($character);
                $this->em->flush();


                return new RedirectResponse($this->router->generate('corahnrin_characters_view', [
                    'id' => $character->getId(),
                    'nameSlug' => $character->getNameSlug(),
                ]));
            }

            $this->flashMessage('Character save is disabled for now, as the app is still in alpha stage. Thanks for understanding :)', 'warning');
        }

        return $this->renderCurrentStep([
            'character' => $character,
        ], 'corahn_rin/Steps/20_finish.html.twig');
    }
}
