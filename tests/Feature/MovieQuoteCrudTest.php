<?php

describe('quote tests', function () {
	it('redirects if unauthenticated user tries to add quote', function () {
		$response = $this->post(route('store-quote'), ['movie_id' => '1', 'quote_en' => 'I love Bamby', 'quote_ge' => 'მე მიყვარს ბემბი', 'image' => $this->image])->assertRedirect();
		$response->assertRedirect();
	});
	it('resutns error if trying to add quote and data is not full', function () {
		$response = $this->actingAs($this->user)->post(route('store-quote'), ['quote_en' => 'I love Bamby', 'quote_ge' => 'მე მიყვარს ბემბი', 'image' => $this->image])->assertRedirect();
		$response->assertSessionHasErrors([
			'movie_id' => 'The movie id field is required.',
		]);
	});
	it('resutns error if trying to add quote and image is wrong', function () {
		$response = $this->actingAs($this->user)->post(route('store-quote'), ['movie_id' => '1', 'quote_en' => 'I love Bamby', 'quote_ge' => 'მე მიყვარს ბემბი', 'image' => 'dsdcsd'])->assertRedirect();
		$response->assertSessionHasErrors([
			'image'    => 'The image field must be an image.',
		]);
	});
	it('resutns error if trying to add quote where quote text in en is in georgian letters', function () {
		$response = $this->actingAs($this->user)->post(route('store-quote'), ['movie_id' => '1', 'quote_en' => 'მე მიყვარს ბემბი', 'quote_ge' => 'მე მიყვარს ბემბი', 'image' => $this->image])->assertRedirect();
    $response->assertSessionHasErrors([
			'quote_en'    => 'The quote en field format is invalid.',
		]);
	});
	it('resutns error if trying to add quote where quote text in ge is in latin letters', function () {
		$response = $this->actingAs($this->user)->post(route('store-quote'), ['movie_id' => '1', 'quote_en' => 'I love Bamby', 'quote_ge' => 'I love Bamby', 'image' => $this->image])->assertRedirect();
		$response->assertSessionHasErrors([
			'quote_ge'    => 'The quote ge field format is invalid.',
		]);
	});
	it('adds new quote if data is correct', function () {
		$response = $this->actingAs($this->user)->post(route('store-quote'), ['movie_id' => '1', 'quote_en' => 'I love Bamby', 'quote_ge' => 'მე მიყვარს ბემბი', 'image' => $this->image]);
		$response->assertStatus(204);
		
		$this->assertDatabaseHas('quotes', [
			'movie_id' => '1',
			'quote->en' => 'I love Bamby',
			'quote->ge' => 'მე მიყვარს ბემბი',
		]);
	});
	it('redirects if unauthenticated user tries to update quote', function () {
		$this->post(route('update-quote', ['quote' => 1, 'quote_en' => 'lovliest animal']))->assertRedirect();
		
	});
	it('updates new quote if data is correct', function () {
		$response = $this->actingAs($this->user)->post(route('update-quote', ['quote' => 1, 'quote_en' => 'lovliest animal']));
		$response->assertStatus(204);
		$this->assertDatabaseHas('quotes', [
			'movie_id' => '1',
			'quote->en'=>'lovliest animal'

		]);
	});
	it('redirects if unauthenticated user tries to delete quote', function () {
		$this->get(route('delete-quote', ['quote'=> '1']))->assertRedirect();
	});
	it('deletes quote if auth user provides id of quote', function () {
		$this->actingAs($this->user)->get(route('delete-quote', ['quote'=> '1']));
		$this->assertDatabaseMissing('quotes', [
			'id'=>'1'
		]);
	});
});

describe('movie tests', function () {
	it('redirects if unauthenticated user tries to add movie', function () {
		$response = $this->post(route('add-movie'), ['name_en' => 'Dumb and dumber', 'name_ge' => 'ლენჩი და უფრო ლენჩი', 'director_en' => 'Some Guy', 'director_ge' => 'ერთი კაცი', 'description_en'=>'great comedy', 'description_ge'=>'მაგარი კომედია','year'=>'2003', 'user_id'=>'1' ])->assertRedirect();
		$response->assertRedirect();
	});
	it('resutns error if trying to add movie and data is not full', function () {
		$response = $this->actingAs($this->user)->post(route('add-movie'), ['name_ge' => 'ლენჩი და უფრო ლენჩი', 'director_en' => 'Some Guy', 'director_ge' => 'ერთი კაცი', 'description_en'=>'great comedy', 'description_ge'=>'მაგარი კომედია','year'=>'2003', 'user_id'=>'1' ])->assertRedirect();
		$response->assertSessionHasErrors([
			'name_en' => 'The name en field is required.',
		]);
	});
	it('resutns error if trying to add movie where movie name in en is in georgian letters', function () {
		$response = $this->actingAs($this->user)->post(route('add-movie'), ['name_en' => 'ლენჩი და უფრო ლენჩი', 'name_ge' => 'ლენჩი და უფრო ლენჩი', 'director_en' => 'Some Guy', 'director_ge' => 'ერთი კაცი', 'description_en'=>'great comedy', 'description_ge'=>'მაგარი კომედია','year'=>'2003', 'user_id'=>'1' ])->assertRedirect();
		$response->assertSessionHasErrors([
			'name_en'    => 'The name en field format is invalid.',
		]);
	});
	it('resutns error if trying to add movie where movie description in ge is in latin letters', function () {
		$response = $this->actingAs($this->user)->post(route('add-movie'), ['name_en' => 'Dumb and dumber', 'name_ge' => 'ლენჩი და უფრო ლენჩი', 'director_en' => 'Some Guy', 'director_ge' => 'ერთი კაცი', 'description_en'=>'great comedy', 'description_ge'=>'great comedy','year'=>'2003', 'user_id'=>'1' ])->assertRedirect();
    	$response->assertSessionHasErrors([
			'description_ge'    => 'The description ge field format is invalid.',
		]);
	});
	it('adds new quote if data is correct', function () {
		$response = $this->actingAs($this->user)->post(route('add-movie'), ['name_en' => 'Dumb and dumber', 'name_ge' => 'ლენჩი და უფრო ლენჩი', 'director_en' => 'Some Guy', 'director_ge' => 'ერთი კაცი', 'description_en'=>'great comedy', 'description_ge'=>'მაგარი კომედია','year'=>'2003', 'user_id'=>'1', 'genre'=>'1', 'image'=>$this->image]);
		$response->assertStatus(204);
		
		$this->assertDatabaseHas('movies', [
			'title->en' => 'Dumb and dumber',
			'title->ge' => 'ლენჩი და უფრო ლენჩი',
		]);
	});

	it('redirects if unauthenticated user tries to update movie', function () {
		$this->post(route('edit-movie', ['movie' => 1, 'name_ge' => 'ლენჩი და უფრო გამოლენჩებული']))->assertRedirect();
		
	});
	it('updates movie if data is correct', function () {
		$response = $this->actingAs($this->user)->post(route('edit-movie', ['movie' => 1, 'name_ge' => 'ლენჩი და უფრო გამოლენჩებული']));
		$response->assertStatus(204);
		$this->assertDatabaseHas('movies', [
			'id' => '1',
			'title->ge' => 'ლენჩი და უფრო გამოლენჩებული'

		]);
	});
	it('redirects if unauthenticated user tries to delete movie', function () {
		$this->get(route('delete-movie', ['movie'=> '1']))->assertRedirect();
	});
	it('deletes movie if auth user provides id of movie', function () {
		$this->actingAs($this->user)->get(route('delete-movie', ['movie'=> '1']));
		$this->assertDatabaseMissing('movies', [
			'id'=>'1'
		]);
	});
});