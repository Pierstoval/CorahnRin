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

use Admin\DTO\FormDTOInterface;
use CorahnRin\Data\OghamType;
use CorahnRin\Entity\Book;
use CorahnRin\Entity\Ogham;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OghamAdminDTO implements FormDTOInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    public string $name = '';

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\Choice(OghamType::ALL)
     */
    public string $type = '';

    /**
     * @var string
     *
     * @Assert\Type("string")
     */
    public string $description = '';

    /**
     * @var Book
     *
     * @Assert\NotBlank
     * @Assert\Type("CorahnRin\Entity\Book")
     */
    public Book $book;

    public static function getEntityMutatorMethodName(): string
    {
        return 'updateFromAdmin';
    }

    public static function createFromEntity(object $entity): static
    {
        if (!$entity instanceof Ogham) {
            throw new UnexpectedTypeException($entity, Ogham::class);
        }

        $self = new self();

        $self->name = $entity->getName();
        $self->description = $entity->getDescription();
        $self->type = $entity->getType();
        $self->book = $entity->getBook();

        return $self;
    }
}
