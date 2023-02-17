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

namespace Tests\Admin;

use Admin\Controller\UsersCrudController;
use Protung\EasyAdminPlusBundle\Test\Controller\NewActionTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use User\Repository\UserRepository;

class UserAdminTest extends NewActionTestCase
{
    use AdminLoginTrait;

    protected static ?string $expectedPageTitle = 'Créer "User"';

    protected function controllerUnderTest(): string
    {
        return UsersCrudController::class;
    }

    public function getEmptyData(): array
    {
        return [
            'username' => '',
            'email' => '',
            'plainPassword' => '',
        ];
    }

    public function testDefaultEmptyData(): void
    {
        $this->assertShowingEntityDefaultData($this->getEmptyData());
    }

    public function provideErrorSets(): \Generator
    {
        $emptyData = $this->getEmptyData();

        yield 'empty everything' => [
            [
                'username' => ['Cette valeur ne doit pas être vide.'],
                'email' => ['Cette valeur ne doit pas être vide.'],
                'plainPassword' => [],
            ],
            $emptyData,
        ];

        yield 'empty everything except invalid email' => [
            [
                'username' => ['Cette valeur ne doit pas être vide.'],
                'email' => ['Cette valeur n\'est pas une adresse email valide.'],
                'plainPassword' => [],
            ],
            array_merge($emptyData, [
                'email' => 'something',
            ])
        ];
    }

    /**
     * @dataProvider provideErrorSets
     */
    public function testNewWithErrors(array $expectedFormErrors, array $formData): void
    {
        $this->assertSubmittingFormAndShowingValidationErrors($expectedFormErrors, $formData);
    }

    public function testWithValidEntityAndNoPassword(): void
    {
        $this->assertSavingEntityAndRedirectingToIndexAction(
            [
                'username' => 'new-user-from-admin',
                'email' => 'new-user-from-admin@domain.local',
            ],
        );

        $user = self::getContainer()->get(UserRepository::class)->findOneBy([], ['id' => 'DESC']);
        self::assertNotNull($user);
        self::assertSame('new-user-from-admin', $user->getUsername());
        self::assertSame('new-user-from-admin@domain.local', $user->getEmail());
        self::assertNotNull($user->getConfirmationToken());
    }

    public function testWithValidEntityAndCustomPassword(): void
    {
        $password = 'foobar';

        $this->assertSavingEntityAndRedirectingToIndexAction(
            [
                'username' => 'new-user-from-admin',
                'email' => 'new-user-from-admin@domain.local',
                'plainPassword' => $password,
            ],
        );

        $user = self::getContainer()->get(UserRepository::class)->findOneBy([], ['id' => 'DESC']);
        self::assertNotNull($user);
        self::assertSame('new-user-from-admin', $user->getUsername());
        self::assertSame('new-user-from-admin@domain.local', $user->getEmail());
        self::assertNull($user->getConfirmationToken());
        self::assertTrue(self::getContainer()->get(PasswordHasherFactoryInterface::class)->getPasswordHasher($user)->verify($user->getPassword(), $password));
    }
}
