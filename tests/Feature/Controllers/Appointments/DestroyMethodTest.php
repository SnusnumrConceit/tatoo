<?php

namespace Tests\Feature\Controllers\Appointments;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AppointmentController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Appointment\DeleteAppointment;

class DestroyMethodTest extends BaseControllerTest
{
	private $deletingAppointment;
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->deletingAppointment = factory(Appointment::class)->create();
		$this->data = factory(Appointment::class)->raw();
		$this->controllerName = AppointmentController::class;
		$this->formRequestName = DeleteAppointment::class;
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
		$this->checkControllerHasMiddleware(['can:delete,appointment']);
		$this->checkControllerHasFormRequest();
	
		$request = $this->getFormRequestInstance();
		$response = (new $this->controllerName)->{$this->actionName}($request, $this->deletingAppointment);
		
		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertTrue($response->isOk());
		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
		
		$this->assertTrue(property_exists($response->getData(), 'message'));
		$this->assertEquals(__('appointments.deleted'), $response->getData()->message);
	}
}
