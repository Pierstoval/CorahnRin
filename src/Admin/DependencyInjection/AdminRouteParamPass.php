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

namespace Admin\DependencyInjection;

use Admin\Controller\AdminController;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AdminRouteParamPass implements CompilerPassInterface
{
    /**
     * @see AdminController
     */
    public function process(ContainerBuilder $container): void
    {
        /* // FIXME/TODO
        $easyadminConfig = $container->getParameter('easyadmin.config');
        $entities = \array_keys($easyadminConfig['entities']);

        $regex = \sprintf('(?:%s)', \implode('|', $entities));

        $container->setParameter('easyadmin_entities_list_regex', $regex);
        */
    }
}
