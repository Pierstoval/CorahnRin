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
use CorahnRin\Entity\Character;
use CorahnRin\Entity\CharacterProperties\CharDisciplines;
use CorahnRin\Entity\Discipline;
use CorahnRin\Exception\WronglySpentXpException;
use Symfony\Component\Validator\Constraints as Assert;

class DisciplinesSpendXpDTO
{
    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $craft = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $closeCombat = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $stealth = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $magience = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $naturalEnvironment = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $demorthenMysteries = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $occultism = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $perception = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $prayer = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $feats = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $relation = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $performance = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $science = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $shootingAndThrowing = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $travel = [];

    /**
     * @var array<DisciplineDomainScoreSpendXpDTO>
     *
     * @Assert\NotBlank(groups={"base"})
     * @Assert\All(groups={"base"}, constraints={
     *     @Assert\Type(DisciplineDomainScoreSpendXpDTO::class)
     * })
     */
    public $erudition = [];

    /**
     * @var null|array<string, array<DisciplineDomainScoreSpendXpDTO>>
     */
    private $baseData;

    private function __construct()
    {
    }

    public static function fromCharacter(Character $character): self
    {
        $self = new self();

        foreach ($character->getDisciplines() as $key => $discipline) {
            /** @var CharDisciplines $discipline */
            $domain = DomainsData::getShortName($discipline->getDomain());
            $self->{$domain}[] = DisciplineDomainScoreSpendXpDTO::fromCharDiscipline($discipline);
        }

        return $self;
    }

    /**
     * @return array<string, array<DisciplineDomainScoreSpendXpDTO>>
     */
    public function getAllByDomains(): array
    {
        $data = [];

        foreach (DomainsData::getShortNames() as $domain) {
            $data[$domain] = [];

            foreach ($this->{$domain} as $dto) {
                $data[$domain][] = $dto;
            }
        }

        return $data;
    }

    public function addDisciplinesForDomain(string $domain, Discipline $discipline): void
    {
        $shortName = DomainsData::getShortName($domain);
        if (!$this->hasDisciplineForDomain($shortName, $discipline)) {
            $this->{$shortName}[] = DisciplineDomainScoreSpendXpDTO::fromDisciplineAndDomain(
                $domain,
                $discipline
            );
        }
    }

    public function makeSnapshot(): void
    {
        $this->baseData = [];

        foreach (DomainsData::getShortNames() as $domain) {
            $this->baseData[$domain] = [];

            foreach ($this->{$domain} as $dto) {
                $this->baseData[$domain][] = clone $dto;
            }
        }
    }

    public function getSpentXp(DomainsSpendXpDTO $domainsDTO): int
    {
        $spent = 0;

        foreach (DomainsData::getShortNames() as $domain) {
            foreach ($this->{$domain} as $dto) {
                /** @var DisciplineDomainScoreSpendXpDTO $dto */
                $baseValue = $this->getBaseValue($domain, $dto->discipline);

                if (0 === $dto->score || $dto->score === $baseValue) {
                    continue;
                }

                if (5 !== $domainsDTO->{$domain}) {
                    throw new WronglySpentXpException('character_spend_xp.form.tried_discipline_for_invalid_domain_score', [
                        '%current_score%' => $domainsDTO->{$domain},
                        '%min_required_score%' => 5,
                    ]);
                }

                if (
                    $dto->score < $baseValue
                    || $dto->score > 10
                    || $dto->score < 0
                ) {
                    throw new WronglySpentXpException('character_spend_xp.form.score_not_in_range');
                }

                for ($i = ($baseValue + 1); $i <= $dto->score; $i++) {
                    // Book 1 p229:
                    // Disciplines from  6 to 10 = 25 XP (20 with mentor, but not supported yet)
                    // Disciplines from 11 to 15 = 40 XP (30 with mentor, but not supported yet)
                    if (0 < $i && $i <= 5) {
                        $spent += 25;
                    } elseif (5 < $i && $i <= 10) {
                        $spent += 40;
                    }
                }
            }
        }

        return $spent;
    }

    public function sanitize(): void
    {
        foreach (DomainsData::getShortNames() as $domain) {
            foreach ($this->{$domain} as $k => $dto) {
                if (0 === $dto->score) {
                    unset($this->{$domain}[$k]);
                }
            }
        }
    }

    private function getBaseValue(string $domain, Discipline $discipline): int
    {
        if (null === $this->baseData) {
            throw new \RuntimeException(\sprintf(
                'Cannot calculate spent xp if there is no base data. Did you forget to call the %s::%s method?',
                self::class,
                'makeSnapshot'
            ));
        }

        foreach ($this->baseData[$domain] as $dto) {
            /** @var DisciplineDomainScoreSpendXpDTO $dto */
            if ($dto->discipline->getId() === $discipline->getId()) {
                return $dto->score;
            }
        }

        throw new \RuntimeException(\sprintf('Base value for domain "%s" and discipline "%s" is inaccessible. Maybe you tried to reach an undefined discipline, or the base values snapshot is invalid.', $domain, $discipline->getId().' - '.$discipline->getName()));
    }

    private function hasDisciplineForDomain(string $domainShortName, Discipline $discipline): bool
    {
        $id = $discipline->getId();

        foreach ($this->{$domainShortName} as $existingDiscipline) {
            /** @var DisciplineDomainScoreSpendXpDTO $existingDiscipline */
            if ($existingDiscipline->discipline->getId() === $id) {
                return true;
            }
        }

        return false;
    }
}
