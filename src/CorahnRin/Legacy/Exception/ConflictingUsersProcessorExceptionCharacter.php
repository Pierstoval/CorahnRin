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

namespace CorahnRin\Legacy\Exception;

class ConflictingUsersProcessorExceptionCharacter extends StopLegacyCharacterProcessingException
{
    private $legacyUsername;
    private $legacyEmail;
    private $emailOfUserWithSameUsername;
    private $usernameOfUserWithSameEmail;

    public function __construct(
        string $processorClass,
        string $legacyUsername,
        string $legacyEmail,
        string $emailOfUserWithSameUsername,
        string $usernameOfUserWithSameEmail
    ) {
        $this->legacyUsername = $legacyUsername;
        $this->legacyEmail = $legacyEmail;
        $this->emailOfUserWithSameUsername = $emailOfUserWithSameUsername;
        $this->usernameOfUserWithSameEmail = $usernameOfUserWithSameEmail;

        parent::__construct($processorClass, $this->getFormattedMessage());
    }

    public function getFormattedMessage(): string
    {
        return <<<MSG
        Legacy user contains data that conflict with the users from the new application.
        Legacy username: {$this->legacyUsername}
        Legacy email: {$this->legacyEmail}
        In the new database, these data correspond to different users.
        Email of user with same username: {$this->emailOfUserWithSameUsername}
        Username of user with same email: {$this->usernameOfUserWithSameEmail}
        MSG;
    }
}
