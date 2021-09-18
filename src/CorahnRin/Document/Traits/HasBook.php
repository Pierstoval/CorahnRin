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

namespace CorahnRin\Document\Traits;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

trait HasBook
{
    /**
     * @ODM\Field(type="int", nullable=true)
     */
    private ?int $bookId = null;

    public function getBookId(): ?int
    {
        return $this->bookId;
    }
}
