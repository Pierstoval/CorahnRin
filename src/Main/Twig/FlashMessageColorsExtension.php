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

namespace Main\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashMessageColorsExtension extends AbstractExtension
{
    private const BASE_CLASS = 'card-panel';

    private const CLASSES = [
        'alert' => 'red lighten-3 red-text text-darken-4',
        'error' => 'red lighten-3 red-text text-darken-4',
        'danger' => 'red lighten-3 red-text text-darken-4',
        'warning' => 'orange lighten-3 orange-text text-darken-4',
        'info' => 'teal lighten-4 teal-text text-darken-3',
        'success' => 'green lighten-3 green-text text-darken-4',
    ];

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_flash_class', [$this, 'getFlashClass'], ['is_safe' => ['all']]),
        ];
    }

    public function getFlashClass(string $initialClass): string
    {
        return self::BASE_CLASS.' '.(self::CLASSES[$initialClass] ?? '');
    }
}
