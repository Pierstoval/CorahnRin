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

use CorahnRin\DTO\AdvantageDTO;
use CorahnRin\Entity\Advantage;
use CorahnRin\Entity\Character;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="characters_avantages")
 * @ORM\Entity
 */
class CharacterAdvantageItem
{
    /**
     * @var Character
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Character", inversedBy="advantages")
     */
    protected $character;

    /**
     * @var Advantage
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Advantage")
     */
    protected $advantage;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    protected $score;

    /**
     * @var string
     *
     * @ORM\Column(name="indication", type="string", length=255)
     */
    protected $indication;

    private function __construct()
    {
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
