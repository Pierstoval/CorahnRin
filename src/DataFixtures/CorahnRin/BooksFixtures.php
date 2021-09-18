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

use CorahnRin\Document\Book;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class BooksFixtures extends ArrayFixture implements ODMFixtureInterface
{
    public const ID_BOOK_1_UNIVERSE = 2;
    public const ID_COMMUNITY = 13;

    public function getEntityClass(): string
    {
        return Book::class;
    }

    public function getReferencePrefix(): ?string
    {
        return 'corahnrin-book-';
    }

    public function getObjects(): iterable
    {
        return [
            [
                'id' => 1,
                'name' => 'Livre 0 - Prologue',
                'description' => '',
            ],
            [
                'id' => 2,
                'name' => 'Livre 1 - Univers',
                'description' => '',
            ],
            [
                'id' => 3,
                'name' => 'Livre 2 - Voyages',
                'description' => '',
            ],
            [
                'id' => 4,
                'name' => 'Livre 2 - Voyages (Réédition )',
                'description' => '',
            ],
            [
                'id' => 5,
                'name' => 'Livre 3 - Dearg Intégrale',
                'description' => '',
            ],
            [
                'id' => 6,
                'name' => 'Livre 3 - Dearg Tome 1',
                'description' => '',
            ],
            [
                'id' => 7,
                'name' => 'Livre 3 - Dearg Tome 2',
                'description' => '',
            ],
            [
                'id' => 8,
                'name' => 'Livre 3 - Dearg Tome 3',
                'description' => '',
            ],
            [
                'id' => 9,
                'name' => 'Livre 3 - Dearg Tome 4',
                'description' => '',
            ],
            [
                'id' => 10,
                'name' => 'Livre 4 - Secrets',
                'description' => '',
            ],
            [
                'id' => 11,
                'name' => 'Livre 5 - Peuples',
                'description' => '',
            ],
            [
                'id' => 12,
                'name' => 'Le Monastère de Tuath',
                'description' => '',
            ],
            [
                'id' => 13,
                'name' => 'Contenu de la communauté',
                'description' => 'Ce contenu est par définition non-officiel.',
            ],
        ];
    }
}
