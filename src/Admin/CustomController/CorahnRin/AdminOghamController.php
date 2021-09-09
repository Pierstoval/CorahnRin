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

namespace Admin\CustomController\CorahnRin;

use Admin\Controller\AdminController;
use Admin\CustomController\BaseDTOControllerTrait;
use Admin\DTO\EasyAdminDTOInterface;
use CorahnRin\DTO\Admin\OghamAdminDTO;
use CorahnRin\Document\Ogham;

class AdminOghamController extends AdminController
{
    use BaseDTOControllerTrait;

    protected function getDTOClass(): string
    {
        return OghamAdminDTO::class;
    }

    protected function createEntityFromDTO(EasyAdminDTOInterface $dto): object
    {
        return Ogham::fromAdmin($dto);
    }

    protected function updateEntityWithDTO(object $entity, EasyAdminDTOInterface $dto): object
    {
        return $this->doUpdateEntityWithDTO($entity, $dto);
    }

    private function doUpdateEntityWithDTO(Ogham $entity, OghamAdminDTO $dto): Ogham
    {
        $entity->updateFromAdmin($dto);

        return $entity;
    }
}
