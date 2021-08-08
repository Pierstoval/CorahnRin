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

namespace EsterenMaps\Serializer;

use EsterenMaps\Model\LatLng;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class LatLngDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = []): LatLng
    {
        return LatLng::create($data['lat'], $data['lng']);
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return LatLng::class === $type;
    }
}
