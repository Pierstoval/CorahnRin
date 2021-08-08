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

namespace CorahnRin\GeneratorTools;

use CorahnRin\Entity\Setback;

class RandomSetbacksProvider
{
    /**
     * Rules are the following:
     * - We shuffle the list to pick a random setback
     * - If it's "unlucky", we pick another non-lucky nor unlucky setback.
     * - If it's "lucky", we pick another non-lucky nor unlucky setback and set it as "avoided".
     * - In both previous cases, we remove all other lucky/unlucky setbacks as we can have only one of these types.
     *
     * @return array<int, array<string, bool|int>>
     */
    public function getRandomSetbacks(array $setbacksToSearchIn, int $numberOfSetbacksToPick): array
    {
        $this->validateSetbacks($setbacksToSearchIn);

        $setbacksCharacterValue = [];

        // Get the whole list in a special var so it can be modified.
        /** @var Setback[] $setbacksDiceList */
        $setbacksDiceList = \array_values($setbacksToSearchIn);

        // Roll the dice (shuffle all setbacks).
        $this->shuffle($setbacksDiceList);

        // A loop is made through all steps until enough setbacks have been set.
        // The advantage of this format is that we can increment the iterator in case we need to pick more setbacks.

        $loopIterationsCount = $numberOfSetbacksToPick;

        while ($loopIterationsCount > 0) {
            /** @var null|Setback $diceResult */
            // Retrieve any setback from the list. It's shuffled anyway.
            // It's removed from the array so we can't have it more than once.
            $diceResult = \array_shift($setbacksDiceList);

            if (!$diceResult) {
                // Means there's no more setbacks in the list.
                // This shouldn't happen very much, unless in tests.
                break;
            }

            if ($diceResult->isUnlucky()) {
                // Unlucky!

                // When character is unlucky, we add two setbacks instead of one

                // Add it to character's setbacks
                $setbacksCharacterValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => false];

                // This will make another loop so another setback will automatically be added to the list.
                $loopIterationsCount += 2;

                $setbacksDiceList = $this->removeLuckyAndUnluckySetbacks($setbacksDiceList);
            } elseif ($diceResult->isLucky()) {
                // Lucky!

                // When character is lucky, we add another setback, but mark it as "avoided".

                // Add "lucky" to list
                $setbacksCharacterValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => false];

                // Remove lucky and unlucky (so we can't pick them twice)
                $setbacksDiceList = $this->removeLuckyAndUnluckySetbacks($setbacksDiceList);

                // Now we determine which setback was avoided
                $diceResult = \array_shift($setbacksDiceList);

                // Then add it and mark it as avoided
                $setbacksCharacterValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => true];
            } else {
                // If not a specific setback (lucky or unlucky),
                // We add it totally normally
                $setbacksCharacterValue[$diceResult->getId()] = ['id' => $diceResult->getId(), 'avoided' => false];
            }
            --$loopIterationsCount;
        }

        return $setbacksCharacterValue;
    }

    protected function shuffle(array $setbacksDiceList): array
    {
        \shuffle($setbacksDiceList);

        return $setbacksDiceList;
    }

    private function removeLuckyAndUnluckySetbacks(array $setbacks): array
    {
        foreach ($setbacks as $k => $setback) {
            if ($setback->isLucky() || $setback->isUnlucky()) {
                unset($setbacks[$k]);
            }
        }

        return $setbacks;
    }

    private function validateSetbacks(array $setbacks): void
    {
        foreach ($setbacks as $setback) {
            if (!$setback instanceof Setback) {
                throw new \InvalidArgumentException(\sprintf(
                    'Expected argument of type "%s", "%s" given',
                    Setback::class,
                    \is_object($setback) ? \get_class($setback) : \gettype($setback)
                ));
            }
        }
    }
}
