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
use EsterenMaps\Entity\Map;
use EsterenMaps\Entity\Zone;
use EsterenMaps\Model\MapBounds;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class MapDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = []): Map
    {
        $object = (new Instantiator())->instantiate(Map::class);
        $setter = ClosureSetter::getSetter($type, $object);

        $setter('id', $data['id']);
        $setter('name', $data['name']);
        $setter('nameSlug', $data['name_slug']);
        $setter('image', $data['image']);
        $setter('description', $data['description']);
        $setter('maxZoom', $data['max_zoom']);
        $setter('startZoom', $data['start_zoom']);
        $setter('startX', $data['start_x']);
        $setter('startY', $data['start_y']);
        $setter('bounds', MapBounds::fromArray($data['bounds']));
        $setter('coordinatesRatio', $data['coordinates_ratio']);
        $setter('zones', $this->denormalizer->denormalize($data['zones'], Zone::class.'[]'));

        return $object;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return Map::class === $type;
    }
}
