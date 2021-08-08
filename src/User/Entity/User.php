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

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use User\Util\Canonicalizer;

/**
 * @ORM\Entity(repositoryClass="User\Repository\UserRepository")
 * @ORM\Table(name="fos_user_user")
 * @UniqueEntity("emailCanonical", message="user.email.already_used")
 * @UniqueEntity("usernameCanonical", message="user.username.already_used")
 */
class User implements UserInterface, \Serializable, EquatableInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;

    public const ROLE_DEFAULT = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="username", type="string")
     *
     * @Assert\NotBlank
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="username_canonical", type="string", unique=true)
     */
    protected $usernameCanonical;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     *
     * @Assert\NotBlank
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_canonical", type="string", unique=true)
     */
    protected $emailCanonical;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     *
     * @ORM\Column(name="password", type="string")
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var null|string
     */
    protected $plainPassword;

    /**
     * @var null|string
     *
     * @ORM\Column(name="confirmation_token", type="string", unique=true, nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @var bool
     *
     * @ORM\Column(name="email_confirmed", type="boolean", options={"default" = "0"})
     */
    private $emailConfirmed = false;

    public function __construct()
    {
        $this->roles = [static::ROLE_DEFAULT];
    }

    public function __toString()
    {
        return (string) $this->username;
    }

    public static function create(string $username, string $email, string $encodedPassword): self
    {
        $user = new self();

        $user->username = $username;
        $user->usernameCanonical = Canonicalizer::urlize($username);
        $user->email = $email;
        $user->emailCanonical = Canonicalizer::urlize($email);
        $user->password = $encodedPassword;

        return $user;
    }

    public function addRole($role): void
    {
        $role = \mb_strtoupper($role);

        if ($role === static::ROLE_DEFAULT) {
            return;
        }

        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        $this->roles = \array_unique($this->roles);
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return \array_unique($roles);
    }

    public function hasRole($role): bool
    {
        if (self::ROLE_DEFAULT === $role) {
            return true;
        }

        return \in_array(\mb_strtoupper($role), $this->getRoles(), true);
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

        if (false !== $key = \array_search(\mb_strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = \array_values($this->roles);
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

    public function getUsername(): ?string
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

    public function getUserIdentifier(): string
    {
        return (string) $this->id;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setUsernameCanonical($usernameCanonical): self
    {
        $this->usernameCanonical = $usernameCanonical;

        return $this;
    }

    public function getUsernameCanonical(): ?string
    {
        return $this->usernameCanonical;
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

    public function setEmailCanonical($emailCanonical): self
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
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
        return \serialize([
            $this->id,
            $this->createdAt->format('Y-m-d H:i:s'),
            $this->updatedAt->format('Y-m-d H:i:s'),
        ]);
    }

    public function unserialize($serialized): void
    {
        $data = \unserialize($serialized, ['allowed_classes' => false]);

        [
            $this->id,
            $this->createdAt,
            $this->updatedAt,
        ] = $data;

        $this->createdAt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->createdAt);
        $this->updatedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $this->updatedAt);
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
