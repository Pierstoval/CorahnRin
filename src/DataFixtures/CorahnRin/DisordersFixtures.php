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

namespace DataFixtures\CorahnRin;

use CorahnRin\Entity\MentalDisorder;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class DisordersFixtures extends ArrayFixture implements ORMFixtureInterface
{
    public const ID_FRENZY = 1;
    public const ID_EXALTATION = 2;
    public const ID_MELANCHOLY = 3;
    public const ID_HALLUCINATION = 4;
    public const ID_MENTAL_CONFUSION = 5;
    public const ID_MIMICRY = 6;
    public const ID_OBSESSION = 7;
    public const ID_HYSTERIA = 8;
    public const ID_MYSTICISM = 9;
    public const ID_PARANOIA = 10;

    protected function getEntityClass(): string
    {
        return MentalDisorder::class;
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-disorder-';
    }

    protected function getObjects(): array
    {
        return [
            [
                'id' => self::ID_FRENZY,
                'name' => 'Frénésie',
                'description' => '',
            ],
            [
                'id' => self::ID_EXALTATION,
                'name' => 'Exaltation',
                'description' => '',
            ],
            [
                'id' => self::ID_MELANCHOLY,
                'name' => 'Mélancolie',
                'description' => '',
            ],
            [
                'id' => self::ID_HALLUCINATION,
                'name' => 'Hallucination',
                'description' => '',
            ],
            [
                'id' => self::ID_MENTAL_CONFUSION,
                'name' => 'Confusion mentale',
                'description' => '',
            ],
            [
                'id' => self::ID_MIMICRY,
                'name' => 'Mimétisme',
                'description' => '',
            ],
            [
                'id' => self::ID_OBSESSION,
                'name' => 'Obsession',
                'description' => '',
            ],
            [
                'id' => self::ID_HYSTERIA,
                'name' => 'Hystérie',
                'description' => '',
            ],
            [
                'id' => self::ID_MYSTICISM,
                'name' => 'Mysticisme',
                'description' => '',
            ],
            [
                'id' => self::ID_PARANOIA,
                'name' => 'Paranoïa',
                'description' => '',
            ],
        ];
    }
}
