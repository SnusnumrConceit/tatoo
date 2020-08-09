<?php

namespace Tests\Feature\Controllers\Appointments;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AppointmentController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Appointment\IndexAppointment;

class IndexMethodTest extends BaseControllerTest
{
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->controllerName = AppointmentController::class;
		$this->formRequestName = IndexAppointment::class;
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
		$this->checkControllerHasFormRequest();
	
		$this->be($this->createAndGetUser());
	
		$request = $this->getFormRequestInstance();
		$response = (new $this->controllerName)->{$this->actionName}($request);
		
		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertTrue($response->isOk());
		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
		
		$this->assertTrue(property_exists($response->getData(), 'appointments'));
	}
}
