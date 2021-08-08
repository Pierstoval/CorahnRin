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

class ProcessorException extends \RuntimeException
{
    private $processorClass;

    public function __construct(string $processorClass, string $message)
    {
        $this->processorClass = $processorClass;
        parent::__construct($message);
    }

    public function getProcessorClass(): string
    {
        return $this->processorClass;
    }
}
