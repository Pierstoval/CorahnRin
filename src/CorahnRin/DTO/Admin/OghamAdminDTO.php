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

namespace CorahnRin\DTO\Admin;

use Admin\DTO\EasyAdminDTOInterface;
use CorahnRin\Data\OghamType;
use CorahnRin\Document\Book;
use CorahnRin\Document\Ogham;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OghamAdminDTO implements EasyAdminDTOInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    public $name = '';

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Choice(OghamType::ALL)
     */
    public $type = '';

    /**
     * @var string
     *
     * @Assert\Type("string")
     */
    public $description = '';

    /**
     * @var Book
     *
     * @Assert\NotBlank
     * @Assert\Type("CorahnRin\Document\Book")
     */
    public $book;

    public static function createFromEntity(object $entity, array $options = []): self
    {
        if (!$entity instanceof Ogham) {
            throw new UnexpectedTypeException($entity, Ogham::class);
        }

        $self = new self();

        $self->name = $entity->getName();
        $self->description = $entity->getDescription();
        $self->type = $entity->getType();
        $self->book = $entity->getBookId();

        return $self;
    }

    public static function createEmpty(): self
    {
        return new self();
    }
}
