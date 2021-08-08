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

namespace CorahnRin\DTO\Session;

use CorahnRin\Entity\Discipline;

class SessionDisciplineDTO
{
    /**
     * @var string
     */
    private $domain;

    /**
     * @var Discipline
     */
    private $discipline;

    private function __construct()
    {
    }

    public static function createFromSession(string $domain, Discipline $discipline): self
    {
        $self = new self();

        $self->domain = $domain;
        $self->discipline = $discipline;

        return $self;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getDiscipline(): Discipline
    {
        return $this->discipline;
    }
}
