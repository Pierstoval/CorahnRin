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
use CorahnRin\Document\Job;
use Doctrine\Bundle\MongoDBBundle\Fixture\ODMFixtureInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;

class JobsFixtures extends ArrayFixture implements ODMFixtureInterface, DependentFixtureInterface
{
    public const ID_ARTISAN = 1;
    public const ID_BARD = 2;
    public const ID_HUNTER = 3;
    public const ID_KNIGHT = 4;
    public const ID_FIGHTER = 5;
    public const ID_MERCHANT = 6;
    public const ID_DEMORTHEN = 7;
    public const ID_SCHOLAR = 8;
    public const ID_SPY = 9;
    public const ID_EXPLORER = 10;
    public const ID_INVESTIGATOR = 11;
    public const ID_MAGIENTIST = 12;
    public const ID_SMUGGLER = 13;
    public const ID_DOCTOR = 14;
    public const ID_OCCULTIST = 15;
    public const ID_PEASANT = 16;
    public const ID_VARIGAL = 17;
    public const ID_PLAYER = 18;
    public const ID_MONK = 19;
    public const ID_CLERIC = 20;
    public const ID_PRIEST = 21;
    public const ID_VECTOR = 22;
    public const ID_SIGIRE = 23;
    public const ID_BLADE_KNIGHT = 24;
    public const ID_DAMATHAIR = 25;
    public const ID_RANGED_FIGHTER = 26;

    public function getObjects(): iterable
    {
        $book1Universe = $this->getReference('corahnrin-book-2');
        $bookCommunity = $this->getReference('corahnrin-book-13');

        return [
            [
                'id' => self::ID_ARTISAN,
                'name' => 'Artisan',
                'description' => 'Quel que soit son domaine, l\'artisan est un manuel qualifié.
Forgeron, cuisinier, architecte, cordonnier, bûcheron, sculpteur, joailler ; les artisans couvrent un grand nombre de spécialités.
Dans les cités où est implantée la magience, on trouve aussi des réparateurs d\'artefacts et des ouvriers spécialisés travaillant dans les usines.',
                'primaryDomain' => DomainsData::CRAFT['title'],
                'dailySalary' => 8,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::RELATION['title'],
                    DomainsData::SCIENCE['title'],
                ],
            ],
            [
                'id' => self::ID_BARD,
                'name' => 'Barde',
                'description' => 'Le statut de barde est hautement honorifique et les plus puissants monarques s\'entourent de ces artistes qui ont de véritables rôles d\'éminence grise.
Artiste, acrobate, musicien, bouffon, le barde peut revêtir différents rôles.
Il peut également être connu sous d\'autres noms, comme les poètes aveugles filidh ou les étranges céilli de l\'archipel des Tri-Sweszörs.',
                'primaryDomain' => DomainsData::PERFORMANCE['title'],
                'dailySalary' => 10,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::RELATION['title'],
                    DomainsData::TRAVEL['title'],
                ],
            ],
            [
                'id' => self::ID_HUNTER,
                'name' => 'Chasseur',
                'description' => 'Il nourrit la communauté du produit de ses longues expéditions, qui durent parfois plusieurs jours.
L\'expansion des villes a vu l\'apparition de chasseurs d\'un genre nouveau comme les ratiers.
D\'autres, comme les Enfants de Neven, dédient leur existence à la traque des feondas.',
                'primaryDomain' => DomainsData::NATURAL_ENVIRONMENT['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::CLOSE_COMBAT['title'],
                    DomainsData::SHOOTING_AND_THROWING['title'],
                ],
            ],
            [
                'id' => self::ID_KNIGHT,
                'name' => 'Chevalier',
                'description' => 'Ces hommes et ces femmes font partie de la noblesse et appartiennent le plus souvent à un ordre de chevalerie comme les Hilderins ou les Ronces.
Certains sont des chevaliers errants, derniers héritiers d\'une famille noble ; d\'autres, les vassaux d\'un puissant seigneur.',
                'primaryDomain' => DomainsData::CLOSE_COMBAT['title'],
                'dailySalary' => 10,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::RELATION['title'],
                    DomainsData::TRAVEL['title'],
                ],
            ],
            [
                'id' => self::ID_FIGHTER,
                'name' => 'Combattant',
                'description' => 'Il peut être soldat ou mercenaire, champion de justice, bagarreur de taverne ou détrousseur des rues sombres, etc.
Il se spécialise dans les armes de contact.',
                'primaryDomain' => DomainsData::CLOSE_COMBAT['title'],
                'dailySalary' => 7,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::FEATS['title'],
                    DomainsData::SHOOTING_AND_THROWING['title'],
                ],
            ],
            [
                'id' => self::ID_MERCHANT,
                'name' => 'Commerçant',
                'description' => 'Marchand ambulant ou tenancier d\'une échoppe bien achalandée, le commerçant peut négocier bien des marchandises.',
                'primaryDomain' => DomainsData::RELATION['title'],
                'dailySalary' => 8,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::CRAFT['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_DEMORTHEN,
                'name' => 'Demorthèn',
                'description' => 'Représentant de la nature, il peut entrer en contact avec les esprits et leur demander d\'accomplir des tâches particulières.
Il est le gardien des anciennes traditions péninsulaires et il est souvent considéré avec respect.
Les apprentis Demorthèn sont appelés Ionnthèn.',
                'primaryDomain' => DomainsData::DEMORTHEN_MYSTERIES['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::NATURAL_ENVIRONMENT['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_SCHOLAR,
                'name' => 'Érudit',
                'description' => 'Passionnés par le savoir, les recherches, les érudits sont souvent employés comme scribes, professeurs ou bibliothécaires.
Généralement, un érudit possède un domaine de connaissance de prédilection, comme la théologie, magience, science, etc.',
                'primaryDomain' => DomainsData::ERUDITION['title'],
                'dailySalary' => 8,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::SCIENCE['title'],
                    DomainsData::OCCULTISM['title'],
                ],
            ],
            [
                'id' => self::ID_SPY,
                'name' => 'Espion',
                'description' => 'N\'importe qui, qu\'il soit un conseiller haut placé ou un simple mendiant, peut jouer un double rôle, amassant des informations pour le compte d\'un commanditaire.
Le domaine secondaire peut être choisi librement pour coller à la fausse identité de l\'espion.',
                'primaryDomain' => DomainsData::PERCEPTION['title'],
                'dailySalary' => 9,
                'book' => $book1Universe,
                'secondaryDomains' => [],
            ],
            [
                'id' => self::ID_EXPLORER,
                'name' => 'Explorateur',
                'description' => 'Aventurier et casse-cou, l\'explorateur est passionné par le voyage, fuyant souvent la pauvreté ou la monotonie de son lieu de naissance.',
                'primaryDomain' => DomainsData::FEATS['title'],
                'dailySalary' => 9,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::NATURAL_ENVIRONMENT['title'],
                    DomainsData::TRAVEL['title'],
                ],
            ],
            [
                'id' => self::ID_INVESTIGATOR,
                'name' => 'Investigateur',
                'description' => 'Habitant généralement dans les grandes villes, les investigateurs proposent leurs services pour mener l\'enquête.
Chaque investigateur a son style : certains sont versés dans l\'occultisme, d\'autres dans la science, la magience ou encore la médecine.
De ce fait, le choix du domaine secondaire est libre.',
                'primaryDomain' => DomainsData::PERCEPTION['title'],
                'dailySalary' => 9,
                'book' => $book1Universe,
                'secondaryDomains' => [],
            ],
            [
                'id' => self::ID_MAGIENTIST,
                'name' => 'Magientiste',
                'description' => 'En Tri-Kazel, on les nomme souvent par le terme dédaigneux de "Daedemorthys".
Malgré cette mauvaise réputation, leur science a pour but général l\'amélioration des conditions de vie de l\'humanité.
Un magientiste diplômé est un scientör, alors qu\'un élève en cours de formation est un inceptus.',
                'primaryDomain' => DomainsData::MAGIENCE['title'],
                'dailySalary' => 10,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::SCIENCE['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_SMUGGLER,
                'name' => 'Malandrin',
                'description' => 'Voleur, cambrioleur, tire-laine ; les moyens illégaux pour gagner sa vie sont assez nombreux pour attirer du monde, et ce malgré les risques.',
                'primaryDomain' => DomainsData::STEALTH['title'],
                'dailySalary' => 8,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::FEATS['title'],
                    DomainsData::PERFORMANCE['title'],
                ],
            ],
            [
                'id' => self::ID_DOCTOR,
                'name' => 'Médecin',
                'description' => 'Il est des endroits où le demorthèn local n\'est pas le meilleur guérisseur.
De nouvelles techniques tout-à-fait efficaces proviennent désormais des universités des grandes villes.
Certains médecins, les aliénistes, s\'attachent à soigner les troubles psychiques en se référant aux travaux du professeur continental Ernst Zigger.
D\'autres, comme les apothicaires, sont spécialisés dans l\'herboristerie.',
                'primaryDomain' => DomainsData::SCIENCE['title'],
                'dailySalary' => 10,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::RELATION['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_OCCULTIST,
                'name' => 'Occultiste',
                'description' => 'Passionnés d\'ésotérisme, les occultistes sont souvent des universitaires ayant un grand intérêt pour ce domaine dénigré par les autres branches de la science.',
                'primaryDomain' => DomainsData::OCCULTISM['title'],
                'dailySalary' => 8,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::SCIENCE['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_PEASANT,
                'name' => 'Paysan',
                'description' => 'Qu\'il cultive la terre ou élève des animaux, il participe à la vie de la communauté en la nourrissant.',
                'primaryDomain' => DomainsData::NATURAL_ENVIRONMENT['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::CRAFT['title'],
                    DomainsData::FEATS['title'],
                ],
            ],
            [
                'id' => self::ID_VARIGAL,
                'name' => 'Varigal',
                'description' => 'Voyageur, messager, porteur de nouvelles mais aussi de colis, le varigal est un lien entre les communautés éparses de Tri-Kazel.
Passant l\'essentiel de sa vie sur les chemins, il est généralement bien accueilli quand il arrive dans un village.
Proches de la nature, les varigaux sont souvent les alliés des demorthèn.',
                'primaryDomain' => DomainsData::TRAVEL['title'],
                'dailySalary' => 10,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::NATURAL_ENVIRONMENT['title'],
                    DomainsData::FEATS['title'],
                ],
            ],
            [
                'id' => self::ID_PLAYER,
                'name' => 'Joueur professionnel',
                'description' => ' Au-delà des jeux de stratégie pratiqués dans les cours et les seigneuries, il existe dans les villes une population de joueurs d\'élite qui ont appris et développé des straté
gies.
Même quand les règles d\'un jeu paraissent simples et le résultat dépendre de la chance ou de l\'intuition, le joueur initié sait qu\'il n\'en est rien.
Lui connaît les probabilités et les mathématiques, de sorte qu\'il sache précisément ce qu\'il risque, ou comment tromper habilement un naïf, au point de pouvoir devenir joueur professionnel et ai
nsi gagner des sommes considérables au jeu.
Il ne s\'agit pas simplement de piécettes, mais bien d\'obtenir de plus puissants qu\'ils soient prêts à parier leur maison ou la main de leur fille, ou n\'importe quel "bien" de valeur d\'ailleurs.
L\'astuce remplace la force du guerrier pour monter dans la société et se faire une place au soleil.',
                'primaryDomain' => DomainsData::PERFORMANCE['title'],
                'dailySalary' => 12,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::RELATION['title'],
                ],
            ],
            [
                'id' => self::ID_MONK,
                'name' => 'Moine du Temple',
                'description' => '',
                'primaryDomain' => DomainsData::PRAYER['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::CRAFT['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_CLERIC,
                'name' => 'Clerc du Temple',
                'description' => '',
                'primaryDomain' => DomainsData::PRAYER['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_PRIEST,
                'name' => 'Prêtre du Temple',
                'description' => '',
                'primaryDomain' => DomainsData::PRAYER['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::RELATION['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_VECTOR,
                'name' => 'Vecteur du Temple',
                'description' => '',
                'primaryDomain' => DomainsData::PRAYER['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::RELATION['title'],
                    DomainsData::TRAVEL['title'],
                ],
            ],
            [
                'id' => self::ID_SIGIRE,
                'name' => 'Sigire du Temple',
                'description' => '',
                'primaryDomain' => DomainsData::PRAYER['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::CLOSE_COMBAT['title'],
                ],
            ],
            [
                'id' => self::ID_BLADE_KNIGHT,
                'name' => 'Chevalier lame du Temple',
                'description' => '',
                'primaryDomain' => DomainsData::PRAYER['title'],
                'dailySalary' => 6,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::CLOSE_COMBAT['title'],
                ],
            ],
            [
                'id' => self::ID_DAMATHAIR,
                'name' => 'Dàmàthair',
                'description' => 'Les Dàmàthair sont exclusivement des femmes.
Elles éduquent et protègent les plus jeunes. Elles assistent aux accouchements, enseignent aux enfants suivant leur âge et les protègent en cas de danger.
Le rôle de la Dàmàthair est de créer les liens de la communauté passée, actuelle et future. Les enfants sont élevés ensemble comme une seule et même famille, quelle que soit leur différence de s
ang afin de plus tard ne former qu\'un seul rempart contre ce qui peut se trouver à l\'extérieur.
Si les habitants de Tri-Kazel ont une capacité d\'entraide si importante (surtout dans les montagnes) c\'est parce qu\'on leur enseigne qu\'ils ne sont qu\'une seule entité qui ne peut survivre qu\'en vivant ensemble.
La damathair principale d\'une communauté est très attachée à celle-ci et ne la quittera que pour cas de force majeure. Mais il arrive qu\'elle ait une ou plusieurs jeunes assistantes qui n\'ont pas encore fixé leur vocation définitive et peuvent se tourner vers un autre métier. Inversement, il arrive qu\'une varigale, une vectrice (en Gwidre) ou même une militaire choisisse de s\'établir comme damathair. Ces changements de trajectoire sont à discuter entre le joueur et le MJ, du moment qu\'ils respectent la cohérence du personnage.',
                'primaryDomain' => DomainsData::RELATION['title'],
                'dailySalary' => 6,
                'book' => $bookCommunity,
                'secondaryDomains' => [
                    DomainsData::CLOSE_COMBAT['title'],
                    DomainsData::ERUDITION['title'],
                ],
            ],
            [
                'id' => self::ID_RANGED_FIGHTER,
                'name' => 'Combattant à distance',
                'description' => 'Il peut être soldat ou mercenaire, champion de justice, bagarreur de taverne ou détrousseur des rues sombres, etc.
Il se spécialise dans les armes à distance',
                'primaryDomain' => DomainsData::SHOOTING_AND_THROWING['title'],
                'dailySalary' => 7,
                'book' => $book1Universe,
                'secondaryDomains' => [
                    DomainsData::CLOSE_COMBAT['title'],
                    DomainsData::FEATS['title'],
                ],
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            BooksFixtures::class,
        ];
    }

    protected function getEntityClass(): string
    {
        return Job::class;
    }

    protected function getReferencePrefix(): ?string
    {
        return 'corahnrin-jobs-';
    }
}
