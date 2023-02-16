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

namespace CorahnRin\Exception;

use CorahnRin\Entity\CharacterProperties\Bonuses;

class InvalidBonus extends \InvalidArgumentException
{
    public function __construct(string $domain)
    {
        parent::__construct(\sprintf(
            'Provided bonus "%s" is not valid. Possible values: %s',
            $domain,
            \implode(', ', Bonuses::ALL)
        ));
    }
}
