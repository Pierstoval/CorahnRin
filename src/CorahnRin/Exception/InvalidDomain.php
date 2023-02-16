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

use CorahnRin\Data\DomainsData;

class InvalidDomain extends \InvalidArgumentException
{
    public function __construct(string $domain, array $values = null)
    {
        if (null === $values) {
            $values = \array_keys(DomainsData::ALL);
        }

        parent::__construct(\sprintf(
            'Provided domain "%s" is not a valid domain. Possible values: %s',
            $domain,
            \implode(', ', $values)
        ));
    }
}
