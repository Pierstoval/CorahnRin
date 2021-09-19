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

namespace EsterenMaps\Model;

use Symfony\Component\Validator\Constraints as Assert;

class MapImageQuery
{
    /**
     * @var int
     *
     * @Assert\Type(type="int")
     * @Assert\Range(min=1 max=100)
     */
    public $ratio = 100;

    /**
     * @var int
     *
     * @Assert\Type(type="int")
     * @Assert\NotBlank
     * @Assert\Range(min=50)
     */
    public $width = 100;

    /**
     * @var int
     *
     * @Assert\Type(type="int")
     * @Assert\NotBlank
     * @Assert\Range(min=50)
     */
    public $height = 100;

    /**
     * @var int
     *
     * @Assert\Type(type="int")
     */
    public $x = 0;

    /**
     * @var int
     *
     * @Assert\Type(type="int")
     */
    public $y = 0;

    /**
     * @var bool
     *
     * @Assert\Type(type="bool")
     */
    public $withImages = true;
}
