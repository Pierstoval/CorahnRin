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

namespace CorahnRin\Controller\Character;

use CorahnRin\DTO\CharacterEdit\CharacterEditDTO;
use CorahnRin\Document\Character;
use CorahnRin\Form\CharacterEditType;
use CorahnRin\Repository\CharactersRepository;
use CorahnRin\Security\CharacterEditVoter;
use Doctrine\ODM\MongoDB\DocumentManager;
use Main\DependencyInjection\PublicService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class CharacterEditController implements PublicService
{
    private Environment $twig;
    private AuthorizationCheckerInterface $authChecker;
    private FormFactoryInterface $formFactory;
    private DocumentManager $em;
    private UrlGeneratorInterface $router;
    private TranslatorInterface $translator;
    private CharactersRepository $charactersRepository;

    public function __construct(
        Environment $twig,
        CharactersRepository $charactersRepository,
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface $formFactory,
        DocumentManager $em,
        UrlGeneratorInterface $router,
        TranslatorInterface $translator
    ) {
        $this->twig = $twig;
        $this->authChecker = $authorizationChecker;
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->translator = $translator;
        $this->charactersRepository = $charactersRepository;
    }

    /**
     * @Route(
     *     "/characters/{id}-{nameSlug}/edit",
     *     name="corahnrin_characters_edit",
     *     requirements={"id" = "\d+", "nameSlug" = "^[\w-]+$"},
     *     methods={"GET", "POST"}
     * )
     */
    public function edit(Request $request, Session $session, string $id, string $nameSlug)
    {
        $character = $this->charactersRepository->findByIdAndSlug((int) $id, $nameSlug);

        if (!$character) {
            throw new NotFoundHttpException('Character not found');
        }

        if (!$this->authChecker->isGranted(CharacterEditVoter::CHARACTER_EDIT, $character)) {
            throw new AccessDeniedException('You cannot edit this character.');
        }

        $dto = CharacterEditDTO::fromCharacter($character);

        // First we need to create ALL forms.
        // They must be stored in instances of the TabbedForm class.
        // since we'll reuse them in handlers, etc.
        $form = $this->formFactory->create(CharacterEditType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleSuccess($dto, $character, $session);
        }

        return new Response(
            $this->twig->render(
                'corahn_rin/character_edit/edit.html.twig',
                [
                    'character' => $character,
                    'form' => $form->createView(),
                ]
            )
        );
    }

    private function handleSuccess(CharacterEditDTO $dto, Character $character, Session $session): RedirectResponse
    {
        $character->updateFromEditForm($dto);

        $this->em->persist($character);
        $this->em->flush();

        $session->getFlashBag()->add('success', $this->translator->trans('character_edit.message.success', [], 'corahn_rin'));

        return new RedirectResponse($this->router->generate('corahnrin_characters_view', [
            'id' => $character->getId(),
            'nameSlug' => $character->getNameSlug(),
        ]));
    }
}
