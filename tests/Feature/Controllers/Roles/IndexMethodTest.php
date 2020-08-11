<?php

namespace Tests\Feature\Controllers\Roles;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\RoleController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Role\IndexRole;

class IndexMethodTest extends BaseControllerTest
{
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->controllerName = RoleController::class;
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
        $this->be($this->createAndGetUser(['roles_view']));
    
        $response = (new $this->controllerName)->{$this->actionName}();
        
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue($response->isOk());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        
        $this->assertTrue(property_exists($response->getData(), 'roles'));
    }
}
