<?php

namespace Tests\Feature\Controllers\Roles;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\RoleController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Role\UpdateRole;

class UpdateMethodTest extends BaseControllerTest
{
    private $updatingRole;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->updatingRole = factory(Role::class)->create();
        $this->data = factory(Role::class)->raw();
        $this->controllerName = RoleController::class;
        $this->formRequestName = UpdateRole::class;
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
        $this->checkControllerHasMiddleware(['can:update,role']);
        $this->checkControllerHasFormRequest();
    
        $request = $this->getFormRequestInstance();
        $response = (new $this->controllerName)->{$this->actionName}($request, $this->updatingRole);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        
        $this->assertTrue(property_exists($response->getData(), 'message'));
        $this->assertEquals(__('roles.updated'), $response->getData()->message);
    }
}
