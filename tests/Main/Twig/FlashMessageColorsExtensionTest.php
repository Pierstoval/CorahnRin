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

namespace Tests\Main\Twig;

use Main\Twig\FlashMessageColorsExtension;
use PHPUnit\Framework\TestCase;

class FlashMessageColorsExtensionTest extends TestCase
{
    /**
     * @dataProvider provide flash messages
     *
     * @group unit
     */
    public function test flash messages classes(string $expected, string $input): void
    {
        static::assertSame($expected, $this->getExtension()->getFlashClass($input));
    }

    public function provide flash messages()
    {
        yield ['card-panel red lighten-3 red-text text-darken-4', 'alert'];

        yield ['card-panel red lighten-3 red-text text-darken-4', 'error'];

        yield ['card-panel red lighten-3 red-text text-darken-4', 'danger'];

        yield ['card-panel orange lighten-3 orange-text text-darken-4', 'warning'];

        yield ['card-panel teal lighten-4 teal-text text-darken-3', 'info'];

        yield ['card-panel green lighten-3 green-text text-darken-4', 'success'];

        yield ['card-panel ', 'not_implemented_should_return_empty_string'];
    }

    private function getExtension()
    {
        return new FlashMessageColorsExtension();
    }
}
