<?php

use App\Models\Book;
use Illuminate\Testing\Fluent\AssertableJson;


it('can create a book', function () {
    $data = [
      'name' => 'Class with Clementines',
      'isbn' => '123-349596450562',
      'authors' => [
        'Clement Makinde',
        'Cyril Fehintoluwa',
      ],
      'number_of_pages' => 782,
      'publisher' => 'Loren Books',
      'country' => 'Nigeria',
      'release_date' => '2022-04-04',
    ];
    $response = $this->postJson('/api/v1/books', $data);
    $response
      ->assertStatus(201)
      ->assertExactJson([
          'status_code' => 201,
          'status' => 'success',
          'data' => $data
      ]);
});

it('can list all books with all the required attributes', function () {

    Book::factory()->times(10)->hasAuthors(2)->create();
    $response = $this->getJson('/api/v1/books');
    $response
      ->assertStatus(200)
      ->assertJson([
          'status_code' => 200,
          'status' => 'success',
      ])
      ->assertJson(function (AssertableJson $json) {
        return $json->hasAll('status_code', 'status', 'data')->has('data.0', function($json) {
            return $json->hasAll('id', 'name', 'isbn', 'authors', 'number_of_pages', 'publisher', 'country', 'release_date');

        });
    })
    ->assertJsonCount(2, 'data.0.authors');
});

it('returns correct response where there is no result', function () {

    $response = $this->getJson('/api/v1/books');
    $response
      ->assertStatus(200)
      ->assertJson([
          'status_code' => 200,
          'status' => 'success',
      ])
      ->assertJsonCount(0, 'data');
});

it('can show a book', function () {
    $book = Book::factory()->hasAuthors(2)->create();
    $response = $this->getJson('/api/v1/books/' . $book->id);
    $response
      ->assertStatus(200)
      ->assertExactJson([
          'status_code' => 200,
          'status' => 'success',
          'data' => [
            'id' => $book->id,
            'name' => $book->name,
            'isbn' => $book->isbn,
            'authors' => $book->authors->map(function($book) {
              return $book->fullname;
            }),
            'number_of_pages' => $book->number_of_pages,
            'publisher' => $book->publisher,
            'country' => $book->country,
            'release_date' => $book->release_date,
          ]
      ]);
});

it('can search books by name', function() {

    Book::factory()->times(10)->hasAuthors(2)->create();
    $book = Book::all()->random();
    $response = $this->getJson('/api/v1/books?s=' . $book->name);
    $response
      ->assertStatus(200)
      ->assertSee($book->name);
});

it('can search books by country', function() {

    Book::factory()->times(10)->hasAuthors(2)->create();
    $book = Book::all()->random();
    $response = $this->getJson('/api/v1/books?s=' . $book->country);
    $response
      ->assertStatus(200)
      ->assertSee($book->country);
});

it('can search books by publisher', function() {

    Book::factory()->times(10)->hasAuthors(2)->create();
    $book = Book::all()->random();
    $response = $this->getJson('/api/v1/books?s=' . $book->publisher);
    $response
      ->assertStatus(200)
      ->assertSee($book->publisher);
});

it('can search books by release_date', function() {

    Book::factory()->times(10)->hasAuthors(2)->create();
    $book = Book::all()->random();
    $response = $this->getJson('/api/v1/books?s=' . $book->release_date);
    $response
      ->assertStatus(200)
      ->assertSee($book->release_date);
});

it('can update a book', function () {
    $book = Book::factory()->hasAuthors(2)->create();
    $data = [
      'name' => 'Attack of the Titans',
      'isbn' => '123-349596450562',
      'authors' => [
        'Clement Makinde',
        'Cyril Fehintoluwa',
      ],
      'number_of_pages' => 782,
      'publisher' => 'Loren Books',
      'country' => 'Ghana',
      'release_date' => now()->format('Y-m-d'),
    ];
    $response = $this->patchJson('/api/v1/books/' . $book->id, $data);
    $new_data = array_merge(['id' => $book->id], $data);
    $response
      ->assertStatus(200)
      ->assertExactJson([
          'status_code' => 200,
          'status' => 'success',
          'message' => 'The book ' . $book->name . ' was updated successfully',
          'data' => $new_data
      ]);
});


it('can delete a book', function () {
    $book = Book::factory()->hasAuthors(2)->create();
    $response = $this->deleteJson('/api/v1/books/' . $book->id);
    $response
      ->assertStatus(200)
      ->assertExactJson([
          'status_code' => 204,
          'status' => 'success',
          'message' => 'The book ' . $book->name . ' was deleted successfully',
          'data' => []
      ]);
});

it('can delete a book via /api/v1/books/:id/delete endpoint', function () {
    $book = Book::factory()->hasAuthors(2)->create();
    $response = $this->postJson('/api/v1/books/' . $book->id . '/delete');
    $response
      ->assertStatus(200)
      ->assertExactJson([
          'status_code' => 204,
          'status' => 'success',
          'message' => 'The book ' . $book->name . ' was deleted successfully',
          'data' => []
      ]);
});

