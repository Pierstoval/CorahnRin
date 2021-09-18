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

use CorahnRin\DTO\AdvantageDTO;
use CorahnRin\Document\Advantage;
use CorahnRin\Document\Character;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class CharacterAdvantageItem
{
    /**
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     */
    private int $id;

    /**
     * @var Character
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Character", inversedBy="advantages")
     */
    private Character $character;

    /**
     * @var Advantage
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Advantage")
     */
    private Advantage $advantage;

    /**
     * @var int
     *
     * @ODM\Field(name="score", type="integer")
     */
    private int $score;

    /**
     * @var string
     *
     * @ODM\Field(name="indication", type="string")
     */
    private string $indication;

    private function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public static function createFromSessionDTO(Character $character, AdvantageDTO $advantageDTO): self
    {
        $score = $advantageDTO->getScore();

        if ($score < 0 || $score > 3) {
            throw new \InvalidArgumentException(\sprintf('An advantage score must be between 1 and 3, got %d.', $score));
        }

        $self = new self();

        $self->character = $character;
        $self->advantage = $advantageDTO->getAdvantage();
        $self->score = $advantageDTO->getScore();
        $self->indication = $advantageDTO->getIndication();

        return $self;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getAdvantage(): Advantage
    {
        return $this->advantage;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getIndication(): string
    {
        return $this->indication;
    }

    public function getBonusesFor(): array
    {
        return $this->advantage->getBonusesFor();
    }
}
