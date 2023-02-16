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

namespace EsterenMaps\Repository;

use EsterenMaps\Entity\Map;
use Main\DependencyInjection\PublicService;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\VarExporter\VarExporter;

class MapsRepository implements PublicService
{
    private string $mapJsonFile;
    private string $mapPhpFile;

    public function __construct(
        private SerializerInterface $serializer,
    ) {
        $this->mapJsonFile = \dirname(__DIR__, 3).'/data/map_data.json';
        $this->mapPhpFile = $this->mapJsonFile.'.php';
    }

    public function getMap(): Map
    {
        if (!\is_file($this->mapPhpFile)) {
            $this->generatePhpMapFile();
        }

        return require $this->mapPhpFile;
    }

    public function getJsonMap(): string
    {
        return \file_get_contents($this->mapJsonFile);
    }

    private function generatePhpMapFile(): void
    {
        $json = \json_decode(\file_get_contents($this->mapJsonFile), true, 512, \JSON_THROW_ON_ERROR);

        $data = $this->serializer->deserialize(\json_encode($json['map'], \JSON_THROW_ON_ERROR), Map::class, 'json');

        $export = VarExporter::export($data);

        $content = <<<PHP
        <?php
        return {$export};
        PHP;

        \file_put_contents($this->mapPhpFile, $content, \LOCK_EX);
    }
}
