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

namespace CorahnRin\DTO\SpendXp;

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\CharacterProperties\CharDisciplines;
use CorahnRin\Entity\Discipline;
use Symfony\Component\Validator\Constraints as Assert;

class DisciplineDomainScoreSpendXpDTO
{
    /**
     * @var Discipline
     *
     * @Assert\NotBlank
     * @Assert\Type(Discipline::class)
     */
    public $discipline;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Choice(DomainsData::CHOICES)
     */
    public $domain;

    /**
     * @var int
     *
     * @Assert\Type("numeric")
     * @Assert\Range(min="0", max="10")
     */
    public $score;

    public static function fromCharDiscipline(CharDisciplines $discipline): self
    {
        $self = new self();

        DomainsData::validateDomain($discipline->getDomain());

        $self->discipline = $discipline->getDiscipline();
        $self->domain = $discipline->getDomain();
        $self->score = $discipline->getScore() - 5;

        return $self;
    }

    public static function fromDisciplineAndDomain(string $domain, Discipline $discipline): self
    {
        $self = new self();

        DomainsData::validateDomain($domain);

        $self->discipline = $discipline;
        $self->domain = $domain;
        $self->score = 0;

        return $self;
    }
}
