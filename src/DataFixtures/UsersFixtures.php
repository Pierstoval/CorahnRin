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

namespace DataFixtures;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Orbitale\Component\ArrayFixture\ArrayFixture;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use User\Document\User;
use User\Util\CanonicalizerTrait;

final class UsersFixtures extends ArrayFixture implements ORMFixtureInterface
{
    use CanonicalizerTrait;

    /**
     * @var PasswordHasherInterface
     */
    private $passwordEncoder;

    private int $increment = 0;

    public function __construct(PasswordHasherFactoryInterface $encoderFactory)
    {
        $this->passwordEncoder = $encoderFactory->getPasswordHasher($this->getEntityClass());

        parent::__construct();
    }

    public function getEntityClass(): string
    {
        return User::class;
    }

    public function getReferencePrefix(): ?string
    {
        return 'user-';
    }

    public function getMethodNameForReference(): string
    {
        return 'getUsernameCanonical';
    }

    public function getObjects(): iterable
    {
        $this->increment = 0;

        yield $this->user('Pierstoval', ['ROLE_USER', 'ROLE_SUPER_ADMIN'], 'admin', 'alex@orbitale.io');
        yield $this->user('map-subscribed', ['ROLE_USER']);
        yield $this->user('lambda-user', ['ROLE_USER']);
        yield $this->user('game-master', ['ROLE_USER']);
        yield $this->user('standard-admin', ['ROLE_ADMIN']);
        yield $this->user('vermine-admin', ['ROLE_PRODUCTS_ADMIN']);
        yield $this->user('vermine-builder', ['ROLE_VERMINE_BUILDER']);
    }

    private function user(string $username, array $roles = [], string $password = 'foobar', ?string $email = null): array
    {
        if (!$email) {
            $email = 'foo'.($this->increment++).'@bar.com';
        }

        return [
            'username' => $username,
            'usernameCanonical' => $this->canonicalize($username),
            'email' => $email,
            'emailCanonical' => $this->canonicalize($email),
            'password' => $this->passwordEncoder->hash($password),
            'roles' => $roles,
            'emailConfirmed' => true,
        ];
    }
}
