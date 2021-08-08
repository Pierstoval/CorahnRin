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

class IconFromPathExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon_from_path', [$this, 'iconFromPath']),
        ];
    }

    public function iconFromPath(string $path): string
    {
        $extension = \pathinfo($path, \PATHINFO_EXTENSION);

        if (\in_array($extension, ['png', 'jpeg', 'gif'], true)) {
            return 'fa fa-image';
        }

        if ('pdf' === $extension) {
            return 'fa fa-file-pdf';
        }

        if ('zip' === $extension) {
            return 'fa fa-file-archive';
        }

        return 'fa fa-file';
    }
}
