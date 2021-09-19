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

namespace CorahnRin\Document\CharacterProperties;

use CorahnRin\Data\DomainsData;
use CorahnRin\Document\Character;
use CorahnRin\Document\Discipline;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class CharDisciplines
{
    /**
     * @var Character
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Character", inversedBy="disciplines")
     */
    private Character $character;

    /**
     * @var Discipline
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Discipline")
     */
    private Discipline $discipline;

    /**
     * @var string
     *
     * @ODM\Id(type="int", strategy="INCREMENT")
     * @ODM\Field(name="domain", type="string")
     */
    private string $domain;

    /**
     * @var int
     *
     * @ODM\Field(type="int")
     */
    private int $score;

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
