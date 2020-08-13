<?php

namespace Tests\Feature\Controllers\Users;

use App\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\UserController;
use App\Http\Requests\Admin\User\UpdateUser;
use Tests\Feature\Controllers\BaseControllerTest;

class UpdateMethodTest extends BaseControllerTest
{
    private $updatingUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->updatingUser = factory(User::class)->create();
        $this->data = factory(User::class)->raw();
        $this->controllerName = UserController::class;
        $this->formRequestName = UpdateUser::class;
        $this->actionName = 'update';
    }

    /**
     * Test controller has method
     *
     * @return void
     */
    public function testUpdateActionExists()
    {
        $controller = new $this->controllerName;

        $this->assertTrue(method_exists($controller, $this->actionName));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUpdateAction()
    {
        $this->checkControllerHasMiddleware(['can:update,user']);
        $this->checkControllerHasFormRequest();

        $request = $this->getFormRequestInstance();
        $response = (new $this->controllerName)->{$this->actionName}($request, $this->updatingUser);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $this->assertTrue(property_exists($response->getData(), 'message'));
        $this->assertEquals(__('users.updated'), $response->getData()->message);
    }
}
