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

namespace User\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use User\Util\Canonicalizer;
use function array_search;
use function array_unique;
use function array_values;
use function in_array;
use function strtoupper;
use function serialize;
use function unserialize;

/**
 * @ODM\Document(repositoryClass="User\Repository\UserRepository")
 *
 * @UniqueEntity("email", message="user.email.already_used")
 * @UniqueEntity("username", message="user.username.already_used")
 */
class User implements UserInterface, Serializable, EquatableInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableDocument;

    public const ROLE_DEFAULT = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @var int
     *
     * @ODM\Field(name="id", type="int", nullable=false)
     * @ODM\Id(type="int", strategy="INCREMENT")
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(name="username", type="string")
     *
     * @Assert\NotBlank
     */
    private string $username;

    /**
     * @var string
     *
     * @ODM\Field(name="email", type="string")
     *
     * @Assert\NotBlank
     */
    private string $email;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     *
     * @ODM\Field(name="password", type="string")
     */
    private string $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var null|string
     */
    private ?string $plainPassword;

    /**
     * @var null|string
     *
     * @ODM\Field(name="confirmation_token", type="string", nullable=true)
     */
    private ?string $confirmationToken;

    /**
     * @var array
     *
     * @ODM\Field(name="roles", type="collection")
     */
    private array $roles;

    /**
     * @var bool
     *
     * @ODM\Field(name="email_confirmed", type="bool")
     */
    private bool $emailConfirmed = false;

    public function __construct()
    {
        $this->roles = [static::ROLE_DEFAULT];
    }

    public function __toString()
    {
        return $this->username;
    }

    public static function create(string $username, string $email, string $encodedPassword): self
    {
        $user = new self();

        $user->username = Canonicalizer::urlize($username);
        $user->email = $email;
        $user->password = $encodedPassword;

        return $user;
    }

    public function addRole($role): void
    {
        $role = strtoupper($role);

        if ($role === static::ROLE_DEFAULT) {
            return;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        $this->roles = array_unique($this->roles);
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    public function hasRole($role): bool
    {
        if (self::ROLE_DEFAULT === $role) {
            return true;
        }

        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    public function removeRole($role): self
    {
        if (self::ROLE_DEFAULT === $role) {
            return $this;
        }

        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function setSuperAdmin($boolean): self
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): ?string
    {
        return $this->getUserIdentifier();
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }

    public function setEmailConfirmed(bool $emailConfirmed): self
    {
        $this->emailConfirmed = $emailConfirmed;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->createdAt->format('Y-m-d H:i:s'),
            $this->updatedAt->format('Y-m-d H:i:s'),
        ]);
    }

    public function unserialize($serialized): void
    {
        $data = unserialize($serialized, ['allowed_classes' => false]);

        [
            $this->id,
            $this->createdAt,
            $this->updatedAt,
        ] = $data;

        $this->createdAt = DateTime::createFromFormat('Y-m-d H:i:s', $this->createdAt);
        $this->updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $this->updatedAt);
    }

    public function isEqualTo(UserInterface $user)
    {
        return
            $user instanceof self
            && $this->id === $user->getId()
            && $this->createdAt->getTimestamp() === $user->createdAt->getTimestamp()
            && $this->updatedAt->getTimestamp() === $user->updatedAt->getTimestamp()
        ;
    }
}
