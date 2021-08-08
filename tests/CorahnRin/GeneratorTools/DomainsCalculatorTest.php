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

namespace Tests\CorahnRin\GeneratorTools;

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironment;
use CorahnRin\GeneratorTools\DomainsCalculator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class DomainsCalculatorTest extends TestCase
{
    /**
     * @group unit
     */
    public function test calculateFromGeneratorData with wrong step 13 values throws exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid $primaryDomains argument sent. It must be an array of integers, and the array key must correspond to the "domain id" property.');

        $this->getCalculator()->calculateFromGeneratorData(
            [],
            '',
            $this->createGeoEnvironmentStub(),
            []
        );
    }

    /**
     * @group unit
     */
    public function test calculateFromGeneratorData with wrong step 14 values throws exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid $domainsBonuses argument sent. It must be an array of integers, and the array key must correspond to the "domain id" property.');

        $this->getCalculator()->calculateFromGeneratorData(
            [],
            '',
            $this->createGeoEnvironmentStub(),
            \array_fill_keys(\array_keys(DomainsData::ALL), 0),
            []
        );
    }

    /**
     * @group unit
     */
    public function test calculateFromGeneratorData with wrong ost service domain throws exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid $ost argument sent. It must be a valid domain id.');

        $this->getCalculator()->calculateFromGeneratorData(
            [],
            '',
            $this->createGeoEnvironmentStub(),
            \array_fill_keys(\array_keys(DomainsData::ALL), 0)
        );
    }

    /**
     * @dataProvider provideTestsWithoutBonuses
     * @group unit
     */
    public function test calculator with fixtures data(array $arguments, array $expectedValues): void
    {
        $arguments[3] = $this->fillMissingDomains($arguments[3]);

        $values = \call_user_func_array([$this->getCalculator(), 'calculateFromGeneratorData'], $arguments);

        foreach ($expectedValues as $key => $value) {
            static::assertArrayHasKey($key, $values);
            static::assertSame($values[$key], $value, \sprintf('Key "%s" has wrong value.', $key));
        }
    }

    public function provideTestsWithoutBonuses(): \Generator
    {
        /** @var Finder|SplFileInfo[] $files */
        $files = (new Finder())->name('*.php')->in(__DIR__.'/domains_calculator_tests_without_bonuses/');

        foreach ($files as $file) {
            $fileData = require $file;
            yield $file->getBasename('.php') => [$fileData['calculator_arguments'], $fileData['expected_values']];
        }
    }

    private function fillMissingDomains(array $step13DomainsToFill): array
    {
        foreach (DomainsData::ALL as $domain => $data) {
            if (!\array_key_exists($domain, $step13DomainsToFill)) {
                $step13DomainsToFill[$domain] = 0;
            }
        }

        return $step13DomainsToFill;
    }

    private function getCalculator(): DomainsCalculator
    {
        return new DomainsCalculator();
    }

    private function createGeoEnvironmentStub(): GeoEnvironment
    {
        return new GeoEnvironment(0, '', '', 'domains.close_combat');
    }
}
