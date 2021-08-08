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

class LegacyCharacterUserMandatoryProcessorException extends StopLegacyCharacterProcessingException
{
    private $legacyUsername;
    private $legacyEmail;

    public function __construct(string $processorClass, string $legacyUsername, string $legacyEmail)
    {
        $this->legacyUsername = $legacyUsername;
        $this->legacyEmail = $legacyEmail;
        parent::__construct($processorClass, $this->getFormattedMessage());
    }

    public function getFormattedMessage(): string
    {
        return <<<MSG
        Legacy character contains an user that does not match the new app:
        Legacy username: {$this->legacyUsername}
        Legacy email: {$this->legacyEmail}
        MSG;
    }
}
