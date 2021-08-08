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

namespace CorahnRin\Data;

class DomainItem
{
    private $title;
    private $shortName;
    private $description;
    private $way;

    public function __construct(string $title, string $shortName, string $description, string $way)
    {
        $this->title = $title;
        $this->shortName = $shortName;
        $this->description = $description;
        $this->way = $way;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWay(): string
    {
        return $this->way;
    }
}
