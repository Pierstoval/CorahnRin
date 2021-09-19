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

use CorahnRin\Document\Character;
use CorahnRin\Document\CharacterProperties\CharacterMiracle;
use CorahnRin\Document\Miracle;
use CorahnRin\Document\Ogham;
use CorahnRin\Exception\WronglySpentXpException;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @Assert\GroupSequence({"CharacterSpendXpDTO", "base", "validate-xp"})
 */
class CharacterSpendXpDTO
{
    /**
     * Should be used with self::getSpentXp() when the DTO is considered valid.
     */
    public const SAVE_XP = true;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="5",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.speed"
     * )
     * @Assert\GreaterThanOrEqual(propertyPath="baseSpeed", groups={"base"})
     */
    public $speed = 0;

    /**
     * @Assert\Range(
     *     min="0",
     *     max="10",
     *     groups={"base"},
     *     notInRangeMessage="corahn_rin.character_spend_xp.defense"
     * )
     * @Assert\GreaterThanOrEqual(propertyPath="baseDefense", groups={"base"})
     */
    public $defense = 0;

    /**
     * @Assert\Valid(groups={"base"})
     *
     * @var DomainsSpendXpDTO
     */
    public $domains;

    /**
     * @Assert\Valid(groups={"base"})
     *
     * @var DisciplinesSpendXpDTO
     */
    public $disciplines;

    /**
     * @var Ogham[]
     */
    public $baseOgham = [];

    /**
     * @var CharacterMiracle[]
     */
    public $baseMiracles = [];

    /**
     * @var Ogham[]
     */
    public $ogham = [];

    /**
     * @var array<string, array<Miracle>>
     */
    public $miracles = [];

    /** @var DomainsSpendXpDTO */
    private $baseDomains;
    /** @var DisciplinesSpendXpDTO */
    private $baseDisciplines;

    private $baseSpeed = 0;
    private $baseDefense = 0;
    private $baseXp = 0;
    private $spentXp;

    /** @var Character */
    private $character;

    private function __construct()
    {
    }

    public function getCharacter(): Character
    {
        return $this->character;
    }

    public static function fromCharacter(Character $character): self
    {
        $object = new self();

        $object->character = $character;

        // Base will be necessary to validate the DTO
        $object->baseXp = $character->getExperienceActual();
        $object->baseSpeed = $character->getSpeedBonus();
        $object->baseDefense = $character->getDefenseBonus();
        $object->baseDomains = DomainsSpendXpDTO::fromCharacter($character);
        $object->baseDisciplines = DisciplinesSpendXpDTO::fromCharacter($character);

        $ogham = $character->getOgham();
        $object->baseOgham = $ogham instanceof Collection ? $ogham->toArray() : $ogham;
        $object->ogham = $object->baseOgham;

        $miracles = $character->getMiracles();
        $object->baseMiracles = $miracles instanceof Collection ? $miracles->toArray() : $miracles;
        $object->miracles = $object->baseMiracles;

        $object->speed = $character->getSpeedBonus();
        $object->defense = $character->getDefenseBonus();
        $object->domains = clone $object->baseDomains;
        $object->disciplines = clone $object->baseDisciplines;

        return $object;
    }

    public function getBaseSpeed(): int
    {
        return $this->baseSpeed;
    }

    public function getBaseDefense(): int
    {
        return $this->baseDefense;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function getDefense(): int
    {
        return $this->defense;
    }

    public function getDomains(): DomainsSpendXpDTO
    {
        return $this->domains;
    }

    /**
     * @throws WronglySpentXpException
     */
    public function getSpentXp(bool $store = false): int
    {
        if (null !== $this->spentXp) {
            return $this->spentXp;
        }

        $spent = 0;

        $spent += $this->getXpSpentForSpeedOrDefense($this->baseDefense, $this->defense);

        $spent += $this->getXpSpentForSpeedOrDefense($this->baseSpeed, $this->speed);

        $spent += $this->domains->getSpentXp();

        $spent += $this->disciplines->getSpentXp($this->domains);

        $spent += $this->getXpSpentForOgham();
        $spent += $this->getXpSpentForMiracles();

        if ($store) {
            $this->spentXp = $spent;
        }

        return $spent;
    }

    /**
     * This callback is totally custom because we need a totally custom group sequence,
     * without messing up with group sequences themselves.
     *
     * @Assert\Callback(groups={"validate-xp"})
     */
    public function validateXp(ExecutionContextInterface $context): void
    {
        try {
            $spentXp = $this->getSpentXp();
        } catch (WronglySpentXpException $e) {
            $context->buildViolation($e->getMessage(), $e->getParameters())
                ->setTranslationDomain('corahn_rin')
                ->addViolation()
            ;

            return;
        }

        if ($spentXp > $this->baseXp) {
            $context->addViolation('😏');

            return;
        }

        $this->sanitize();
    }

    private function getXpSpentForSpeedOrDefense(int $base, int $score): int
    {
        if ($score === $base) {
            return 0;
        }

        $spent = 0;

        for ($i = $base; $i <= $score; $i++) {
            if (0 === $i) {
                continue;
            }
            $spent += (
                $i <= 5
                ? (($i * 5) + 5)
                : 30
            );
        }

        return $spent;
    }

    private function sanitize(): void
    {
        $this->disciplines->sanitize();
    }

    private function getXpSpentForOgham(): int
    {
        $existingIds = [];

        if ($this->baseOgham) {
            $existingIds = \array_reduce($this->baseOgham, static function (array $carry, Ogham $ogham) {
                $carry[$ogham->getId()] = true;

                return $carry;
            }, []);
        }

        $xp = 0;

        foreach ($this->ogham as $ogham) {
            if (isset($existingIds[$ogham->getId()])) {
                // Don't re-spend xp for already possessed ogham.
                continue;
            }

            $xp += 5;
        }

        return $xp;
    }

    private function getXpSpentForMiracles(): int
    {
        if (!$this->miracles) {
            return 0;
        }

        $existingIds = [];

        if ($this->baseMiracles) {
            $existingIds = \array_reduce($this->baseMiracles, static function (array $carry, CharacterMiracle $miracle) {
                $carry[$miracle->getMiracleId()] = true;

                return $carry;
            }, []);
        }

        $xp = 0;

        foreach (['major', 'minor'] as $miracleType) {
            foreach ($this->miracles[$miracleType] as $miracle) {
                if (isset($existingIds[$miracle->getId()])) {
                    // Don't re-spend xp for already possessed miracle.
                    continue;
                }

                $xp += 5;
            }
        }

        return $xp;
    }
}
