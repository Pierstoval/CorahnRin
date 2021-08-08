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

namespace User\Security;

final class UsernamePasswordCredentials
{
    private $usernameOrEmail;
    private $password;

    private function __construct()
    {
    }

    public static function create(string $usernameOrEmail, string $password): self
    {
        $obj = new self();

        $obj->usernameOrEmail = $usernameOrEmail;
        $obj->password = $password;

        return $obj;
    }

    public function getUsernameOrEmail(): string
    {
        return $this->usernameOrEmail;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
