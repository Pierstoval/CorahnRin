<?php

namespace Tests\Admin;

use User\Repository\UserRepository;

trait AdminLoginTrait
{
    public function setUp(): void
    {
        parent::setUp();

        self::$authenticationFirewallContext = 'main';
        self::$easyAdminRoutePath = '/fr/admin';

        self::bootKernel();
        $user = self::getContainer()->get(UserRepository::class)->findByUsernameOrEmail('Pierstoval');
        $this->loginAs($user);
    }
}
