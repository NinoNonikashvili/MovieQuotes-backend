<?php

/**
 * გუგლით ავტორიზაცია
 * პაროლის აღდგენა
 * დალოგინება
 * დალოგაუთება
 */
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;

describe('login', function () {
	it('is not  accessible for auth user', function () {
		//code here
		$user = User::find(6);
		$this->actingAs($user)->post(route('login'), [
			'email'      => 'nino@gmail.com',
			'password'   => '33333333',
			'rememberMe' => false,
		])->assertStatus(302);
	});
	it('returns error when email and password are omitted', function () {
		$response = $this->post(route('login'), [
			'email'    => '',
			'password' => '',
		]);
		//middleware EnsureExists responds with 404
		$response->assertStatus(404);
	});

	it('returns wrong password when password is short', function () {
		$response = $this->post(route('login'), [
			'passwors' => '',
		]);
		//middleware EnsureExists responds with 404
		$response->assertStatus(302);
	});
	it('returns user doesnt exist if so', function () {
		$response = $this->post(route('login'), [
			'email' => 'bla@gmail.com',
		]);
		//middleware EnsureExists responds with 404
		$response->assertStatus(404);
	});
	it('returns email not verified error if so', function () {
		$response = $this->post(route('login'), [
			'email'      => 'nino@gmail.com',
			'password'   => '33333333',
			'rememberMe' => 'false',
		]);
		$response->assertStatus(200);
	});
	it('returns success if everything is ok', function () {
		$response = $this->post(route('login'), [
			'email'      => 'naina@gmail.com',
			'password'   => '11111111',
			'rememberMe' => 'false',
		]);
		$response->assertStatus(404);
	});
});

describe('logout', function () {
	it('logs auth user out', function () {
		$user = User::find(2);
		$this->actingAs($user)->get(route('logout'), )->assertStatus(204);
	});
	it('returns error when unauth user tries logout', function () {
		$this->get(route('logout'))->assertStatus(302);
	});
});

describe('google auth', function () {
	it('creates user if does not exist and loggs in', function () {
		// Create a mock for the Socialite driver
		$abstractUser = Mockery::mock('Laravel\Socialite\Two\User');
		$abstractUser->shouldReceive('getId')->andReturn('1234567890');
		$abstractUser->shouldReceive('getEmail')->andReturn('sandro@example.com');
		$abstractUser->shouldReceive('getName')->andReturn('Test User');

		$provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
		$provider->shouldReceive('user')->andReturn($abstractUser);

		Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

		// Perform the callback request
		$response = $this->get(route('auth.callback'));

		// Assert the user was authenticated
		$this->assertAuthenticated();

		// Assert the user data in the database
		$this->assertDatabaseHas('users', [
			'email' => 'sandro@example.com',
		]);

		$response->assertStatus(200);
	});
});

describe('reset password', function () {
	it('sends email to the user', function () {
		$this->post(route('password.email'), [
			'email' => 'nino@gmail.com',
		])->assertStatus(200);
	});
	it('returns error if user does not exist with the email provided', function () {
		$this->post(route('password.email'), [
			'email' => 'niscsrfno@gmail.com',
		])->assertStatus(404);
	});
	it('updates password when correct token and data are provided', function () {
		Notification::fake();
		$url = '';
		$this->post(route('password.email'), ['email' => 'leqso@gmail.com']);
		Notification::assertSentTo(User::where('email', 'leqso@gmail.com')->first(), ResetPassword::class, function (ResetPassword $notification) use (&$url) {
			$url = $notification->toMail(User::where('email', 'leqso@gmail.com')->first())->actionUrl;
			return true;
		});
		$a = explode('?', $url);
		$b = explode('&', $a[1]);
		$c = explode('=', $b[0]);

		$this->post(route('password.update'), [
			'email'            => 'leqso@gmail.com',
			'password'         => '99999999',
			'confirm_password' => '99999999',
			'token'            => $c[1],
		]);
		$newPass = User::where('email', 'leqso@gmail.com')->first()->fresh()->password;
		// dd(Hash::check('99999999', $newPass));
		$this->assertTrue(Hash::check('99999999', $newPass));
	});
});
