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

namespace Admin\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EasyAdminControllerListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            // Must be executed before EasyAdmin's one
            KernelEvents::CONTROLLER => ['onKernelController', 1],
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Some parts of this listener are just copy/pastes from EasyAdmin's base controller.
        // The goal here is to make sure everything's available for EasyAdmin to work with custom URL design.

        $request = $event->getRequest();

        if ('easyadmin' !== $request->attributes->get('_route')) {
            return;
        }

        // this condition happens when accessing the backend homepage, which
        // then redirects to the 'list' action of the first configured entity.
        if (null === $entityName = $request->attributes->get('entity')) {
            return;
        }

        $action = $request->attributes->get('action', 'list');
        $id = $request->attributes->get('id');

        // As the route is "beautified" by putting entity, action and ID in the url,
        // we need this hack for EasyAdmin to retrieve the right values.
        $request->query->set('entity', $entityName);
        $request->query->set('action', $action);
        $request->query->set('id', $id);
    }
}
