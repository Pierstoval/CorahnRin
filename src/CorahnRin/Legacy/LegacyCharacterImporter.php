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

namespace CorahnRin\Legacy;

use CorahnRin\DTO\LegacyCharacterDTO;
use CorahnRin\Entity\Game;
use CorahnRin\Legacy\ConversionProcessor\LegacyCharacterConversionProcessor;
use CorahnRin\Legacy\ConversionProcessor\PrioritizedLegacyProcessor;
use CorahnRin\Legacy\Exception\LegacyCharacterNotFoundException;
use CorahnRin\Legacy\Exception\ProcessorException;
use CorahnRin\Legacy\Exception\StopLegacyCharacterProcessingException;
use CorahnRin\Legacy\Model\LegacyCharacterData;
use CorahnRin\Legacy\Repository\LegacyCharacterRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use User\Entity\User;
use User\Repository\UserRepository;

class LegacyCharacterImporter
{
    private $userManager;
    private $em;
    private $legacyCharacterRepository;

    /**
     * @var array|LegacyCharacterConversionProcessor[]|\Traversable
     */
    private $processors = [];

    /** @var User[] */
    private $users = [];

    /** @var EntityRepository[] */
    private $repositories = [];

    /** @var Game[] */
    private $games = [];

    public function __construct(
        iterable $processors,
        LegacyCharacterRepository $legacyCharacterRepository,
        ManagerRegistry $managerRegistry,
        UserRepository $userManager
    ) {
        $this->userManager = $userManager;
        $this->em = $managerRegistry->getManager();
        $this->legacyCharacterRepository = $legacyCharacterRepository;

        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }

        $this->sortProcessors();
    }

    public function importCharacterFromId(int $id): LegacyCharacterDTO
    {
        $arrayCharacter = $this->legacyCharacterRepository->getCharacterById($id);

        if (!$arrayCharacter) {
            throw new LegacyCharacterNotFoundException($id);
        }

        return $this->doCreateCharacter($arrayCharacter);
    }

    private function doCreateCharacter($characterFromDb): LegacyCharacterDTO
    {
        $characterDTO = new LegacyCharacterDTO();

        $legacyData = LegacyCharacterData::create($characterFromDb, $characterDTO);

        /** @var ProcessorException[] $errors */
        $errors = [];

        foreach ($this->processors as $processor) {
            try {
                $processor->process($characterDTO, $legacyData);
            } catch (StopLegacyCharacterProcessingException $e) {
                $errors[$e->getProcessorClass()] = $e;

                break;
            } catch (ProcessorException $e) {
                $errors[$e->getProcessorClass()] = $e;
            }
        }

        if (\count($errors)) {
            $message = '';
            foreach ($errors as $class => $e) {
                $message .= "\n{$class}:{$e->getMessage()}";
            }

            throw new \RuntimeException(\trim($message));
        }

        return $characterDTO;
    }

    private function addProcessor(LegacyCharacterConversionProcessor $processor): void
    {
        $this->processors[] = $processor;
    }

    private function sortProcessors(): void
    {
        $processors = $this->processors;

        $prioritizedProcessors = $standardProcessors = [];

        foreach ($processors as $processor) {
            if ($processor instanceof PrioritizedLegacyProcessor) {
                $prioritizedProcessors[] = $processor;
            } else {
                $standardProcessors[] = $processor;
            }
        }

        \usort($prioritizedProcessors, static function (
            PrioritizedLegacyProcessor $processor1,
            PrioritizedLegacyProcessor $processor2
        ) {
            return $processor2::getPriority() <=> $processor1::getPriority();
        });

        $this->processors = \array_merge($prioritizedProcessors, $standardProcessors);
    }
}
