<?php

namespace Tests\Feature\Controllers\Roles;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\RoleController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Role\StoreRole;

class StoreMethodTest extends BaseControllerTest
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->data = factory(Role::class)->raw();
        $this->controllerName = RoleController::class;
        $this->formRequestName = StoreRole::class;
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
        $this->checkControllerHasMiddleware(['can:create,App\Models\Role']);
        $this->checkControllerHasFormRequest();
    
        $request = $this->getFormRequestInstance();
        $response = (new $this->controllerName)->{$this->actionName}($request);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        
        $this->assertTrue(property_exists($response->getData(), 'message'));
        $this->assertEquals(__('roles.created'), $response->getData()->message);
    }
}
