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

namespace CorahnRin\Entity\CharacterProperties;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Money
{
    protected static $name = 'Daol';
    protected static $names = ['ember', 'azure', 'frost'];

    /**
     * @var int
     *
     * @ORM\Column(name="ember", type="integer")
     */
    protected $ember;

    /**
     * @var int
     *
     * @ORM\Column(name="azure", type="integer")
     */
    protected $azure;

    /**
     * @var int
     *
     * @ORM\Column(name="frost", type="integer")
     */
    protected $frost;

    /**
     * @param int  $ember
     * @param int  $azure
     * @param int  $frost
     * @param bool $flatten
     */
    public function __construct($ember = 0, $azure = 0, $frost = 0, $flatten = false)
    {
        $this->ember = (int) $ember;
        $this->azure = (int) $azure;
        $this->frost = (int) $frost;

        if ($flatten) {
            $this->reallocate();
        }
    }

    public function getFrost(): int
    {
        return $this->frost;
    }

    public function addFrost(int $frost): void
    {
        $this->frost += $frost;
    }

    public function getAzure(): int
    {
        return $this->azure;
    }

    public function addAzure(int $azure): void
    {
        $this->azure += $azure;
    }

    public function getEmber(): int
    {
        return $this->ember;
    }

    public function addEmber(int $ember): void
    {
        $this->ember += $ember;
    }

    /**
     * Take all lower moneys to reallocate them to the higher money units.
     */
    public function reallocate(): void
    {
        while ($this->ember > 10) {
            $this->azure++;
            $this->ember -= 10;
        }

        while ($this->azure > 10) {
            $this->frost++;
            $this->azure -= 10;
        }
    }

    /**
     * Take all high moneys and put them in the "ember" unit.
     */
    public function flatten(): void
    {
        $this->ember =
            $this->ember +
            ($this->azure * 10) +
            ($this->frost * 100)
        ;

        $this->azure = 0;
        $this->frost = 0;
    }
}
