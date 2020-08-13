<?php

namespace Tests\Feature\Controllers\Users;

use App\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\UserController;
use App\Http\Requests\Admin\User\StoreUser;
use Tests\Feature\Controllers\BaseControllerTest;

class StoreMethodTest extends BaseControllerTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->data = factory(User::class)->raw();
        $this->controllerName = UserController::class;
        $this->formRequestName = StoreUser::class;
        $this->actionName = 'store';
    }

    /**
     * Test controller has method
     */
    public function testStoreActionExists()
    {
        $controller = new $this->controllerName;

        $this->assertTrue(method_exists($controller, $this->actionName));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreAction()
    {
        $this->checkControllerHasMiddleware(['can:create,App\User']);
        $this->checkControllerHasFormRequest();

        $request = $this->getFormRequestInstance();
        $response = (new $this->controllerName)->{$this->actionName}($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $this->assertTrue(property_exists($response->getData(), 'message'));
        $this->assertEquals(__('users.created'), $response->getData()->message);
    }
}
