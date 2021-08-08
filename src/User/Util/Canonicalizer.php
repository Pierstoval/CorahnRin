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

namespace User\Util;

final class Canonicalizer
{
    public static function urlize(string $string): string
    {
        if (!$string) {
            return '';
        }

        $encoding = \mb_detect_encoding($string, \mb_detect_order(), true);

        return $encoding
            ? \mb_convert_case($string, \MB_CASE_LOWER, $encoding)
            : \mb_convert_case($string, \MB_CASE_LOWER);
    }
}
