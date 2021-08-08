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

namespace CorahnRin\Entity\CharacterProperties;

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\Character;
use CorahnRin\Entity\Discipline;
use Doctrine\ORM\Mapping as ORM;

/**
 * CharDisciplines.
 *
 * @ORM\Table(name="characters_disciplines")
 * @ORM\Entity
 */
class CharDisciplines
{
    /**
     * @var Character
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Character", inversedBy="disciplines")
     */
    protected $character;

    /**
     * @var Discipline
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Discipline")
     */
    protected $discipline;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="domain", type="string", length=100)
     */
    protected $domain;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $score;

    private function __construct(Character $character, Discipline $discipline, string $domain, int $score)
    {
        DomainsData::validateDomain($domain);

        $this->character = $character;
        $this->discipline = $discipline;
        $this->domain = $domain;
        $this->updateScore($score);
    }

    public static function createFromSession(Character $character, Discipline $discipline, string $domain): self
    {
        return self::create($character, $discipline, $domain, 6);
    }

    public static function create(Character $character, Discipline $discipline, string $domain, int $score = 6): self
    {
        return new self($character, $discipline, $domain, $score);
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getDiscipline(): Discipline
    {
        return $this->discipline;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getTotalScore(): int
    {
        $way = DomainsData::ALL[$this->domain]['way'];

        // We have to withdraw 5 because disciplines scores start at 6,
        // and a discipline can be acquired only with a domain score of 5.
        // Therefore, when calculating discipline score, it "does not" use
        // the domain score, since it's implicitly present in the final
        // discipline score.

        return $this->score
            + $this->character->getWayScore($way)
            + $this->character->getDomains()->getDomainScoreWithBonus($this->domain)
            - 5
        ;
    }

    public function updateScoreFromSpendXp(int $score): void
    {
        $this->updateScore($score + 5);
    }

    private function updateScore(int $score): void
    {
        if ($score < 6) {
            throw new \RuntimeException('Discipline score must be at least 6.');
        }

        if ($score > 15) {
            throw new \RuntimeException('Discipline score cannot exceed 15.');
        }

        $this->score = $score;
    }
}
