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

use CorahnRin\Entity\Job;
use CorahnRin\Entity\Miracle;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class MiraclesFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public const ID_CANTICLE = 1;
    public const ID_CIRCLE_OF_PROTECTION = 2;
    public const ID_CASTIGATION = 3;
    public const ID_DIVINE_WRATH = 4;
    public const ID_DIVINE_COLD = 5;
    public const ID_MIRACULOUS_HEALING = 6;
    public const ID_LITANY = 7;
    public const ID_MIGHT_OF_FAITH = 8;
    public const ID_PURIFICATION = 9;
    public const ID_RESURRECTION = 10;
    public const ID_HOLY_VIGOR = 11;
    public const ID_VISION_OF_LIMBO = 12;

    public function getDependencies(): array
    {
        return [
            JobsFixtures::class,
        ];
    }

    protected function getEntityClass(): string
    {
        return Miracle::class;
    }

    protected function getObjects(): iterable
    {
        return [
            [
                'id' => self::ID_CANTICLE,
                'name' => 'Cantique',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_VECTOR,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_SIGIRE,
                ]),
            ],
            [
                'id' => self::ID_CIRCLE_OF_PROTECTION,
                'name' => 'Cercle de protection',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_SIGIRE,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_VECTOR,
                ]),
            ],
            [
                'id' => self::ID_CASTIGATION,
                'name' => 'Châtiment',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_SIGIRE,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_VECTOR,
                ]),
            ],
            [
                'id' => self::ID_DIVINE_WRATH,
                'name' => 'Courroux divin',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_SIGIRE,
                    JobsFixtures::ID_VECTOR,
                ]),
            ],
            [
                'id' => self::ID_DIVINE_COLD,
                'name' => 'Froid divin',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_SIGIRE,
                    JobsFixtures::ID_VECTOR,
                ]),
                'minorForJobs' => $this->getJobs([
                ]),
            ],
            [
                'id' => self::ID_MIRACULOUS_HEALING,
                'name' => 'Guérison miraculeuse',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_VECTOR,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_SIGIRE,
                ]),
            ],
            [
                'id' => self::ID_LITANY,
                'name' => 'Litanie',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_VECTOR,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_SIGIRE,
                ]),
            ],
            [
                'id' => self::ID_MIGHT_OF_FAITH,
                'name' => 'Puissance de la foi',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_SIGIRE,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_VECTOR,
                ]),
            ],
            [
                'id' => self::ID_PURIFICATION,
                'name' => 'Purification',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_SIGIRE,
                    JobsFixtures::ID_VECTOR,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                ]),
            ],
            [
                'id' => self::ID_RESURRECTION,
                'name' => 'Résurrection',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_SIGIRE,
                    JobsFixtures::ID_VECTOR,
                ]),
            ],
            [
                'id' => self::ID_HOLY_VIGOR,
                'name' => 'Sainte vigueur',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_VECTOR,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_SIGIRE,
                ]),
            ],
            [
                'id' => self::ID_VISION_OF_LIMBO,
                'name' => 'Vision des Limbes',
                'majorForJobs' => $this->getJobs([
                    JobsFixtures::ID_BLADE_KNIGHT,
                    JobsFixtures::ID_SIGIRE,
                ]),
                'minorForJobs' => $this->getJobs([
                    JobsFixtures::ID_CLERIC,
                    JobsFixtures::ID_MONK,
                    JobsFixtures::ID_PRIEST,
                    JobsFixtures::ID_VECTOR,
                ]),
            ],
        ];
    }

    /**
     * @return Job[]
     */
    private function getJobs(array $ids): array
    {
        $jobs = [];

        foreach ($ids as $id) {
            $jobs[] = $this->getReference('corahnrin-jobs-'.$id);
        }

        return $jobs;
    }
}
