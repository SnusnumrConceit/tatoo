<?php

namespace Tests;

use App\User;
use JMac\Testing\Traits\AdditionalAssertions;

class BaseTest extends TestCase
{
    use AdditionalAssertions;
    
    protected $data = [];
    
    protected function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * Create and get created user
     *
     * @param array $permissions
     *
     * @return User
     */
    protected function createAndGetUser(array $permissions = [])
    {
        $user = factory(User::class)->create();
        
        if ($permissions) {
            $user->permissions()->sync($permissions);
        }
        
        return $user;
    }
}