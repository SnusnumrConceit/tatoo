<?php

namespace Tests\Feature\Controllers\Users;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\UserController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\User\IndexUser;

class IndexMethodTest extends BaseControllerTest
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->controllerName = UserController::class;
        $this->formRequestName = IndexUser::class;
        $this->actionName = 'index';
    }

    /**
     * Test controller has method
     */
    public function testIndexActionExists()
    {
        $controller = new $this->controllerName;

        $this->assertTrue(method_exists($controller, $this->actionName));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndexAction()
    {
        $this->be($this->createAndGetUser(['users_view']));
        $this->checkControllerHasFormRequest();

        $request = $this->getFormRequestInstance();
        $response = (new $this->controllerName)->{$this->actionName}($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));

        $this->assertTrue(property_exists($response->getData(), 'users'));
    }
}
