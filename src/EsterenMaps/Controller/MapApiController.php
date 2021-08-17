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

namespace EsterenMaps\Controller;

use EsterenMaps\Repository\MapsRepository;
use Main\DependencyInjection\PublicService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MapApiController implements PublicService
{
    public function __construct(private MapsRepository $mapsRepository)
    {
    }

    public function __invoke(string | int $id): JsonResponse
    {
        if (!$id) {
            throw new NotFoundHttpException('Please enter a Map ID.');
        }

        if (!\is_numeric($id)) {
            throw new BadRequestException('Please enter a valid ID.');
        }

        $id = (int) $id;

        $map = $this->mapsRepository->getMap();

        if ($map->getId() !== $id) {
            throw new NotFoundHttpException('Map not found.');
        }

        return new JsonResponse($this->mapsRepository->getJsonMap(), 200, [], true);
    }
}
