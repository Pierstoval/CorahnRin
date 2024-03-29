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

namespace CorahnRin\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateGameDTO
{
    /**
     * @Assert\NotBlank
     */
    public $name = '';

    public $summary = '';

    /**
     * @Assert\All({
     *
     *     @Assert\NotBlank,
     *
     *     @Assert\Type("CorahnRin\Entity\Character"),
     * })
     */
    public $charactersToInvite = [];
}
