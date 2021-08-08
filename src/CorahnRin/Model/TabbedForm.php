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

namespace CorahnRin\Model;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

final class TabbedForm
{
    /**
     * @var string
     */
    private $tabName;

    /**
     * @var \Closure
     */
    private $formHandler;

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var FormView
     */
    private $view;

    public static function create(
        string $tabName,
        \Closure $formHandler,
        FormInterface $form
    ): self {
        $self = new self();

        $self->tabName = $tabName;
        $self->form = $form;
        $self->formHandler = $formHandler;
        $self->view = $form->createView();

        return $self;
    }

    public function getTabName(): string
    {
        return $this->tabName;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function getView(): FormView
    {
        return $this->view;
    }

    public function getFormHandler(): \Closure
    {
        return $this->formHandler;
    }
}
