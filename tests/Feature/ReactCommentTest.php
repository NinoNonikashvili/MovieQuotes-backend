<?php



describe('quote notification tests', function(){
    it('redirects if unauth user tries to add notification', function(){
        $this->post(route('add-quote-notification', []))->assertRedirect();
    });
    it('return error if  quote_id, user_id, and type is not provided ', function(){
        $response = $this->actingAs($this->user)->post(route('add-quote-notification', []));
        $response->assertSessionHasErrors([
			'quote_id' => 'The quote id field is required.',
            'user_id' => 'The user id field is required.',
            'type' => 'The type field is required.',
		]);
    });

    it('return error if  type===comment and comment is not provided ', function(){
        $response = $this->actingAs($this->user)->post(route('add-quote-notification', ['quote_id' => '1', 'user_id'=>'1', 'type'=>'comment']));
        $response->assertSessionHasErrors([
			'comment' => 'The comment field is required.'
		]);
    });
    it('creates  notification ', function(){
        $response = $this->actingAs($this->user)->post(route('add-quote-notification', ['quote_id' => '1', 'user_id'=>'1', 'type'=>'comment', 'comment'=>'great quote']));
        $response->assertStatus(204);
        $this->assertDatabaseHas('notifications',[
            'quote_id' => '1', 
            'user_id'=>'1', 
            'type'=>'comment', 
            'comment'=>'great quote'
        ]);
    });

    it('adds user as reactor in reactions(quote-user-many-to-many-table) if type===react  ', function(){
        $this->actingAs($this->user)->post(route('add-quote-notification', ['quote_id' => '1', 'user_id'=>'1', 'type'=>'react']));
        $this->assertDatabaseHas('reactions', ['user_id' => '1', 'quote_id'=> '1']);

    });
    it('removes user as reactor from reactions(quote-user-many-to-many-table) if type===unreact  ', function(){
        $this->actingAs($this->user)->post(route('add-quote-notification', ['quote_id' => '1', 'user_id'=>'1', 'type'=>'unreact']));
        $this->assertDatabaseMissing('reactions', ['user_id' => '1', 'quote_id'=> '1']);
    });
});