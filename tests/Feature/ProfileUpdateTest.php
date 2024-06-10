<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

describe('profile update tests', function () {
	it('redirects if unauth user tried to update', function(){
	    $this->post(route('update-profile'))->assertRedirect();
	});
	it('returns error if name is less than 3 chars', function(){
	    $response = $this->actingAs($this->user)->post(route('update-profile', ['name'=> 'No']));
	    $response->assertSessionHasErrors([
			'name' => 'The name field must be at least 3 characters.',
		]);
	});
	it('returns error if name is more than 15 chars', function(){
	    $response = $this->actingAs($this->user)->post(route('update-profile', ['name'=> 'Nodsdsdsdsdsdsdsds']));
	    $response->assertSessionHasErrors([
			'name' => 'The name field must not be greater than 15 characters.',
		]);
	});
	it('returns error if name is in other but latin', function(){
	    $response = $this->actingAs($this->user)->post(route('update-profile', ['name'=> 'ნინო']));
	    $response->assertSessionHasErrors([
			'name'    => 'The name field format is invalid.',
		]);
	});
	it('updates name successfully if data is valid', function(){
	    $response = $this->actingAs($this->user)->post(route('update-profile', ['name'=> 'niano']));
	    // dd($response);
	    $response->assertStatus(204);
	    $this->assertDatabaseHas('users',[
	        'name' => 'Niano'
	    ]);
	});
	it('returns error if password is less than 3 chars', function () {
		$response = $this->actingAs($this->user)->post(route('update-profile', ['password'=> 'No']));
		$response->assertSessionHasErrors([
			'password' => 'The password field must be at least 8 characters.',
		]);
	});
	it('returns error if password is more than 15 chars', function () {
		$response = $this->actingAs($this->user)->post(route('update-profile', ['password'=> 'Nodsdsdsdsdsdsdsds']));
		$response->assertSessionHasErrors([
			'password' => 'The password field must not be greater than 15 characters.',
		]);
	});
	it('returns error if password_confirmation is not same as password', function () {
		$response = $this->actingAs($this->user)->post(route('update-profile', ['password'=> 'sdcsdsdscs', 'password_confirmation'=> 'sdcsdssddsdsdscs']));
        $response->assertSessionHasErrors([
			'password_confirmation' => 'The password confirmation field must match password.',
		]);
	});
	it('updates password successfully if data is valid', function () {
        $this->user->password = bcrypt('lalalalala');
        $this->user->save();
        $this->assertFalse(bcrypt('12345678') === $this->user->password);

        $response = $this->actingAs($this->user)->post(route('update-profile'), [
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);
    
        $response->assertStatus(204);

        $this->assertTrue(Hash::check('12345678', User::find(1)->password));
	});
	it('updates image successfully if image file is provided', function () {
		$response = $this->actingAs($this->user)->post(route('update-profile', ['image'=>$this->image]));
		$response->assertStatus(204);
	});
});
