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
use CorahnRin\Entity\SocialClass;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class SocialClassesFixtures extends ArrayFixture implements ORMFixtureInterface
{
    public const ID_PEASANT = 1;
    public const ID_ARTISAN = 2;
    public const ID_BOURGEOIS = 3;
    public const ID_CLERGY = 4;
    public const ID_NOBLETY = 5;

    public function getEntityClass(): string
    {
        return SocialClass::class;
    }

    public function getObjects(): iterable
    {
        return [
            [
                'id' => self::ID_PEASANT,
                'domains' => [DomainsData::NATURAL_ENVIRONMENT['title'], DomainsData::PERCEPTION['title'], DomainsData::FEATS['title'], DomainsData::TRAVEL['title']],
                'name' => 'Paysan',
                'description' => 'Les roturiers font partie de la majorité de la population. Vous avez vécu dans une famille paysanne, à l\'écart des villes et cités, sans pour autant les ignorer. Vous êtes plus proche de la nature.'."\n".'les Demorthèn font également partie de cette classe sociale.',
            ],
            [
                'id' => self::ID_ARTISAN,
                'domains' => [DomainsData::CRAFT['title'], DomainsData::RELATION['title'], DomainsData::SCIENCE['title'], DomainsData::ERUDITION['title']],
                'name' => 'Artisan',
                'description' => 'Les roturiers font partie de la majorité de la population. Votre famille était composée d\'un ou plusieurs artisans ou ouvriers, participant à la vie communale et familiale usant de ses talents manuels.',
            ],
            [
                'id' => self::ID_BOURGEOIS,
                'domains' => [DomainsData::CRAFT['title'], DomainsData::RELATION['title'], DomainsData::PERFORMANCE['title'], DomainsData::ERUDITION['title']],
                'name' => 'Bourgeois',
                'description' => 'Votre famille a su faire des affaires dans les villes, ou tient probablement un commerce célèbre dans votre région, ce qui vous permet de vivre confortablement au sein d\'une communauté familière.',
            ],
            [
                'id' => self::ID_CLERGY,
                'domains' => [DomainsData::PRAYER['title'], DomainsData::RELATION['title'], DomainsData::TRAVEL['title'], DomainsData::ERUDITION['title']],
                'name' => 'Clergé',
                'description' => 'Votre famille a toujours respecté l\'Unique et ses représentants, et vous êtes issu d\'un milieu très pieux.'."\n".'Vous avez probablement la foi, vous aussi.',
            ],
            [
                'id' => self::ID_NOBLETY,
                'domains' => [DomainsData::CLOSE_COMBAT['title'], DomainsData::RELATION['title'], DomainsData::SCIENCE['title'], DomainsData::ERUDITION['title']],
                'name' => 'Noblesse',
                'description' => 'Vous portez peut-être un grand nom des affaires des grandes cités, ou avez grandi en ville. Néanmoins, votre famille est placée assez haut dans la noblesse pour vous permettre d\'avoir eu des enseignements particuliers.',
            ],
        ];
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-social-class-';
    }
}
