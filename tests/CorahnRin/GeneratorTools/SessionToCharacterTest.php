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

use CorahnRin\Entity\Character;
use CorahnRin\Exception\CharacterException;
use CorahnRin\GeneratorTools\SessionToCharacter;
use Doctrine\Common\Collections\Collection;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class SessionToCharacterTest extends KernelTestCase
{
    /** @var null|PropertyAccessor */
    private static $propertyAccessor;

    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::$propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public static function tearDownAfterClass(): void
    {
        static::ensureKernelShutdown();
        static::$propertyAccessor = null;
    }

    /**
     * @group integration
     */
    public function test unfinished character generation(): void
    {
        $this->expectException(CharacterException::class);
        $this->expectExceptionMessage('Character error: Generator seems not to be fully finished');

        static::getCharacterFromValues([]);
    }

    /**
     * @dataProvider provideCharacterFiles
     * @group integration
     */
    public function test base working characters(array $values, array $expectedValues): void
    {
        // This one is just here as a smoke test,
        // just like the FullValidStepsControllerTest class.
        $character = static::getCharacterFromValues($values);

        $propertyAccessor = static::$propertyAccessor;

        $getValue = static function (string $propertyPath) use ($character, $propertyAccessor) {
            return $propertyAccessor->getValue($character, $propertyPath);
        };

        foreach ($expectedValues as $data) {
            $value = $getValue($data['property_path']);
            if ($value instanceof Collection) {
                $value = $value->toArray();
            }
            static::assertEquals($data['value'], $value, \sprintf(
                'Property path "%s" expected value "%s" but got "%s" instead',
                $data['property_path'],
                \var_export($data['value'], true),
                \var_export($value, true)
            ));
        }
    }

    public function provideCharacterFiles(): Generator
    {
        /** @var Finder|SplFileInfo[] $files */
        $files = (new Finder())->name('*.php')->in(__DIR__.'/session_to_character_tests/');

        foreach ($files as $file) {
            $fileData = require $file;
            yield $file->getBasename('.php') => [$fileData['values'], $fileData['expected_values']];
        }
    }

    public static function getCharacterFromValues(array $values): Character
    {
        return self::createInstance()->createCharacterFromGeneratorValues($values, null);
    }

    private static function createInstance(): SessionToCharacter
    {
        static::bootKernel();

        return static::$container->get(SessionToCharacter::class);
    }
}
