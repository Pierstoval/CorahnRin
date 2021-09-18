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

namespace CorahnRin\Document;

use CorahnRin\Data\DomainsData;
use CorahnRin\DTO\CharacterEdit\CharacterEditDTO;
use CorahnRin\DTO\Session\SessionCharacterDTO;
use CorahnRin\DTO\SpendXp\CharacterSpendXpDTO;
use CorahnRin\DTO\SpendXp\DisciplineDomainScoreSpendXpDTO;
use CorahnRin\Document\CharacterProperties\Bonuses;
use CorahnRin\Document\CharacterProperties\CharacterAdvantageItem;
use CorahnRin\Document\CharacterProperties\CharacterDomains;
use CorahnRin\Document\CharacterProperties\CharacterMiracle;
use CorahnRin\Document\CharacterProperties\CharDisciplines;
use CorahnRin\Document\CharacterProperties\CharFlux;
use CorahnRin\Document\CharacterProperties\CharSetbacks;
use CorahnRin\Document\CharacterProperties\HealthCondition;
use CorahnRin\Document\CharacterProperties\Money;
use CorahnRin\Document\CharacterProperties\Ways;
use CorahnRin\Exception\CharacterException;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use EsterenMaps\Id\ZoneId;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use InvalidArgumentException;
use Pierstoval\Bundle\CharacterManagerBundle\Entity\Character as BaseCharacter;
use RuntimeException;
use Symfony\Component\String\Slugger\AsciiSlugger;
use User\Document\User;
use function in_array;
use function sprintf;
use function trim;

/**
 * @ODM\Document(repositoryClass="CorahnRin\Repository\CharactersRepository")
 */
class Character extends BaseCharacter
{
    use TimestampableDocument;

    public const FEMALE = 'character.sex.female';
    public const MALE = 'character.sex.male';

    public const COMBAT_ATTITUDE_STANDARD = 'character.combat_attitude.standard';
    public const COMBAT_ATTITUDE_OFFENSIVE = 'character.combat_attitude.offensive';
    public const COMBAT_ATTITUDE_DEFENSIVE = 'character.combat_attitude.defensive';
    public const COMBAT_ATTITUDE_QUICK = 'character.combat_attitude.quick';
    public const COMBAT_ATTITUDE_MOVEMENT = 'character.combat_attitude.movement';

    public const COMBAT_ATTITUDES = [
        self::COMBAT_ATTITUDE_STANDARD,
        self::COMBAT_ATTITUDE_OFFENSIVE,
        self::COMBAT_ATTITUDE_DEFENSIVE,
        self::COMBAT_ATTITUDE_QUICK,
        self::COMBAT_ATTITUDE_MOVEMENT,
    ];

    /**
     * @ODM\Field(name="id", type="integer", nullable=false)
     * @ODM\Id(type="integer", strategy="INCREMENT")
     */
    private int $id;

    /**
     * @var string
     *
     * @ODM\Field(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ODM\Field(name="name_slug", type="string", nullable=false)
     * @Gedmo\Slug(fields={"name"}, unique=false)
     */
    protected $nameSlug;

    /**
     * @ODM\Field(name="player_name", type="string", nullable=false)
     */
    private string $playerName;

    /**
     * @ODM\Field(name="sex", type="string", nullable=false)
     */
    private string $sex;

    /**
     * @ODM\Field(name="description", type="string")
     */
    private string $description = '';

    /**
     * @ODM\Field(name="story", type="string")
     */
    private string $story = '';

    /**
     * @ODM\Field(name="facts", type="string")
     */
    private string $facts = '';

    /**
     * @var array
     *
     * @ODM\Field(name="inventory", type="collection")
     */
    private array $inventory = [];

    /**
     * @var array
     *
     * @ODM\Field(name="treasures", type="collection")
     */
    private array $treasures = [];

    /**
     * @var Money
     *
     * @ODM\EmbedOne(targetDocument="CorahnRin\Document\CharacterProperties\Money")
     */
    private Money $money;

    /**
     * @ODM\Field(name="orientation", type="string")
     */
    private string $orientation;

    /**
     * @var GeoEnvironment
     *
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\GeoEnvironment")
     */
    private GeoEnvironment $geoLiving;

    /**
     * @ODM\Field(name="temporary_trauma", type="integer")
     */
    private int $temporaryTrauma = 0;

    /**
     * @ODM\Field(name="permanent_trauma", type="integer")
     */
    private int $permanentTrauma = 0;

    /**
     * @ODM\Field(name="hardening", type="integer")
     */
    private int $hardening = 0;

    /**
     * @ODM\Field(name="age", type="integer", nullable=false)
     */
    private int $age = 16;

    /**
     * @ODM\Field(name="mental_resistance_bonus", type="integer")
     */
    private int $mentalResistanceBonus = 0;

    /**
     * @ODM\EmbedOne(targetDocument="CorahnRin\Document\CharacterProperties\Ways")
     */
    private Ways $ways;

    /**
     * @var HealthCondition
     *
     * @ODM\EmbedOne(targetDocument="CorahnRin\Document\CharacterProperties\HealthCondition")
     */
    private HealthCondition $health;

    /**
     * @var HealthCondition
     *
     * @ODM\EmbedOne(targetDocument="CorahnRin\Document\CharacterProperties\HealthCondition")
     */
    private HealthCondition $maxHealth;

    /**
     * @ODM\Field(name="stamina", type="integer")
     */
    private int $stamina = 10;

    /**
     * @ODM\Field(name="stamina_bonus", type="integer")
     */
    private int $staminaBonus = 0;

    /**
     * @ODM\Field(name="survival", type="integer")
     */
    private int $survival = 3;

    /**
     * @ODM\Field(name="speed_bonus", type="integer")
     */
    private int $speedBonus = 0;

    /**
     * @ODM\Field(name="defense_bonus", type="integer")
     */
    private int $defenseBonus = 0;

    /**
     * @ODM\Field(name="rindath", type="integer")
     */
    private int $rindath = 0;

    /**
     * @ODM\Field(name="rindathMax", type="integer")
     */
    private int $rindathMax = 0;

    /**
     * @ODM\Field(name="exaltation", type="integer")
     */
    private int $exaltation = 0;

    /**
     * @ODM\Field(name="exaltation_max", type="integer")
     */
    private int $exaltationMax = 0;

    /**
     * @ODM\Field(name="experience_actual", type="integer")
     */
    private int $experienceActual = 0;

    /**
     * @ODM\Field(name="experience_spent", type="integer")
     */
    private int $experienceSpent = 0;

    /**
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\People")
     */
    private People $people;

    /**
     * This will be used to add some metadata to characters.
     * Such metadata can be for example "is it a Dearg-based character", "is it imported from v1", etc.
     *
     * @ODM\Field(name="tags", type="collection", nullable=true)
     */
    private array $tags = [];

    /**
     * @var array<Armor>|Collection<Armor>
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\Armor")
     */
    private array|Collection $armors;

    /**
     * @var array<MagienceArtifact>|Collection<MagienceArtifact>
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\MagienceArtifact")
     */
    private array|Collection $artifacts;

    /**
     * @var array<CharacterMiracle>|Collection<CharacterMiracle>
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\CharacterProperties\CharacterMiracle")
     */
    private array|Collection $miracles;

    /**
     * @var array<Ogham>|Collection<Ogham>
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\Ogham")
     */
    private array|Collection $ogham;

    /**
     * @var array<Weapon>|Collection<Weapon>
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\Weapon")
     */
    private array|Collection $weapons;

    /**
     * @var array<CombatArt>|Collection<CombatArt>
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\CombatArt")
     */
    private array|Collection $combatArts;

    /**
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\SocialClass")
     */
    private SocialClass $socialClass;

    /**
     * @ODM\Field(name="social_class_domain1", type="string")
     */
    private string $socialClassDomain1;

    /**
     * @ODM\Field(name="social_class_domain2", type="string")
     */
    private string $socialClassDomain2;

    /**
     * @ODM\Field(name="ost_service", type="string")
     */
    private string $ostService;

    /**
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\MentalDisorder")
     */
    private MentalDisorder $mentalDisorder;

    /**
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Job")
     */
    private Job $job;

    /**
     * @ODM\Field(type="zone_id")
     */
    private ZoneId $birthPlace;

    /**
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\PersonalityTrait")
     */
    private PersonalityTrait $flaw;

    /**
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\PersonalityTrait")
     */
    private PersonalityTrait $quality;

    /**
     * @var CharacterAdvantageItem[]|Collection
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\CharacterProperties\CharacterAdvantageItem")
     */
    private array|Collection|ArrayCollection $advantages;

    /**
     * @ODM\EmbedOne(targetDocument="CorahnRin\Document\CharacterProperties\CharacterDomains")
     */
    private CharacterDomains $domains;

    /**
     * @var CharDisciplines[]|Collection
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\CharacterProperties\CharDisciplines")
     */
    private array|Collection|ArrayCollection $disciplines;

    /**
     * @var CharFlux[]|Collection
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\CharacterProperties\CharFlux")
     */
    private array|Collection|ArrayCollection $flux;

    /**
     * @var CharSetbacks[]|Collection
     *
     * @ODM\ReferenceMany(targetDocument="CorahnRin\Document\CharacterProperties\CharSetbacks")
     */
    private array|Collection|ArrayCollection $setbacks;

    /**
     * @var User
     * @ODM\ReferenceOne(targetDocument="User\Document\User")
     */
    private User $user;

    /**
     * @var null|Game
     * @ODM\ReferenceOne(targetDocument="CorahnRin\Document\Game", inversedBy="characters")
     */
    private ?Game $game;

    public function __construct(string $name)
    {
        parent::__construct(trim($name), (new AsciiSlugger())->slug($name)->toString());
        $this->maxHealth = new HealthCondition();
        $this->armors = new ArrayCollection();
        $this->artifacts = new ArrayCollection();
        $this->miracles = new ArrayCollection();
        $this->ogham = new ArrayCollection();
        $this->weapons = new ArrayCollection();
        $this->combatArts = new ArrayCollection();
        $this->advantages = new ArrayCollection();
        $this->disciplines = new ArrayCollection();
        $this->flux = new ArrayCollection();
        $this->setbacks = new ArrayCollection();
    }

    public static function createFromSession(SessionCharacterDTO $characterFromSession): self
    {
        $character = new self($characterFromSession->getName());

        $character->age = $characterFromSession->getAge();
        $character->armors = $characterFromSession->getArmors();
        $character->artifacts = $characterFromSession->getArtifacts();
        $character->birthPlace = $characterFromSession->getBirthPlace();
        $character->combatArts = $characterFromSession->getCombatArts();
        $character->defenseBonus = $characterFromSession->getDefenseBonus();
        $character->description = $characterFromSession->getDescription();
        $character->domains = CharacterDomains::createFromSession($character, $characterFromSession->getDomains());
        $character->exaltationMax = $characterFromSession->getExaltationMax();
        $character->experienceActual = $characterFromSession->getExperienceActual();
        $character->facts = $characterFromSession->getFacts();
        $character->flaw = $characterFromSession->getFlaw();
        $character->geoLiving = $characterFromSession->getGeoLiving();
        $character->health = $characterFromSession->getHealthCondition();
        $character->inventory = $characterFromSession->getInventory();
        $character->job = $characterFromSession->getJob();
        $character->maxHealth = clone $characterFromSession->getHealthCondition();
        $character->mentalDisorder = $characterFromSession->getMentalDisorder();
        $character->mentalResistanceBonus = $characterFromSession->getMentalResistanceBonus();
        $character->money = $characterFromSession->getMoney();
        $character->orientation = $characterFromSession->getOrientation();
        $character->ostService = $characterFromSession->getOstService();
        $character->people = $characterFromSession->getPeople();
        $character->permanentTrauma = $characterFromSession->getPermanentTrauma();
        $character->playerName = $characterFromSession->getPlayerName();
        $character->quality = $characterFromSession->getQuality();
        $character->rindathMax = $characterFromSession->getRindathMax();
        $character->sex = $characterFromSession->getSex();
        $character->socialClass = $characterFromSession->getSocialClass();
        $character->socialClassDomain1 = $characterFromSession->getSocialClassDomain1();
        $character->socialClassDomain2 = $characterFromSession->getSocialClassDomain2();
        $character->speedBonus = $characterFromSession->getSpeedBonus();
        $character->staminaBonus = $characterFromSession->getStaminaBonus();
        $character->story = $characterFromSession->getStory();
        $character->survival = $characterFromSession->getSurvival();
        $character->user = $characterFromSession->getUser();
        $character->ways = Ways::createFromSession($characterFromSession->getWays());
        $character->weapons = $characterFromSession->getWeapons();

        foreach ($characterFromSession->getSetbacks() as $setbackDTO) {
            $character->setbacks->add(CharSetbacks::createFromSessionDTO($character, $setbackDTO));
        }
        foreach ($characterFromSession->getAdvantages() as $advantageDTO) {
            $character->advantages->add(CharacterAdvantageItem::createFromSessionDTO($character, $advantageDTO));
        }
        foreach ($characterFromSession->getDisciplines() as $disciplineDTO) {
            $character->disciplines->add(CharDisciplines::createFromSession($character, $disciplineDTO->getDiscipline(), $disciplineDTO->getDomain()));
        }

        return $character;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPlayerName(): string
    {
        return $this->playerName;
    }

    public function getSex(): string
    {
        return $this->sex;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStory(): string
    {
        return $this->story;
    }

    public function getFacts(): string
    {
        return $this->facts;
    }

    /**
     * @return iterable|string[]
     */
    public function getInventory(): iterable
    {
        return $this->inventory;
    }

    /**
     * @return iterable|string[]
     */
    public function getTreasures(): iterable
    {
        return $this->treasures ?: [];
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getOrientation(): string
    {
        return $this->orientation;
    }

    public function getGeoLiving(): GeoEnvironment
    {
        return $this->geoLiving;
    }

    public function getTemporaryTrauma(): int
    {
        return $this->temporaryTrauma;
    }

    public function getPermanentTrauma(): int
    {
        return $this->permanentTrauma;
    }

    public function getHardening(): int
    {
        return $this->hardening;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function getTags(): array
    {
        return (array) $this->tags;
    }

    public function getMentalResistanceBonus(): int
    {
        return $this->mentalResistanceBonus;
    }

    public function getCombativeness(): int
    {
        return $this->ways->getCombativeness();
    }

    public function getCreativity(): int
    {
        return $this->ways->getCreativity();
    }

    public function getEmpathy(): int
    {
        return $this->ways->getEmpathy();
    }

    public function getReason(): int
    {
        return $this->ways->getReason();
    }

    public function getConviction(): int
    {
        return $this->ways->getConviction();
    }

    public function getWayScore(string $way): int
    {
        return $this->ways->getWay($way);
    }

    public function getWays(): Ways
    {
        return $this->ways;
    }

    public function getHealth(): HealthCondition
    {
        return $this->health;
    }

    public function getMaxHealth(): HealthCondition
    {
        return $this->maxHealth;
    }

    public function getStamina(): int
    {
        return $this->stamina;
    }

    public function getStaminaBonus(): int
    {
        return $this->staminaBonus;
    }

    public function getSurvival(): int
    {
        return $this->survival;
    }

    public function getSpeedBonus(): int
    {
        return $this->speedBonus;
    }

    public function getDefenseBonus(): int
    {
        return $this->defenseBonus;
    }

    public function getRindath(): int
    {
        return $this->rindath;
    }

    public function getRindathMax(): int
    {
        return $this->rindathMax;
    }

    public function getExaltation(): int
    {
        return $this->exaltation;
    }

    public function getExaltationMax(): int
    {
        return $this->exaltationMax;
    }

    public function getExperienceActual(): int
    {
        return $this->experienceActual;
    }

    public function getExperienceSpent(): int
    {
        return $this->experienceSpent;
    }

    public function getPeople(): People
    {
        return $this->people;
    }

    public function getArmors(): iterable
    {
        return $this->armors;
    }

    public function getArtifacts(): iterable
    {
        return $this->artifacts;
    }

    /**
     * @return array<CharacterMiracle>|Collection<CharacterMiracle>
     */
    public function getMiracles(): iterable
    {
        return $this->miracles;
    }

    /**
     * @return array<Ogham>|Collection<Ogham>
     */
    public function getOgham(): iterable
    {
        return $this->ogham;
    }

    public function getWeapons(): iterable
    {
        return $this->weapons;
    }

    public function getCombatArts(): iterable
    {
        return $this->combatArts;
    }

    public function getSocialClass(): SocialClass
    {
        return $this->socialClass;
    }

    public function getSocialClassDomain1(): string
    {
        return $this->socialClassDomain1;
    }

    public function getSocialClassDomain2(): string
    {
        return $this->socialClassDomain2;
    }

    public function getOstService(): string
    {
        return $this->ostService;
    }

    public function getMentalDisorder(): MentalDisorder
    {
        return $this->mentalDisorder;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function getBirthPlace(): ZoneId
    {
        return $this->birthPlace;
    }

    public function getFlaw(): PersonalityTrait
    {
        return $this->flaw;
    }

    public function getQuality(): PersonalityTrait
    {
        return $this->quality;
    }

    /**
     * @return CharacterAdvantageItem[]|iterable
     */
    public function getAllAdvantages(): iterable
    {
        return $this->advantages;
    }

    public function getDomains(): CharacterDomains
    {
        return $this->domains;
    }

    /**
     * @return Collection&iterable<CharDisciplines>
     */
    public function getDisciplines(): iterable
    {
        return $this->disciplines;
    }

    /**
     * @return Collection&iterable<CharFlux>
     */
    public function getFlux(): iterable
    {
        return $this->flux;
    }

    /**
     * @return Collection&iterable<CharSetbacks>
     */
    public function getSetbacks(): iterable
    {
        return $this->setbacks;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    // ----------------------------
    // ----------------------------
    // --------- MUTATORS ---------
    // ----------------------------
    // ----------------------------

    public function updateFromSpendingXp(CharacterSpendXpDTO $dto): void
    {
        $this->speedBonus = $dto->getSpeed();
        $this->defenseBonus = $dto->getDefense();

        $this->domains->updateFromSpendingXp($dto->domains);

        foreach ($dto->disciplines->getAllByDomains() as $domain => $dtos) {
            foreach ($dtos as $disciplineDto) {
                /** @var DisciplineDomainScoreSpendXpDTO $disciplineDto */
                if (0 === $disciplineDto->score) {
                    continue;
                }

                $hasDiscipline = false;

                foreach ($this->disciplines as $charDiscipline) {
                    if (
                        $charDiscipline->getDomain() === $disciplineDto->domain
                        && $charDiscipline->getDiscipline()->getId() === $disciplineDto->discipline->getId()
                    ) {
                        $hasDiscipline = true;
                        $charDiscipline->updateScoreFromSpendXp($disciplineDto->score);

                        break;
                    }
                }

                if (!$hasDiscipline) {
                    $charDiscipline = CharDisciplines::create($this, $disciplineDto->discipline, DomainsData::shortNameToTitle($domain), $disciplineDto->score + 5);
                    $this->disciplines[] = $charDiscipline;
                }
            }
        }

        foreach ($dto->ogham as $ogham) {
            $this->ogham[] = $ogham;
        }

        foreach (['major', 'minor'] as $miracleType) {
            if (!isset($dto->miracles[$miracleType])) {
                // Means no miracles have been submitted.
                continue;
            }

            foreach ($dto->miracles[$miracleType] as $miracle) {
                $this->miracles[] = CharacterMiracle::create($this, $miracle, 'major' === $miracleType);
            }
        }

        $spentXp = $dto->getSpentXp();

        $this->experienceSpent += $spentXp;
        $this->experienceActual -= $spentXp;
    }

    public function updateFromEditForm(CharacterEditDTO $data): void
    {
        $this->description = $data->descriptionAndFacts->description ?: '';
        $this->story = $data->descriptionAndFacts->story ?: '';
        $this->facts = $data->descriptionAndFacts->notableFacts ?: '';
        $this->inventory = $data->inventory->getUnicizedItems() ?: [];
        $this->treasures = $data->inventory->getUnicizedPreciousObjects() ?: [];
        $this->updatedAt = new DateTime();
    }

    public function inviteToGame(GameInvitation $invitation): void
    {
        if ($this->game) {
            throw new RuntimeException(sprintf('Character "%s" is already in a game.', $this->name));
        }

        if ($invitation->getCharacterId() !== $this->id) {
            throw new RuntimeException(
                sprintf(
                'Invitation "%s" is for the wrong character. Expected "%s", got "%s" instead.',
                $invitation->getId(),
                $this->nameSlug,
                $invitation->getCharacterNameSlug()
            ));
        }

        $this->game = $invitation->getGame();
    }

    // -------------------------------------------------
    // -------------------------------------------------
    // --------- Methods used for entity logic ---------
    // -------------------------------------------------

    // -------------------------------------------------

    /**
     * @return CharacterAdvantageItem[]
     */
    public function getAdvantages(): array
    {
        $advantages = [];

        foreach ($this->advantages as $charAdvantage) {
            if (!$charAdvantage->getAdvantage()->isDisadvantage()) {
                $advantages[] = $charAdvantage;
            }
        }

        return $advantages;
    }

    /**
     * @return CharacterAdvantageItem[]
     */
    public function getDisadvantages(): array
    {
        $advantages = [];

        foreach ($this->advantages as $charAdvantage) {
            if ($charAdvantage->getAdvantage()->isDisadvantage()) {
                $advantages[] = $charAdvantage;
            }
        }

        return $advantages;
    }

    /**
     * Conscience is determined by "Reason" and "Conviction" ways.
     */
    public function getConsciousness(): int
    {
        return $this->getReason() + $this->getConviction();
    }

    /**
     * Conscience is determined by "Creativity" and "Combativity" ways.
     */
    public function getInstinct(): int
    {
        return $this->getCreativity() + $this->getCombativeness();
    }

    public function getDomain(string $name): int
    {
        return $this->getDomains()->getDomainScore($name);
    }

    /**
     * @return CharDisciplines[]
     */
    public function getDisciplineFromDomain(string $domain): array
    {
        $disciplines = [];

        foreach ($this->disciplines as $discipline) {
            if ($discipline->getDomain() === $domain) {
                $disciplines[] = $discipline;
            }
        }

        return $disciplines;
    }

    public function getDiscipline($id): ?CharDisciplines
    {
        foreach ($this->disciplines as $charDiscipline) {
            $discipline = $charDiscipline->getDiscipline();
            if (
                $charDiscipline instanceof CharDisciplines
                && (($discipline->getId() === (int) $id) || $discipline->getName() === $id)
            ) {
                return $charDiscipline;
            }
        }

        return null;
    }

    /**
     * Base defense is calculated from "Reason" and "Empathy".
     */
    public function getBaseDefense(): int
    {
        return $this->getReason() + $this->getEmpathy() + 5;
    }

    public function getTotalDefense(string $attitude = self::COMBAT_ATTITUDE_STANDARD): int
    {
        self::validateCombatAttitude($attitude);

        $defense = $this->getBaseDefense() + $this->defenseBonus;

        switch ($attitude) {
            case self::COMBAT_ATTITUDE_DEFENSIVE:
            case self::COMBAT_ATTITUDE_MOVEMENT:
                $defense += $this->getPotential();

                break;

            case self::COMBAT_ATTITUDE_OFFENSIVE:
                $defense -= $this->getPotential();

                break;
        }

        return $defense;
    }

    /**
     * Base speed is calculated from "Combativity" and "Empathy".
     */
    public function getBaseSpeed(): int
    {
        return $this->getCombativeness() + $this->getEmpathy();
    }

    public function getTotalSpeed($attitude = self::COMBAT_ATTITUDE_STANDARD): int
    {
        self::validateCombatAttitude($attitude);

        $speed = $this->getBaseSpeed() + $this->speedBonus;

        if (self::COMBAT_ATTITUDE_QUICK === $attitude) {
            $speed += $this->getPotential();
        }

        return $speed;
    }

    public function getBaseMentalResistance(): int
    {
        return $this->getConviction() + 5;
    }

    public function getTotalMentalResistance(): int
    {
        $value = $this->getBaseMentalResistance() + $this->mentalResistanceBonus;

        foreach ($this->getAdvantages() as $advantage) {
            if (in_array(Bonuses::MENTAL_RESISTANCE, $advantage->getBonusesFor(), true)) {
                $value += $advantage->getScore();
            }
        }

        foreach ($this->getDisadvantages() as $disadvantage) {
            if (in_array(Bonuses::MENTAL_RESISTANCE, $disadvantage->getBonusesFor(), true)) {
                $value -= $disadvantage->getScore();
            }
        }

        return $value;
    }

    public function getPotential(): int
    {
        $creativity = $this->getCreativity();

        return match ($creativity) {
            1 => 1,
            2, 3, 4 => 2,
            5 => 3,
            default => throw new CharacterException('Wrong creativity value to calculate potential'),
        };
    }

    public function getMeleeAttackScore(int $discipline = null, string $potentialOperator = ''): int
    {
        return $this->getAttackScore('melee', $discipline, $potentialOperator);
    }

    public function getAttackScore(
        string $type = 'melee',
        int $discipline = null,
        string $attitude = self::COMBAT_ATTITUDE_STANDARD
    ): int {
        self::validateCombatAttitude($attitude);

        // Récupération du score de voie
        $way = $this->getCombativeness();

        if ('melee' === $type) {
            $domain_id = DomainsData::CLOSE_COMBAT['title'];
        } elseif ('ranged' === $type) {
            $domain_id = DomainsData::SHOOTING_AND_THROWING['title'];
        } else {
            throw new CharacterException('Attack can only be "melee" or "ranged".');
        }

        // Récupération du score du domaine
        $domain = $this->getDomain($domain_id);

        // Si on indique une discipline, le score du domaine sera remplacé par le score de discipline
        if (null !== $discipline) {
            $charDiscipline = $this->getDiscipline($discipline);

            // Il faut impérativement que la discipline soit associée au même domaine
            if ($charDiscipline && $charDiscipline->getDomain() === $domain_id) {
                // Remplacement du nouveau score
                $domain = $charDiscipline->getScore();
            }
        }

        $attack = $way + $domain;

        switch ($attitude) {
            case self::COMBAT_ATTITUDE_OFFENSIVE:
                $attack += $this->getPotential();

                break;

            case self::COMBAT_ATTITUDE_DEFENSIVE:
                $attack -= $this->getPotential();

                break;

            case self::COMBAT_ATTITUDE_MOVEMENT:
                $attack = 0;

                break;
        }

        return $attack;
    }

    public function hasAdvantage($id): bool
    {
        $id = (int) $id;

        foreach ($this->advantages as $advantage) {
            if ($advantage->getAdvantage()->getId() === $id) {
                return true;
            }
        }

        return false;
    }

    public function hasSetback($id, $falseIfAvoided = true): bool
    {
        $id = (int) $id;

        foreach ($this->setbacks as $setback) {
            if ($setback->getSetback()->getId() === $id) {
                if ($falseIfAvoided && $setback->isAvoided()) {
                    continue;
                }

                return true;
            }
        }

        return false;
    }

    public function canHaveOghams(): bool
    {
        if (5 !== $this->domains->getDemorthenMysteries()) {
            return false;
        }

        foreach ($this->getDisciplineFromDomain(DomainsData::DEMORTHEN_MYSTERIES['title']) as $discipline) {
            if ('Sigil Rann' === $discipline->getDiscipline()->getName()) {
                // FIXME: this is hardcoded based on database content.
                return true;
            }
        }

        return false;
    }

    public function canHaveMiracles(): bool
    {
        if (5 !== $this->domains->getPrayer()) {
            return false;
        }

        foreach ($this->getDisciplineFromDomain(DomainsData::PRAYER['title']) as $discipline) {
            if ('Miracles' === $discipline->getDiscipline()->getName()) {
                // FIXME: this is hardcoded based on database content.
                return true;
            }
        }

        return false;
    }

    private static function validateCombatAttitude(string $attitude): void
    {
        if (!in_array($attitude, self::COMBAT_ATTITUDES, true)) {
            throw new InvalidArgumentException("Combat attitude is invalid, {$attitude} given.");
        }
    }
}
