<?php
/**
 * პროფილის აფდეითი
 * ციტატების დაკომენტარება + მოწონება
 * broadcasting
 */

use App\Models\Movie;
use App\Models\User;




describe('search movie', function () {
	it('redirects if unauthenticated user sends request', function () {
		$this->get(route('get-movies'))->assertRedirect();
	});
	it('returns movies if movie title contains search key', function () {
		$user = $this->user;
		$response = $this->actingAs($user)->get(route('get-movies', ['search' => 'Bam']));

		$response->assertStatus(200);
		$movies = $response->json();
		expect(collect($movies['data'])->contains('title', 'Bamby'))->toBeTrue();
	});
	it('returns movies if movie title contains search key in diff language', function () {
		$user = $this->user;
		$response = $this->actingAs($user)->get(route('get-movies', ['search' => 'ბემ']));

		$response->assertStatus(200);
		$movies = $response->json();
		expect(collect($movies['data'])->contains('title', 'Bamby'))->toBeTrue();
	});
});

describe('search quote', function () {
	it('returns quotes if key starts with @ and quote movie title contains search key for movie title', function () {
        $response = $this->actingAs($this->user)->get(route('get-quotes', ['search'=>'@Ba']));

        $response->assertStatus(200);
        $quotes = $response->json();
        expect(collect($quotes['quotes'])->contains('quote_text', 'lovely animal'))->toBeTrue();
	});
	it('doesnt return quotes if key starts with # and quote text contains search key for movie title', function () {
        $response = $this->actingAs($this->user)->get(route('get-quotes', ['search'=>'#Ba']));

        $response->assertStatus(200);
        $quotes = $response->json();
        expect(collect($quotes['quotes'])->isEmpty())->toBeTrue();
	});
    it('returns quotes if key starts with # and quote text contains search key for quote text', function () {
        $response = $this->actingAs($this->user)->get(route('get-quotes', ['search'=>'#anim']));

        $response->assertStatus(200);
        $quotes = $response->json();
        expect(collect($quotes['quotes'])->contains('quote_text', 'lovely animal'))->toBeTrue();
	});
	it('returns quotes if  quote text contains search key and search key doesnt start with @ or #', function () {
        $response = $this->actingAs($this->user)->get(route('get-quotes'), ['search', 'love']);
        $response->assertStatus(200);
        $quotes = $response->json();

        expect(collect($quotes['quotes'])->contains('quote_text', 'lovely animal'))->toBeTrue();
	});
    it('returns quotes if  movie title  contains search key and search key doesnt start with @ or #', function () {
        $response = $this->actingAs($this->user)->get(route('get-quotes'), ['search', 'Bam']);
        $response->assertStatus(200);
        $quotes = $response->json();

        expect(collect($quotes['quotes'])->contains('quote_text', 'lovely animal'))->toBeTrue();
	});
});
