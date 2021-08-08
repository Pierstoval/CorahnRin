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

use Doctrine\Instantiator\Instantiator;
use EsterenMaps\Entity\Zone;
use EsterenMaps\Entity\ZoneType;
use EsterenMaps\Model\LatLng;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ZoneDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $object = (new Instantiator())->instantiate(Zone::class);
        $setter = ClosureSetter::getSetter($type, $object);

        $setter('id', $data['id']);
        $setter('name', $data['name']);
        $setter('description', $data['description']);
        $setter('coordinates', $this->denormalizer->denormalize($data['coordinates'], LatLng::class.'[]'));
        $setter('zoneType', $this->denormalizer->denormalize($data['zone_type'], ZoneType::class));

        return $object;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return Zone::class === $type;
    }
}
