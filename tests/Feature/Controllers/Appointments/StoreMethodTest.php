<?php

namespace Tests\Feature\Controllers\Appointments;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AppointmentController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Appointment\StoreAppointment;

class StoreMethodTest extends BaseControllerTest
{
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->data = factory(Appointment::class)->raw();
		$this->controllerName = AppointmentController::class;
		$this->formRequestName = StoreAppointment::class;
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
		$this->checkControllerHasMiddleware(['can:create,App\Models\Appointment']);
		$this->checkControllerHasFormRequest();
	
		$request = $this->getFormRequestInstance();
		$response = (new $this->controllerName)->{$this->actionName}($request);
		
		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertTrue($response->isOk());
		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
		
		$this->assertTrue(property_exists($response->getData(), 'message'));
		$this->assertEquals(__('appointments.created'), $response->getData()->message);
	}
}
