<?php

namespace Tests\Feature\Controllers\Roles;

use App\Models\Role;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\RoleController;
use Tests\Feature\Controllers\BaseControllerTest;

class ShowMethodTest extends BaseControllerTest
{
    private $showingRole;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->controllerName = RoleController::class;
        $this->actionName = 'show';
        $this->showingRole = factory(Role::class)->create();
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
        $this->checkControllerHasMiddleware(['can:view,role']);
        
        $response = (new $this->controllerName)->{$this->actionName}($this->showingRole);
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        
        $this->assertTrue(property_exists($response->getData(), 'role'));
        $this->assertTrue(property_exists($response->getData()->role, 'name'));
        $this->assertTrue(property_exists($response->getData()->role, 'slug'));
        $this->assertTrue(property_exists($response->getData()->role, 'is_protected'));
        $this->assertInstanceOf(Role::class, $response->getOriginalContent());
    }
}
