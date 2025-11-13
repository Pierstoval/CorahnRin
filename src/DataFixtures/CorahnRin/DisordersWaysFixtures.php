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

use CorahnRin\Data\Ways;
use CorahnRin\Entity\MentalDisorderWay;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class DisordersWaysFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            DisordersFixtures::class,
        ];
    }

    protected function getEntityClass(): string
    {
        return MentalDisorderWay::class;
    }

    protected function getObjects(): array
    {
        return [
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_FRENZY),
                'way' => Ways::COMBATIVENESS,
                'major' => true,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_EXALTATION),
                'way' => Ways::COMBATIVENESS,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_EXALTATION),
                'way' => Ways::CONVICTION,
                'major' => false,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MELANCHOLY),
                'way' => Ways::CONVICTION,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MELANCHOLY),
                'way' => Ways::COMBATIVENESS,
                'major' => false,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MELANCHOLY),
                'way' => Ways::REASON,
                'major' => false,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_HALLUCINATION),
                'way' => Ways::CREATIVITY,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_HALLUCINATION),
                'way' => Ways::EMPATHY,
                'major' => true,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MENTAL_CONFUSION),
                'way' => Ways::CREATIVITY,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MENTAL_CONFUSION),
                'way' => Ways::REASON,
                'major' => false,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MIMICRY),
                'way' => Ways::CREATIVITY,
                'major' => false,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_OBSESSION),
                'way' => Ways::REASON,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_OBSESSION),
                'way' => Ways::CREATIVITY,
                'major' => false,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_HYSTERIA),
                'way' => Ways::EMPATHY,
                'major' => true,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MYSTICISM),
                'way' => Ways::EMPATHY,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_MYSTICISM),
                'way' => Ways::CONVICTION,
                'major' => true,
            ],

            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_PARANOIA),
                'way' => Ways::REASON,
                'major' => true,
            ],
            [
                'disorder' => $this->getReference('corahnrin-disorder-'.DisordersFixtures::ID_PARANOIA),
                'way' => Ways::EMPATHY,
                'major' => false,
            ],
        ];
    }
}
