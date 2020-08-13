<?php

namespace Tests\Feature\Controllers\Users;

use App\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\UserController;
use Tests\Feature\Controllers\BaseControllerTest;

class ShowMethodTest extends BaseControllerTest
{
    private $showingUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controllerName = UserController::class;
        $this->actionName = 'show';
        $this->showingUser = factory(User::class)->create();
    }

    /**
     * Test controller has method
     */
    public function testShowActionExists()
    {
        $controller = new $this->controllerName;

        $this->assertTrue(method_exists($controller, $this->actionName));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShowAction()
    {
        $this->checkControllerHasMiddleware(['can:view,user']);

        $response = (new $this->controllerName)->{$this->actionName}($this->showingUser);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $this->assertTrue(property_exists($response->getData(), 'user'));
        $this->assertTrue(property_exists($response->getData()->user, 'full_name'));
        $this->assertTrue(property_exists($response->getData()->user, 'email'));
        $this->assertTrue(property_exists($response->getData()->user, 'birthday'));
        $this->assertTrue(property_exists($response->getData()->user, 'created_at'));
        $this->assertTrue(property_exists($response->getData()->user, 'orders'));
        $this->assertTrue(property_exists($response->getData()->user, 'permissions'));

        $this->assertInstanceOf(User::class, $response->getOriginalContent());
    }
}
