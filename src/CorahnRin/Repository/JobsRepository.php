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

namespace CorahnRin\Repository;

use CorahnRin\Document\Job;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

/**
 * @method null|Job find($id, $lockMode = null, $lockVersion = null)
 */
class JobsRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * Renvoie la liste des métiers triés par livre associé.
     *
     * @return Job[][]
     */
    public function findAllPerBook()
    {
        /** @var Job[] $jobs */
        $jobs = $this->findAll();

        $books = [];

        foreach ($jobs as $job) {
            $book = $job->getBook();
            $bookId = $book ? $book->getId() : -1;
            $books[$bookId][$job->getId()] = $job;
        }

        return $books;
    }
}
