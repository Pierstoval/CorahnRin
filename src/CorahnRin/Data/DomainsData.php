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

namespace CorahnRin\Data;

use CorahnRin\Exception\InvalidDomain;
use CorahnRin\Exception\InvalidDomainValue;

final class DomainsData
{
    // Old ID : 1
    public const CRAFT = [
        'way' => Ways::CREATIVITY,
        'short_name' => 'craft',
        'title' => 'domains.craft',
        'description' => 'domains.craft.description',
    ];

    // Old ID : 2
    public const CLOSE_COMBAT = [
        'way' => Ways::COMBATIVENESS,
        'short_name' => 'closeCombat',
        'title' => 'domains.close_combat',
        'description' => 'domains.close_combat.description',
    ];

    // Old ID : 3
    public const STEALTH = [
        'way' => Ways::EMPATHY,
        'short_name' => 'stealth',
        'title' => 'domains.stealth',
        'description' => 'domains.stealth.description',
    ];

    // Old ID : 4
    public const MAGIENCE = [
        'way' => Ways::REASON,
        'short_name' => 'magience',
        'title' => 'domains.magience',
        'description' => 'domains.magience.description',
    ];

    // Old ID : 5
    public const NATURAL_ENVIRONMENT = [
        'way' => Ways::EMPATHY,
        'short_name' => 'naturalEnvironment',
        'title' => 'domains.natural_environment',
        'description' => 'domains.natural_environment.description',
    ];

    // Old ID : 6
    public const DEMORTHEN_MYSTERIES = [
        'way' => Ways::EMPATHY,
        'short_name' => 'demorthenMysteries',
        'title' => 'domains.demorthen_mysteries',
        'description' => 'domains.demorthen_mysteries.description',
    ];

    // Old ID : 7
    public const OCCULTISM = [
        'way' => Ways::REASON,
        'short_name' => 'occultism',
        'title' => 'domains.occultism',
        'description' => 'domains.occultism.description',
    ];

    // Old ID : 8
    public const PERCEPTION = [
        'way' => Ways::REASON,
        'short_name' => 'perception',
        'title' => 'domains.perception',
        'description' => 'domains.perception.description',
    ];

    // Old ID : 9
    public const PRAYER = [
        'way' => Ways::CONVICTION,
        'short_name' => 'prayer',
        'title' => 'domains.prayer',
        'description' => 'domains.prayer.description',
    ];

    // Old ID : 10
    public const FEATS = [
        'way' => Ways::COMBATIVENESS,
        'short_name' => 'feats',
        'title' => 'domains.feats',
        'description' => 'domains.feats.description',
    ];

    // Old ID : 11
    public const RELATION = [
        'way' => Ways::EMPATHY,
        'short_name' => 'relation',
        'title' => 'domains.relation',
        'description' => 'domains.relation.description',
    ];

    // Old ID : 12
    public const PERFORMANCE = [
        'way' => Ways::CREATIVITY,
        'short_name' => 'performance',
        'title' => 'domains.performance',
        'description' => 'domains.performance.description',
    ];

    // Old ID : 13
    public const SCIENCE = [
        'way' => Ways::REASON,
        'short_name' => 'science',
        'title' => 'domains.science',
        'description' => 'domains.science.description',
    ];

    // Old ID : 14
    public const SHOOTING_AND_THROWING = [
        'way' => Ways::COMBATIVENESS,
        'short_name' => 'shootingAndThrowing',
        'title' => 'domains.shooting_and_throwing',
        'description' => 'domains.shooting_and_throwing.description',
    ];

    // Old ID : 15
    public const TRAVEL = [
        'way' => Ways::EMPATHY,
        'short_name' => 'travel',
        'title' => 'domains.travel',
        'description' => 'domains.travel.description',
    ];

    // Old ID : 16
    public const ERUDITION = [
        'way' => Ways::REASON,
        'short_name' => 'erudition',
        'title' => 'domains.erudition',
        'description' => 'domains.erudition.description',
    ];

    public const ALL = [
        self::CRAFT['title'] => self::CRAFT,
        self::CLOSE_COMBAT['title'] => self::CLOSE_COMBAT,
        self::STEALTH['title'] => self::STEALTH,
        self::MAGIENCE['title'] => self::MAGIENCE,
        self::NATURAL_ENVIRONMENT['title'] => self::NATURAL_ENVIRONMENT,
        self::DEMORTHEN_MYSTERIES['title'] => self::DEMORTHEN_MYSTERIES,
        self::OCCULTISM['title'] => self::OCCULTISM,
        self::PERCEPTION['title'] => self::PERCEPTION,
        self::PRAYER['title'] => self::PRAYER,
        self::FEATS['title'] => self::FEATS,
        self::RELATION['title'] => self::RELATION,
        self::PERFORMANCE['title'] => self::PERFORMANCE,
        self::SCIENCE['title'] => self::SCIENCE,
        self::SHOOTING_AND_THROWING['title'] => self::SHOOTING_AND_THROWING,
        self::TRAVEL['title'] => self::TRAVEL,
        self::ERUDITION['title'] => self::ERUDITION,
    ];

    public const CHOICES = [
        self::CRAFT['title'] => self::CRAFT['title'],
        self::CLOSE_COMBAT['title'] => self::CLOSE_COMBAT['title'],
        self::STEALTH['title'] => self::STEALTH['title'],
        self::MAGIENCE['title'] => self::MAGIENCE['title'],
        self::NATURAL_ENVIRONMENT['title'] => self::NATURAL_ENVIRONMENT['title'],
        self::DEMORTHEN_MYSTERIES['title'] => self::DEMORTHEN_MYSTERIES['title'],
        self::OCCULTISM['title'] => self::OCCULTISM['title'],
        self::PERCEPTION['title'] => self::PERCEPTION['title'],
        self::PRAYER['title'] => self::PRAYER['title'],
        self::FEATS['title'] => self::FEATS['title'],
        self::RELATION['title'] => self::RELATION['title'],
        self::PERFORMANCE['title'] => self::PERFORMANCE['title'],
        self::SCIENCE['title'] => self::SCIENCE['title'],
        self::SHOOTING_AND_THROWING['title'] => self::SHOOTING_AND_THROWING['title'],
        self::TRAVEL['title'] => self::TRAVEL['title'],
        self::ERUDITION['title'] => self::ERUDITION['title'],
    ];

    public static function validateDomain(string $domain): void
    {
        if (!self::isDomainValid($domain)) {
            throw new InvalidDomain($domain);
        }
    }

    public static function validateShortDomain(string $shortDomain): void
    {
        $valid = false;

        foreach (self::ALL as $domain) {
            if ($domain['short_name'] === $shortDomain) {
                $valid = true;

                break;
            }
        }

        if (!$valid) {
            throw new InvalidDomain($shortDomain, self::getShortNames());
        }
    }

    public static function isDomainValid(string $domain): bool
    {
        return isset(self::ALL[$domain]);
    }

    public static function validateDomainBaseValue(string $domain, int $value): void
    {
        self::validateDomain($domain);

        if ($value < 0 || $value > 5) {
            throw new InvalidDomainValue($domain);
        }
    }

    public static function getAsObject(string $name): DomainItem
    {
        self::validateDomain($name);

        $item = self::ALL[$name];

        return new DomainItem($item['title'], $item['short_name'], $item['description'], $item['way']);
    }

    public static function shortNameToTitle(string $shortName): string
    {
        self::validateShortDomain($shortName);

        foreach (self::ALL as $name => $item) {
            if ($item['short_name'] === $shortName) {
                return $name;
            }
        }

        throw new \RuntimeException('Should never happen.');
    }

    public static function getShortName(string $domain): string
    {
        self::validateDomain($domain);

        return self::ALL[$domain]['short_name'];
    }

    /**
     * @return DomainItem[]
     */
    public static function allAsObjects(): array
    {
        $collection = [];

        foreach (self::ALL as $item) {
            $collection[$item['title']] = self::getAsObject($item['title']);
        }

        return $collection;
    }

    /**
     * @return string[]
     */
    public static function getShortNames(): array
    {
        $shortNames = [];

        foreach (self::ALL as $item) {
            $shortNames[] = $item['short_name'];
        }

        return $shortNames;
    }
}
