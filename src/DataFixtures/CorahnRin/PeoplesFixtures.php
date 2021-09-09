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

use CorahnRin\Document\People;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class PeoplesFixtures extends ArrayFixture implements ORMFixtureInterface, DependentFixtureInterface
{
    public const ID_TRI_KAZEL = 1;
    public const ID_TARISH = 2;
    public const ID_OSAG = 3;
    public const ID_CONTINENT = 4;

    public function getEntityClass(): string
    {
        return People::class;
    }

    public function getObjects(): iterable
    {
        $book = $this->getReference('corahnrin-book-2');

        return [
            [
                'id' => self::ID_TRI_KAZEL,
                'name' => 'Tri-Kazel',
                'description' => "Les Tri-Kazeliens constituent la très grande majorité de la population de la péninsule. La plupart d'entre eux conservent une stature assez robuste héritée des Osags mais peuvent aussi avoir des traits d'autres peuples. Les Tri-Kazeliens sont issus de siècles de mélanges entre toutes les cultures ayant un jour ou l'autre foulé le sol de la péninsule.\n\nDe par cette origine, le PJ connaît un dialecte local ; il faut donc préciser de quel pays et région il est originaire.",
                'book' => $book,
            ],
            [
                'id' => self::ID_TARISH,
                'name' => 'Tarish',
                'description' => "D'origine inconnue, le peuple Tarish forme une minorité nomade qui parcourt depuis des décennies les terres de la péninsule. Il est aussi appelé \"peuple de l'ouest\" car la légende veut qu'il soit arrivé par l'Océan Furieux. Les Tarishs se distinguent des Tri-Kazeliens par des pommettes hautes, le nez plutôt aquilin et les yeux souvent clairs. Beaucoup d'entre eux deviennent des saltimbanques, des mystiques ou des artisans.\nLa culture Tarish, même si elle est diluée aujourd'hui, conserve encore une base importante : c'est un peuple nomade habitué aux longs périples et leur langue n'a pas disparu, bien qu'aucun étranger ne l'ait jamais apprise.",
                'book' => $book,
            ],
            [
                'id' => self::ID_OSAG,
                'name' => 'Osag',
                'description' => "Habitués à ne compter que sur eux-mêmes, les Osags forment un peuple rude. Généralement dotés d'une carrure imposante, ils sont les descendants directs des clans traditionnels de la péninsule. La civilisation péninsulaire a beaucoup évolué depuis l'avènement des Trois Royaumes, mais certains clans sont restés fidèles aux traditions ancestrales et n'ont pas pris part à ces changements. Repliés sur leur mode de vie clanique, les Osags ne se sont pas métissés avec les autres peuples et ont gardé de nombreuses caractéristiques de leurs ancêtres. Les Osags font de grands guerriers et comptent parmi eux les plus célèbres Demorthèn.\n\nLeur langue a elle aussi survécu au passage des siècles. Les mots \"feondas\", \"C'maogh\", \"Dàmàthair\" - pour ne citer qu'eux - viennent tous de ce que les Tri-Kazeliens nomment la langue ancienne, mais qui est toujours utilisée par les Osags.",
                'book' => $book,
            ],
            [
                'id' => self::ID_CONTINENT,
                'name' => 'Continent',
                'description' => "Les hommes et les femmes du Continent sont souvent plus minces et plus élancés que les natifs de Tri-Kazel. Leur visage aura tendance à être plus fin mais avec des traits parfois taillés à la serpe. Un PJ choisissant ce peuple ne sera pas natif du Continent, mais plutôt le descendant direct d'au moins un parent Continental. Si les origines Continentales du PJ sont davantage diluées, on estime qu'il fait partie du peuple de Tri-Kazel.\n\nEn fonction du passé de la famille du PJ et de son niveau d'intégration dans la société tri-kazelienne, il pourrait avoir appris leur langue d'origine Continentale ou bien un patois de la péninsule, au choix du PJ.",
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
        return 'corahnrin-people-';
    }
}
