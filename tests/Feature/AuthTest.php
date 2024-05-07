<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
	/**
	 * A basic test example.
	 */
	public function test_the_application_returns_a_successful_response(): void
	{
		$response = $this->get('/');

		$response->assertStatus(200);
	}

	//test_login_returns_error_when_email_not_present()

	//test_login_returns_error_when_password_not_present()

	//test_login_returns_error_when_email_not_valid()

	//test_login_returns_error_when_password_not_valid()

	//test_login_returns_error_when_email_not_verified()

	//test_login_returns_error_when_credentials_are_wrong()

	//test_login_returns_success_when_password_not_valid()

	//test_app_logs_user_out_when_auth_user_not_null()

	//test_reset_returns_error_when_email_not_present

	//test reset returns error when email wrong

	//test reset returns error when emai does not exist

	//test rest email sent when correct email is sent

	//tets password is required

	//test passwords must match

	//test password is updated

	//test gmail popup runs when gmail clicked

	//test user created if did not exist && logg in

	//test user logged in if existed
}
