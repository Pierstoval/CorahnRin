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

namespace Tests\CorahnRin\Data;

use CorahnRin\Data\DomainsData;
use CorahnRin\Exception\InvalidDomain;
use CorahnRin\Exception\InvalidDomainValue;
use PHPUnit\Framework\TestCase;

class DomainsDataTest extends TestCase
{
    /**
     * @dataProvider provide methods that use validateDomain
     */
    public function test method that use validateDomain throw exception with invalid domain(string $method, array $arguments): void
    {
        $this->expectException(InvalidDomain::class);
        $this->expectExceptionMessage('Provided domain "invalid_domain" is not a valid domain. Possible values: domains.craft, domains.close_combat, domains.stealth, domains.magience, domains.natural_environment, domains.demorthen_mysteries, domains.occultism, domains.perception, domains.prayer, domains.feats, domains.relation, domains.performance, domains.science, domains.shooting_and_throwing, domains.travel, domains.erudition');

        DomainsData::{$method}(...$arguments);
    }

    /**
     * @dataProvider provide valid titles
     */
    public function test validateDomain with valid domain title does not throw(string $title): void
    {
        DomainsData::validateDomain($title);
        self::assertTrue(true);
    }

    public function test validateDomain with invalid domain title throws exception(): void
    {
        $this->expectException(InvalidDomain::class);
        $this->expectExceptionMessage('Provided domain "inexistent_domain" is not a valid domain. Possible values: domains.craft, domains.close_combat, domains.stealth, domains.magience, domains.natural_environment, domains.demorthen_mysteries, domains.occultism, domains.perception, domains.prayer, domains.feats, domains.relation, domains.performance, domains.science, domains.shooting_and_throwing, domains.travel, domains.erudition');

        DomainsData::validateDomain('inexistent_domain');
    }

    /**
     * @dataProvider provide valid titles
     */
    public function test isValid with valid title returns true(string $title): void
    {
        self::assertTrue(DomainsData::isDomainValid($title));
    }

    public function test isValid with invalid domain title returns false(): void
    {
        self::assertFalse(DomainsData::isDomainValid('inexistent_title'));
    }

    /**
     * @dataProvider provide valid short domains
     */
    public function test validateShortDomain with valid short domain does not throw(string $shortDomain): void
    {
        DomainsData::validateShortDomain($shortDomain);
        self::assertTrue(true);
    }

    public function test validateShortDomain with invalid domain throws exception(): void
    {
        $this->expectException(InvalidDomain::class);
        $this->expectExceptionMessage('Provided domain "inexistent_short_domain" is not a valid domain. Possible values: craft, closeCombat, stealth, magience, naturalEnvironment, demorthenMysteries, occultism, perception, prayer, feats, relation, performance, science, shootingAndThrowing, travel, erudition');

        DomainsData::validateShortDomain('inexistent_short_domain');
    }

    /**
     * @dataProvider provide valid domains and valid values
     */
    public function test validateDomainBaseValue with valid domain and valid value does not throw(string $domain, int $value): void
    {
        DomainsData::validateDomainBaseValue($domain, $value);
        self::assertTrue(true);
    }

    /**
     * @dataProvider provide invalid domains and invalid values
     */
    public function test validateDomainBaseValue with invalid value throws exception(string $domain, int $value): void
    {
        $this->expectException(InvalidDomainValue::class);
        $this->expectExceptionMessage(\sprintf('Provided domain "%s" does not have a right value. Expected a value from 0 to 5', $domain));

        DomainsData::validateDomainBaseValue($domain, $value);
    }

    /**
     * @dataProvider provide valid titles
     */
    public function test getAsObject returns DomainItem instance with valid title(string $domain): void
    {
        $object = DomainsData::getAsObject($domain);

        self::assertSame($domain, $object->getTitle());
        $asArray = DomainsData::ALL[$domain];
        self::assertSame($asArray['description'], $object->getDescription());
        self::assertSame($asArray['short_name'], $object->getShortName());
        self::assertSame($asArray['way'], $object->getWay());
    }

    /**
     * @dataProvider provide valid titles and short names
     */
    public function test shortNameToTitle with valid short name returns associated domain title(string $domainTitle, string $shortDomain): void
    {
        self::assertSame($domainTitle, DomainsData::shortNameToTitle($shortDomain));
    }

    /**
     * @dataProvider provide valid titles and short names
     */
    public function test getShortName with valid title returns associated short name(string $domainTitle, string $shortDomain): void
    {
        self::assertSame($shortDomain, DomainsData::getShortName($domainTitle));
    }

    public function test getShortNames returns valid short names(): void
    {
        $names = DomainsData::getShortNames();
        \sort($names);

        self::assertSame(['closeCombat', 'craft', 'demorthenMysteries', 'erudition', 'feats', 'magience', 'naturalEnvironment', 'occultism', 'perception', 'performance', 'prayer', 'relation', 'science', 'shootingAndThrowing', 'stealth', 'travel'], $names);
    }

    public function provide methods that use validateDomain(): \Generator
    {
        $invalidDomainName = 'invalid_domain';

        yield 'validateDomainBaseValue' => ['validateDomainBaseValue', [$invalidDomainName, 0]];
        yield 'getAsObject' => ['getAsObject', [$invalidDomainName]];
        yield 'getShortName' => ['getShortName', [$invalidDomainName]];
    }

    public function provide valid titles(): \Generator
    {
        yield 'domains.craft' => ['domains.craft'];
        yield 'domains.close_combat' => ['domains.close_combat'];
        yield 'domains.stealth' => ['domains.stealth'];
        yield 'domains.magience' => ['domains.magience'];
        yield 'domains.natural_environment' => ['domains.natural_environment'];
        yield 'domains.demorthen_mysteries' => ['domains.demorthen_mysteries'];
        yield 'domains.occultism' => ['domains.occultism'];
        yield 'domains.perception' => ['domains.perception'];
        yield 'domains.prayer' => ['domains.prayer'];
        yield 'domains.feats' => ['domains.feats'];
        yield 'domains.relation' => ['domains.relation'];
        yield 'domains.performance' => ['domains.performance'];
        yield 'domains.science' => ['domains.science'];
        yield 'domains.shooting_and_throwing' => ['domains.shooting_and_throwing'];
        yield 'domains.travel' => ['domains.travel'];
        yield 'domains.erudition' => ['domains.erudition'];
    }

    public function provide valid short domains(): \Generator
    {
        yield 'craft' => ['craft'];
        yield 'closeCombat' => ['closeCombat'];
        yield 'stealth' => ['stealth'];
        yield 'magience' => ['magience'];
        yield 'naturalEnvironment' => ['naturalEnvironment'];
        yield 'demorthenMysteries' => ['demorthenMysteries'];
        yield 'occultism' => ['occultism'];
        yield 'perception' => ['perception'];
        yield 'prayer' => ['prayer'];
        yield 'feats' => ['feats'];
        yield 'relation' => ['relation'];
        yield 'performance' => ['performance'];
        yield 'science' => ['science'];
        yield 'shootingAndThrowing' => ['shootingAndThrowing'];
        yield 'travel' => ['travel'];
        yield 'erudition' => ['erudition'];
    }

    public function provide valid titles and short names(): \Generator
    {
        yield 'domains.craft / craft' => ['domains.craft', 'craft'];
        yield 'domains.close_combat / closeCombat' => ['domains.close_combat', 'closeCombat'];
        yield 'domains.stealth / stealth' => ['domains.stealth', 'stealth'];
        yield 'domains.magience / magience' => ['domains.magience', 'magience'];
        yield 'domains.natural_environment / naturalEnvironment' => ['domains.natural_environment', 'naturalEnvironment'];
        yield 'domains.demorthen_mysteries / demorthenMysteries' => ['domains.demorthen_mysteries', 'demorthenMysteries'];
        yield 'domains.occultism / occultism' => ['domains.occultism', 'occultism'];
        yield 'domains.perception / perception' => ['domains.perception', 'perception'];
        yield 'domains.prayer / prayer' => ['domains.prayer', 'prayer'];
        yield 'domains.feats / feats' => ['domains.feats', 'feats'];
        yield 'domains.relation / relation' => ['domains.relation', 'relation'];
        yield 'domains.performance / performance' => ['domains.performance', 'performance'];
        yield 'domains.science / science' => ['domains.science', 'science'];
        yield 'domains.shooting_and_throwing / shootingAndThrowing' => ['domains.shooting_and_throwing', 'shootingAndThrowing'];
        yield 'domains.travel / travel' => ['domains.travel', 'travel'];
        yield 'domains.erudition / erudition' => ['domains.erudition', 'erudition'];
    }

    public function provide valid domains and valid values(): \Generator
    {
        foreach ($this->provide valid titles() as $title) {
            for ($i = 0; $i <= 5; $i++) {
                yield "{$title[0]}-{$i}" => [$title[0], $i];
            }
        }
    }

    public function provide invalid domains and invalid values(): \Generator
    {
        yield 'domains.craft / -1' => ['domains.craft', -1];
        yield 'domains.craft / 6' => ['domains.craft', 6];
    }
}
