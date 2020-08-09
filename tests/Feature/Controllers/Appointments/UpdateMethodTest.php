<?php

namespace Tests\Feature\Controllers\Appointments;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AppointmentController;
use Tests\Feature\Controllers\BaseControllerTest;
use App\Http\Requests\Admin\Appointment\UpdateAppointment;

class UpdateMethodTest extends BaseControllerTest
{
	private $updatingAppointment;
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->updatingAppointment = factory(Appointment::class)->create();
		$this->data = factory(Appointment::class)->raw();
		$this->controllerName = AppointmentController::class;
		$this->formRequestName = UpdateAppointment::class;
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
		$this->checkControllerHasMiddleware(['can:update,appointment']);
		$this->checkControllerHasFormRequest();
	
		$request = $this->getFormRequestInstance();
		$response = (new $this->controllerName)->{$this->actionName}($request, $this->updatingAppointment);
		
		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertTrue($response->isOk());
		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
		
		$this->assertTrue(property_exists($response->getData(), 'message'));
		$this->assertEquals(__('appointments.updated'), $response->getData()->message);
	}
}
