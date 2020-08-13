<?php

namespace Tests\Feature\Controllers\Users;

use App\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\UserController;
use Tests\Feature\Controllers\BaseControllerTest;

class DestroyMethodTest extends BaseControllerTest
{
    private $deletingUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->deletingUser = factory(User::class)->create();
        $this->data = factory(User::class)->raw();
        $this->controllerName = UserController::class;
        $this->actionName = 'destroy';
    }

    /**
     * Test controller has method
     */
    public function testDestroyActionExists()
    {
        $controller = new $this->controllerName;

        $this->assertTrue(method_exists($controller, $this->actionName));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDestroyAction()
    {
        $this->checkControllerHasMiddleware(['can:delete,user']);

        $response = (new $this->controllerName)->{$this->actionName}($this->deletingUser);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $this->assertTrue(property_exists($response->getData(), 'message'));
        $this->assertEquals(__('users.deleted'), $response->getData()->message);
    }
}
