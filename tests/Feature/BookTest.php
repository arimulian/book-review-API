<?php

use function Pest\Laravel\{post, get, delete};

describe('Book Service Test', function() {

    it('Book crete test success', function() {
        post('api/books/create',[
            'title' => 'Harry Potter',
            'author' => 'JK. Rowley',
            'publisher' => 'USA-Book',
            'stock' => 3,
            'status' => 'active'
        ])->assertStatus(201)
            ->assertJson([
                'data'=> [
                    'title' => 'Harry Potter',
                    'author' => 'JK. Rowley',
                    'published' => 'USA-Book',
                   'stock' => 3,
                   'status' => 'active'
                ]
            ]);
    });
});
