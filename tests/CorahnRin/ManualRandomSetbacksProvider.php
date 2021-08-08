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

namespace Tests\CorahnRin;

use CorahnRin\GeneratorTools\RandomSetbacksProvider;

/**
 * This provider replaces the RandomSetbacksProvider in test so we can use it instead of the native one.
 *
 * The goal is to be able to control the "dice roll" that picks random setbacks.
 *
 * The default behavior of the RandomSetbacksProvider is to array_shift() a list of setbacks previously shuffled.
 *
 * By setting "custom setbacks to pick", they will be selected one by one without shuffling them,
 * so we can have a total control over what setbacks are picked.
 */
class ManualRandomSetbacksProvider extends RandomSetbacksProvider
{
    private ?array $customSetbacksToPick = null;

    public function getRandomSetbacks(array $setbacksToSearchIn, int $numberOfSetbacksToPick): array
    {
        return parent::getRandomSetbacks($this->customSetbacksToPick ?: $setbacksToSearchIn, $numberOfSetbacksToPick);
    }

    public function setCustomSetbacksToPick(array $customSetbacksToPick): void
    {
        $this->customSetbacksToPick = $customSetbacksToPick;
    }

    protected function shuffle(array $setbacksDiceList): array
    {
        if (!$this->customSetbacksToPick) {
            return parent::shuffle($setbacksDiceList);
        }

        return $this->customSetbacksToPick;
    }
}
