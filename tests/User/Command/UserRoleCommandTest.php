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

namespace Tests\User\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use User\Command\UserRoleCommand;
use User\Repository\UserRepository;

class UserRoleCommandTest extends KernelTestCase
{
    /**
     * @group integration
     */
    public function test no promote nor demote options throws exception(): void
    {
        $tester = $this->getCommandTester();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('You must at least specify the --promote or --demote option.');

        $tester->execute([
            'username-or-email' => '',
            'roles' => [],
        ]);
    }

    /**
     * @group integration
     */
    public function test invalid username throws exception(): void
    {
        $tester = $this->getCommandTester();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('User with username or email inexistent_user does not exist.');

        $tester->execute([
            'username-or-email' => 'inexistent_user',
            'roles' => [],
            '--promote' => true,
        ]);
    }

    /**
     * @group integration
     */
    public function test invalid roles to promote throws exception(): void
    {
        $tester = $this->getCommandTester();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Only attributes starting with "ROLE_" are valid roles.');

        $tester->execute([
            'username-or-email' => 'pierstoval',
            'roles' => ['invalid_role'],
            '--promote' => true,
        ]);
    }

    /**
     * @group integration
     */
    public function test promote with default user role throws exception(): void
    {
        $tester = $this->getCommandTester();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The role "ROLE_USER" is hard-coded and you can neither add or remove it.');

        $tester->execute([
            'username-or-email' => 'pierstoval',
            'roles' => ['ROLE_USER'],
            '--promote' => true,
        ]);
    }

    /**
     * @group integration
     */
    public function test promote with existing roles displays warning(): void
    {
        $tester = $this->getCommandTester();

        $tester->setInputs(['no']); // For dry-run

        $tester->execute([
            'username-or-email' => 'pierstoval',
            'roles' => ['ROLE_SUPER_ADMIN'],
            '--promote' => true,
        ]);

        $output = \explode("\n", $tester->getDisplay());

        static::assertStringStartsWith(' Adding roles to Pierstoval:', $output[1] ?? '');
        static::assertStringStartsWith(' * ROLE_SUPER_ADMIN', $output[3] ?? '');
        static::assertStringStartsWith(' [WARNING] User already has role "ROLE_SUPER_ADMIN', $output[5] ?? '');
        static::assertStringStartsWith(' Final roles:', $output[7] ?? '');
        static::assertStringStartsWith('  ROLE_USER', $output[10] ?? '');
        static::assertStringStartsWith('  ROLE_SUPER_ADMIN', $output[11] ?? '');
        static::assertStringStartsWith(' Dry run then!', $output[16] ?? '');
        static::assertStringStartsWith(' [OK] Done!', $output[18] ?? '');
    }

    /**
     * @group integration
     */
    public function test promote with any role effectively adds role(): void
    {
        $tester = $this->getCommandTester();

        $tester->setInputs(['yes']); // Force save

        $tester->execute([
            'username-or-email' => 'pierstoval',
            'roles' => ['ROLE_TEST'],
            '--promote' => true,
        ]);

        $output = \explode("\n", $tester->getDisplay());

        static::assertStringStartsWith(' Adding roles to Pierstoval:', $output[1] ?? '');
        static::assertStringStartsWith(' * ROLE_TEST', $output[3] ?? '');
        static::assertStringStartsWith(' Final roles:', $output[5] ?? '');
        static::assertStringStartsWith('  ROLE_USER', $output[8] ?? '');
        static::assertStringStartsWith('  ROLE_SUPER_ADMIN', $output[9] ?? '');
        static::assertStringStartsWith('  ROLE_TEST', $output[10] ?? '');
        static::assertStringStartsWith(' Saving...', $output[15] ?? '');
        static::assertStringStartsWith(' [OK] Done!', $output[17] ?? '');

        /** @var UserRepository $repo */
        $repo = static::$container->get(UserRepository::class);
        $user = $repo->loadUserByUsername('pierstoval');

        static::assertNotNull($user);
        static::assertSame(['ROLE_USER', 'ROLE_SUPER_ADMIN', 'ROLE_TEST'], $user->getRoles());
    }

    /**
     * @group integration
     */
    public function test demote with superadmin role effectively removes role(): void
    {
        $tester = $this->getCommandTester();

        $tester->setInputs(['yes']); // Force save

        $tester->execute([
            'username-or-email' => 'pierstoval',
            'roles' => ['ROLE_SUPER_ADMIN'],
            '--demote' => true,
        ]);

        $output = \explode("\n", $tester->getDisplay());

        static::assertStringStartsWith(' Removing roles to Pierstoval:', $output[1] ?? '');
        static::assertStringStartsWith(' * ROLE_SUPER_ADMIN', $output[3] ?? '');
        static::assertStringStartsWith(' Final roles:', $output[5] ?? '');
        static::assertStringStartsWith('  ROLE_USER', $output[8] ?? '');
        static::assertStringStartsWith(' Saving...', $output[13] ?? '');
        static::assertStringStartsWith(' [OK] Done!', $output[15] ?? '');

        /** @var UserRepository $repo */
        $repo = static::$container->get(UserRepository::class);
        $user = $repo->loadUserByUsername('pierstoval');

        static::assertNotNull($user);
        static::assertSame(['ROLE_USER'], $user->getRoles());
    }

    /**
     * @group integration
     */
    public function test demote with inexistent roles displays warning(): void
    {
        $tester = $this->getCommandTester();

        $tester->setInputs(['no']); // For dry-run

        $tester->execute([
            'username-or-email' => 'pierstoval',
            'roles' => ['ROLE_INEXISTENT'],
            '--demote' => true,
        ]);

        $output = \explode("\n", $tester->getDisplay());

        static::assertStringStartsWith(' Removing roles to Pierstoval:', $output[1] ?? '');
        static::assertStringStartsWith(' * ROLE_INEXISTENT', $output[3] ?? '');
        static::assertStringStartsWith(' [WARNING] User does not have role "ROLE_INEXISTENT', $output[5] ?? '');
        static::assertStringStartsWith(' Final roles:', $output[7] ?? '');
        static::assertStringStartsWith('  ROLE_USER', $output[10] ?? '');
        static::assertStringStartsWith('  ROLE_SUPER_ADMIN', $output[11] ?? '');
        static::assertStringStartsWith(' Dry run then!', $output[16] ?? '');
        static::assertStringStartsWith(' [OK] Done!', $output[18] ?? '');
    }

    private function getCommandTester(): CommandTester
    {
        return new CommandTester($this->getCommand());
    }

    private function getCommand(): UserRoleCommand
    {
        $cmd = (new Application(static::bootKernel()))->find('user:role');

        static::assertInstanceOf(UserRoleCommand::class, $cmd);

        return $cmd;
    }
}
