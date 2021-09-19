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

use CorahnRin\DTO\SpendXp\CharacterSpendXpDTO;
use CorahnRin\Document\Character;
use CorahnRin\Form\SpendXp\CharacterSpendXpType;
use CorahnRin\Repository\CharactersRepository;
use CorahnRin\Repository\DisciplinesRepository;
use CorahnRin\Security\CharacterSpendXpVoter;
use Doctrine\ODM\MongoDB\DocumentManager;
use Main\DependencyInjection\PublicService;
use Symfony\Component\Form\FormError;
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

class CharacterSpendXpController implements PublicService
{
    public function __construct(
        private CharactersRepository $charactersRepository,
        private DisciplinesRepository $disciplinesRepository,
        private Environment $twig,
        private AuthorizationCheckerInterface $authorizationChecker,
        private FormFactoryInterface $formFactory,
        private DocumentManager $em,
        private UrlGeneratorInterface $router,
        private TranslatorInterface $translator
    ) {
    }

    /**
     * @Route(
     *     "/characters/{id}-{nameSlug}/spend-xp",
     *     name="character_spend_xp",
     *     requirements={"id" = "\d+", "nameSlug" = "^[\w-]+$"},
     *     methods={"GET", "POST"}
     * )
     */
    public function __invoke(Request $request, Session $session, string $id, string $nameSlug): Response
    {
        $character = $this->charactersRepository->findByIdAndSlug((int) $id, $nameSlug);

        if (!$character) {
            throw new NotFoundHttpException('Character not found.');
        }

        if (!$this->authorizationChecker->isGranted(CharacterSpendXpVoter::SPEND_XP, $character)) {
            throw new AccessDeniedException('You spend this character\'s experience.');
        }

        $dto = $this->createDTO($character);

        $form = $this->formFactory->create(CharacterSpendXpType::class, $dto)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $spentXp = $dto->getSpentXp($dto::SAVE_XP);

            if ($spentXp > 0) {
                $character->updateFromSpendingXp($dto);
                $this->em->persist($character);
                $this->em->flush();

                $session->getFlashBag()->add('success', $this->translator->trans('character_spend_xp.form.success', ['%xp%' => $spentXp], 'corahn_rin'));

                return new RedirectResponse($this->router->generate('corahnrin_characters_view', ['id' => $character->getId(), 'nameSlug' => $character->getNameSlug()]));
            }

            $form->addError(new FormError($this->translator->trans('character_spend_xp.form.no_xp_spent', [], 'corahn_rin')));
        }

        return new Response($this->twig->render('corahn_rin/character_edit/spend_xp.html.twig', [
            'form' => $form->createView(),
            'base_xp' => $character->getExperienceActual(),
        ]));
    }

    private function createDTO(Character $character): CharacterSpendXpDTO
    {
        $dto = CharacterSpendXpDTO::fromCharacter($character);

        foreach ($this->disciplinesRepository->findAllIndexedByDomains() as $domain => $disciplines) {
            foreach ($disciplines as $discipline) {
                $dto->disciplines->addDisciplinesForDomain($domain, $discipline);
            }
        }

        $dto->disciplines->makeSnapshot();

        return $dto;
    }
}
