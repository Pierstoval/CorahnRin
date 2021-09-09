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

namespace CorahnRin\Legacy;

use CorahnRin\Legacy\Exception\ConflictingUsersProcessorExceptionCharacter;
use User\Document\User;
use User\Repository\UserRepository;

class UserProviderForProcessors
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserFromLegacy(?string $legacyEmail, ?string $legacyUsername): ?User
    {
        $user = null;

        $userByUsername = $legacyUsername ? $this->userRepository->findOneBy(['username' => $legacyUsername]) : null;
        $userByEmail = $legacyEmail ? $this->userRepository->findOneBy(['email' => $legacyEmail]) : null;

        if (
            $userByEmail && $userByUsername
            && $userByEmail->getId() !== $userByUsername->getId()
        ) {
            // Case when legacy username and legacy email correspond to 2 different users in the new app.
            throw new ConflictingUsersProcessorExceptionCharacter(
                self::class,
                $legacyUsername,
                $legacyEmail,
                $userByUsername->getEmail(),
                $userByEmail->getUsername()
            );
        }

        if ($userByEmail || $userByUsername) {
            // Since we throw an exception if user ids are different, both should be the same here.
            $user = $userByEmail ?: $userByUsername;
        } elseif ($legacyUsername || $legacyEmail) {
            // Create a new user object here.
            // But we must be careful: this user might be changed later.
            $user = new User();

            $user
                ->setUsername($legacyUsername)
                ->setEmail($legacyEmail)
                ->setPlainPassword(\uniqid($legacyUsername.$legacyEmail, true))
            ;
        }

        return $user;
    }
}
