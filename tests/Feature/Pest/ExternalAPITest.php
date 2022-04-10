<?php
use Illuminate\Testing\Fluent\AssertableJson;

it('returns json response', function () {

    $response = $this->get('/api/external-books');

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
    ]);

});


it('can get a specific book', function () {
    $book_name = 'A Game of Thrones';
    $response = $this->get('/api/external-books?name=' . $book_name);

    $response->assertStatus(200);
    $response->assertJson([
        'status' => 'success',
    ]);
    $response->assertSee('A Game of Thrones');
    $response->assertJsonPath('data.0.name', $book_name);
});

it('has correct response for requested book', function () {
    $response = $this->get('/api/external-books?name=A Game of Thrones');

    $response->assertJson(function (AssertableJson $json) {
        return $json->hasAll('status_code', 'status', 'data')->has('data.0', function($json) {
          return $json->hasAll('name', 'isbn', 'authors', 'number_of_pages', 'publisher', 'country', 'release_date');
        });
    });

});

it('returns correct response when the book cannot be found', function () {
    $response = $this->get('/api/external-books?name=Gibberish');

    $response->assertStatus(404);
    $response->assertJson([
        'status' => 'not found',
        'status_code' => '404',
    ]);
    $response->assertJsonCount(0, 'data');
});
