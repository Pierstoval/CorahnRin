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

use CorahnRin\Data\DomainsData;
use CorahnRin\Entity\GeoEnvironment;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class GeoEnvironmentsFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public const ID_RURAL = 1;
    public const ID_URBAN = 2;

    public function getEntityClass(): string
    {
        return GeoEnvironment::class;
    }

    public function getObjects(): iterable
    {
        $book = $this->getReference('corahnrin-book-2');

        return [
            [
                'id' => self::ID_RURAL,
                'domain' => DomainsData::NATURAL_ENVIRONMENT['title'],
                'name' => 'Rural',
                'description' => 'Votre personnage est issu d\'une campagne ou d\'un lieu relativement isolé.',
                'book' => $book,
            ],
            [
                'id' => self::ID_URBAN,
                'domain' => DomainsData::RELATION['title'],
                'name' => 'Urbain',
                'description' => 'Votre personnage a vécu longtemps dans une ville, suffisamment pour qu\'il ait adopté les codes de la ville dans son mode de vie.',
                'book' => $book,
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            BooksFixtures::class,
        ];
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-geo-environment-';
    }
}
