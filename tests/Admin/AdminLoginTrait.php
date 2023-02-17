<?php

namespace Tests\Admin;

use Protung\EasyAdminPlusBundle\Test\Controller\AdminControllerWebTestCase;
use User\Repository\UserRepository;

trait AdminLoginTrait
{
    public function setUp(): void
    {
        parent::setUp();

        self::$authenticationFirewallContext = 'main';
        if (is_a(self::class, AdminControllerWebTestCase::class, true)) {
            self::$easyAdminRoutePath = '/fr/admin';
        }

        self::bootKernel();
        $user = self::getContainer()->get(UserRepository::class)->findByUsernameOrEmail('Pierstoval');
        $this->loginAs($user);
    }
}
