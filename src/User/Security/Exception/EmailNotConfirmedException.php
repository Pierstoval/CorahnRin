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

namespace User\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class EmailNotConfirmedException extends AuthenticationException
{
    private const EXCEPTION_MESSAGE = 'security.email_not_confirmed';

    public function __construct()
    {
        parent::__construct(self::EXCEPTION_MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return self::EXCEPTION_MESSAGE;
    }
}
