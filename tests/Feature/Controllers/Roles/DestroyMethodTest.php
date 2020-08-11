<?php

namespace Tests\Feature\Controllers\Roles;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\RoleController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Role\DestroyRole;

class DestroyMethodTest extends BaseControllerTest
{
    private $deletingRole;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->deletingRole = factory(Role::class)->create();
        $this->data = factory(Role::class)->raw();
        $this->controllerName = RoleController::class;
        $this->formRequestName = DestroyRole::class;
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
        $this->checkControllerHasMiddleware(['can:delete,role']);
        $this->checkControllerHasFormRequest();
    
        $request = $this->getFormRequestInstance();
        $response = (new $this->controllerName)->{$this->actionName}($request, $this->deletingRole);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        
        $this->assertTrue(property_exists($response->getData(), 'message'));
        $this->assertEquals(__('roles.deleted'), $response->getData()->message);
    }
}
