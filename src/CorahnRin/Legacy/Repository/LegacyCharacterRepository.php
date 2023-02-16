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

namespace CorahnRin\Legacy\Repository;

use CorahnRin\Legacy\LegacyCharacterListPaginator;
use Doctrine\DBAL\Connection;

class LegacyCharacterRepository
{
    private $legacyConnection;

    public function __construct(Connection $legacyConnection)
    {
        $this->legacyConnection = $legacyConnection;
    }

    public function getCharacterById(int $id): ?array
    {
        $sql = <<<'SQL'

            -- Nice tip: use auto-completion to remember fields, and to do so, uncomment this line:
            -- use esteren_legacy;

            SELECT

                c.char_id,
                c.char_name,
                c.char_job,
                c.char_origin,
                c.char_people,
                c.char_content,
                c.char_date_creation,
                c.char_date_update,
                c.char_status,
                c.char_confirm_invite,
                c.game_id,
                c.user_id,

                j.job_id,
                j.job_name,
                j.job_desc,
                j.job_book,

                r.region_id,
                r.region_name,
                r.region_desc,
                r.region_kingdom,

                g.game_summary,
                g.game_id,
                g.game_name,
                g.game_summary,
                g.game_notes,
                g.game_mj,

                u.user_id,
                u.user_name,
                u.user_email,

                u_gm.user_id as gm_user_id,
                u_gm.user_name as gm_user_name,
                u_gm.user_email as gm_user_email

            FROM est_characters c

            LEFT JOIN est_jobs j ON c.char_job = j.job_id

            LEFT JOIN est_regions r ON c.char_origin = r.region_id

            LEFT JOIN est_games g ON c.game_id = g.game_id
                LEFT JOIN est_users u_gm ON u_gm.user_id = g.game_mj

            LEFT JOIN est_users u ON c.user_id = u.user_id

            WHERE c.char_id = :id
        SQL;

        return $this->legacyConnection->fetchAssociative($sql);
    }

    public function paginateLegacyCharactersForAdminList(int $currentPage): LegacyCharacterListPaginator
    {
        // Should be enough.
        $pageSize = 10;

        $currentPage = $currentPage < 1 ? 1 : $currentPage;
        $firstResult = ($currentPage - 1) * $pageSize;

        $queryBuilder = $this->legacyConnection->createQueryBuilder()
            ->select('c.char_id as id, c.char_name as name')
            ->from('est_characters', 'c')
            ->setFirstResult($firstResult)
            ->setMaxResults($pageSize)
        ;

        $numResults = (int) $this->legacyConnection->fetchOne('SELECT COUNT(c.char_id) as number FROM est_characters as c');

        $hasPreviousPage = $currentPage > 1;
        $hasNextPage = ($currentPage * $pageSize) < $numResults;

        return LegacyCharacterListPaginator::create(
            $queryBuilder->executeQuery(),
            $numResults,
            $currentPage,
            $hasPreviousPage,
            $hasNextPage,
            $hasPreviousPage ? $currentPage - 1 : null,
            $hasNextPage ? $currentPage + 1 : null,
            (int) \ceil($numResults / $pageSize),
            $numResults > $pageSize
        );
    }
}
