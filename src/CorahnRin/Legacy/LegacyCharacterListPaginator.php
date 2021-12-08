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

namespace CorahnRin\Legacy;

use Doctrine\DBAL\Result;

class LegacyCharacterListPaginator
{
    private Result $results;
    private int $numberOfResults;
    private int $currentPage;
    private bool $hasPreviousPage;
    private bool $hasNextPage;
    private ?int $previousPage;
    private ?int $nextPage;
    private int $numPages;
    private bool $hasToPaginate;

    private function __construct()
    {
    }

    public static function create(
        Result $results,
        int $numberOfResults,
        int $currentPage,
        bool $hasPreviousPage,
        bool $hasNextPage,
        ?int $previousPage,
        ?int $nextPage,
        int $numPages,
        bool $hasToPaginate
    ): self {
        $self = new self();

        $self->results = $results;
        $self->numberOfResults = $numberOfResults;
        $self->currentPage = $currentPage;
        $self->hasPreviousPage = $hasPreviousPage;
        $self->hasNextPage = $hasNextPage;
        $self->previousPage = $previousPage;
        $self->nextPage = $nextPage;
        $self->numPages = $numPages;
        $self->hasToPaginate = $hasToPaginate;

        return $self;
    }

    public function results(): iterable
    {
        return $this->results->fetchAllAssociative();
    }

    public function numberOfResults(): int
    {
        return $this->numberOfResults;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function hasPreviousPage(): bool
    {
        return $this->hasPreviousPage;
    }

    public function hasNextPage(): bool
    {
        return $this->hasNextPage;
    }

    public function previousPage(): ?int
    {
        return $this->previousPage;
    }

    public function nextPage(): ?int
    {
        return $this->nextPage;
    }

    public function numPages(): int
    {
        return $this->numPages;
    }

    public function hasToPaginate(): bool
    {
        return $this->hasToPaginate;
    }
}
