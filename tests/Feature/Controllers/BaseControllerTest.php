<?php

namespace Tests\Feature\Controllers;


use Tests\BaseTest;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BaseControllerTest extends BaseTest
{
	use DatabaseTransactions;
	
	protected 	$controllerName,
				$actionName,
				$formRequestName;
	
	protected function setUp(): void
	{
		parent::setUp();
	}
	
	/**
	 * Check controller has middlewares
	 *
	 * @param array $middlewares
	 */
	protected function checkControllerHasMiddleware(array $middlewares)
	{
		$this->assertActionUsesMiddleware($this->controllerName, $this->actionName, $middlewares);
	}
	
	/**
	 * Check controller has form request
	 */
	public function checkControllerHasFormRequest()
	{
		$this->assertActionUsesFormRequest($this->controllerName, $this->actionName, $this->formRequestName);
	}
	
	/**
	 * Get form request instance
	 *
	 * @return mixed
	 */
	protected function getFormRequestInstance()
	{
		$request = $this->createFormRequest($this->formRequestName, $this->data);
		$request->setValidator($this->app['validator']->make($request->input(), $request->rules()));
		
		return $request;
	}
}