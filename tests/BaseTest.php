<?php

namespace Tests;

use JMac\Testing\Traits\AdditionalAssertions;

class BaseTest extends TestCase
{
	use AdditionalAssertions;
	
	protected $data = [];
	
	protected function setUp(): void
	{
		parent::setUp();
	}
	
	/**
	 * Create and get created user
	 *
	 * @return mixed
	 */
	protected function createAndGetUser()
	{
		return factory('App\User')->create();
	}
}