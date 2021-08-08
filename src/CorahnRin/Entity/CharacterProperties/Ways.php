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

use CorahnRin\Data\Ways as WaysData;
use CorahnRin\DTO\WaysDTO;
use CorahnRin\Entity\Character;
use CorahnRin\Exception\InvalidWay;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="characters_ways")
 */
class Ways
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Character
     *
     * @ORM\OneToOne(targetEntity="CorahnRin\Entity\Character", mappedBy="ways")
     * @ORM\JoinColumn(name="character_id", referencedColumnName="id", nullable=true, unique=true)
     */
    protected $character;

    /**
     * @var int
     *
     * @ORM\Column(name="combativeness", type="smallint")
     */
    protected $combativeness;

    /**
     * @var int
     *
     * @ORM\Column(name="creativity", type="smallint")
     */
    protected $creativity;

    /**
     * @var int
     *
     * @ORM\Column(name="empathy", type="smallint")
     */
    protected $empathy;

    /**
     * @var int
     *
     * @ORM\Column(name="reason", type="smallint")
     */
    protected $reason;

    /**
     * @var int
     *
     * @ORM\Column(name="conviction", type="smallint")
     */
    protected $conviction;

    private function __construct()
    {
    }

    public static function create(
        Character $character,
        int $combativeness,
        int $creativity,
        int $empathy,
        int $reason,
        int $conviction
    ): self {
        $self = new self();

        $self->character = $character;
        $self->combativeness = $combativeness;
        $self->creativity = $creativity;
        $self->empathy = $empathy;
        $self->reason = $reason;
        $self->conviction = $conviction;

        return $self;
    }

    public static function createFromSession(Character $character, WaysDTO $dto): self
    {
        return self::create(
            $character,
            $dto->getCombativeness(),
            $dto->getCreativity(),
            $dto->getEmpathy(),
            $dto->getReason(),
            $dto->getConviction()
        );
    }

    public function getWay(string $way): int
    {
        WaysData::validateWay($way);

        switch ($way) {
            case WaysData::COMBATIVENESS:
                return $this->combativeness;

            case WaysData::CREATIVITY:
                return $this->creativity;

            case WaysData::EMPATHY:
                return $this->empathy;

            case WaysData::REASON:
                return $this->reason;

            case WaysData::CONVICTION:
                return $this->conviction;

            default:
                throw new InvalidWay($way);
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public function getCombativeness(): int
    {
        return $this->combativeness;
    }

    public function getCreativity(): int
    {
        return $this->creativity;
    }

    public function getEmpathy(): int
    {
        return $this->empathy;
    }

    public function getReason(): int
    {
        return $this->reason;
    }

    public function getConviction(): int
    {
        return $this->conviction;
    }
}
