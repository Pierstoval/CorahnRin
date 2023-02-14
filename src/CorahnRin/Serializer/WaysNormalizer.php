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

namespace CorahnRin\Serializer;

use CorahnRin\Data\Ways as WaysData;
use CorahnRin\Entity\CharacterProperties\Ways;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class WaysNormalizer implements DenormalizerInterface, NormalizerInterface
{
    /**
     * @param Ways|array{
     *     "ways.combativeness": int,
     *     "ways.creativity": int,
     *     "ways.empathy": int,
     *     "ways.reason": int,
     *     "ways.conviction": int
     * } $data
     */
    public function denormalize($data, string $type, string $format = null, array $context = []): Ways
    {
        if ($data instanceof Ways) {
            return $data;
        }

        if (!$data || !is_array($data)) {
            throw new UnexpectedValueException(\sprintf(
                'Ways data to denormalize have to be an array or an instance of "%s", "%s" given.',
                Ways::class,
                get_debug_type($data),
            ));
        }

        [
            WaysData::COMBATIVENESS => $combativeness,
            WaysData::CREATIVITY => $creativity,
            WaysData::EMPATHY => $empathy,
            WaysData::REASON => $reason,
            WaysData::CONVICTION => $conviction,
        ] = $data;

        return Ways::create($combativeness, $creativity, $empathy, $reason, $conviction);
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return \str_starts_with($type, Ways::class);
    }

    /**
     * @param Ways $object
     *
     * @return array{
     *                "ways.combativeness": int,
     *                "ways.creativity": int,
     *                "ways.empathy": int,
     *                "ways.reason": int,
     *                "ways.conviction": int
     *                }
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return $object->toArray();
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Ways;
    }
}
