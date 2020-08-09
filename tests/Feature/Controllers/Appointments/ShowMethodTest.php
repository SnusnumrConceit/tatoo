<?php

namespace Tests\Feature\Controllers\Appointments;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\AppointmentController;
use Tests\Feature\Controllers\BaseControllerTest;

class ShowMethodTest extends BaseControllerTest
{
	private $showingAppointment;
	
	protected function setUp(): void
	{
		parent::setUp();
		
		$this->controllerName = AppointmentController::class;
		$this->actionName = 'show';
		$this->showingAppointment = factory(Appointment::class)->create();
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
		$this->checkControllerHasMiddleware(['can:view,appointment']);
		
		$response = (new $this->controllerName)->{$this->actionName}($this->showingAppointment);
		
		$this->assertInstanceOf(JsonResponse::class, $response);
		$this->assertTrue($response->isOk());
		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
		
		$this->assertTrue(property_exists($response->getData(), 'appointment'));
		$this->assertTrue(property_exists($response->getData()->appointment, 'id'));
		$this->assertTrue(property_exists($response->getData()->appointment, 'name'));
		$this->assertInstanceOf(Appointment::class, $response->getOriginalContent());
	}
}
