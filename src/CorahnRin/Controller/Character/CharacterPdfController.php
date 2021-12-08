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

use CorahnRin\Repository\CharactersRepository;
use Main\DependencyInjection\PublicService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CharacterPdfController implements PublicService
{
    private $charactersRepository;
    private $chromiumPath;
    private $characterFilesOutputDir;
    private $router;
    private $logger;

    public function __construct(
        CharactersRepository $charactersRepository,
        UrlGeneratorInterface $router,
        LoggerInterface $logger,
        string $chromiumPath,
        string $characterFilesOutputDir
    ) {
        $this->charactersRepository = $charactersRepository;
        $this->chromiumPath = $chromiumPath;
        $this->characterFilesOutputDir = $characterFilesOutputDir;
        $this->router = $router;
        $this->logger = $logger;
    }

    /**
     * @Route("/characters/{id}-{nameSlug}/print",
     *     name="corahnrin_characters_print",
     *     requirements={"id" = "\d+", "nameSlug" = "^[\w-]+$"},
     *     methods={"GET"}
     * )
     */
    public function print(string $id, string $nameSlug, Session $session): Response
    {
        $character = $this->charactersRepository->findForView($id, $nameSlug);

        if (!$character) {
            throw new NotFoundHttpException('Character not found.');
        }

        if (!\is_dir($this->characterFilesOutputDir)) {
            (new Filesystem())->mkdir($this->characterFilesOutputDir);
        }

        $fileName = $this->characterFilesOutputDir.'/character-'.$character->getNameSlug().'.pdf';

        if (\file_exists($fileName)) {
            (new Filesystem())->remove($fileName);
        }

        $characterViewUrl = $this->router->generate('corahnrin_characters_view', ['id' => $id, 'nameSlug' => $nameSlug], UrlGeneratorInterface::ABSOLUTE_URL);

        $process = Process::fromShellCommandline(
            $this->chromiumPath.
            ' --no-sandbox '.
            ' --headless '.
            ' --disable-gpu '.
            ' --run-all-compositor-stages-before-draw '.
            ' --no-margins '.
            ' --print-background '.
            ' --ignore-certificate-errors '. // Mandatory in dev, unfortunately.
            ' --virtual-time-budget=20000 '.
            ' --print-to-pdf="'.$fileName.'" '.
            $characterViewUrl
        );
        $process->setTimeout(20);

        try {
            $process->mustRun();
        } catch (ProcessTimedOutException|ProcessFailedException|ProcessSignaledException $e) {
            $session->getFlashBag()->add('error', 'character.error.pdf_error');
            $this->logger->error('Character PDF generation failed', [
                'character' => ['id' => $id, 'nameSlug' => $nameSlug],
                'exception' => $e->getMessage(),
            ]);

            return new Response('Failed', 500);
        }

        $response = new BinaryFileResponse($fileName);
        $response->deleteFileAfterSend();

        return $response;
    }
}
