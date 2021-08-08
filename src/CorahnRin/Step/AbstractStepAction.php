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

namespace CorahnRin\Step;

use Pierstoval\Bundle\CharacterManagerBundle\Action\AbstractStepAction as BaseAbstractStepAction;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractStepAction extends BaseAbstractStepAction
{
    protected static $translationDomain = 'corahn_rin';

    protected function renderCurrentStep(array $parameters = [], string $template = null): Response
    {
        // Default parameters always injected in template.
        // Not overridable, they're mandatory.
        $parameters['current_step'] = $this->step;
        $parameters['steps'] = $this->steps;
        $parameters['current_character'] = $this->getCurrentCharacter();

        // Get template name
        if (null === $template) {
            $template = 'corahn_rin/Steps/'.$this->step->getName().'.html.twig';
        }

        return new Response($this->twig->render($template, $parameters));
    }
}
