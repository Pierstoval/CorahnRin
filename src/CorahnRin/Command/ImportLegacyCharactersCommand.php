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

namespace CorahnRin\Command;

use CorahnRin\Legacy\LegacyCharacterImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportLegacyCharactersCommand extends Command
{
    protected static $defaultName = 'corahnrin:legacy-import:characters';

    private $characterImporter;

    public function __construct(LegacyCharacterImporter $characterImporter)
    {
        parent::__construct(static::$defaultName);
        $this->characterImporter = $characterImporter;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create characters from the old database and insert them here.')
            ->addArgument('character-id', InputArgument::REQUIRED, 'The identifier of the character to import.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        $this->characterImporter->importCharacterFromId((int) $input->getArgument('character-id'));
    }
}
