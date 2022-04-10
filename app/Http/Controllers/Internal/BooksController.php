<?php

namespace App\Http\Controllers\Internal;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $book = Book::query();
        if ( $request->get('s') ) {
            $book = $book->with('authors')->search($request->get('s'));
        }
        return new \App\Http\Resources\BookCollection($book->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\BookRequest $request)
    {
        try {
            $validated = $request->validated();
            $authors = collect($validated['authors']);
            unset($validated['authors']);
            $book = Book::create($validated);
            $authors->each(function($author) use($book) {
                $book->authors()->create([
                    'fullname' => $author
                ]);
            });
            return new \Illuminate\Http\JsonResponse([
                'status_code' => 201,
                'status' => 'success',
                'data' => [
                    'name' => $book->name,
                    'isbn' => $book->isbn,
                    'authors' => $book->authors->map(function($author) {
                        return $author->fullname;
                    }),
                    'number_of_pages' => $book->number_of_pages,
                    'publisher' => $book->publisher,
                    'country' => $book->country,
                    'release_date' => \Carbon\Carbon::parse($book->release_date)->isoFormat('YYYY-MM-DD'),
                ]
            ], 201);

        } catch(\Exception $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return new \Illuminate\Http\JsonResponse([
            'status_code' => 200,
            'status' => 'success',
            'data' => [
                'id' => $book->id,
                'name' => $book->name,
                'isbn' => $book->isbn,
                'authors' => $book->authors->map(function($author) {
                    return $author->fullname;
                }),
                'number_of_pages' => $book->number_of_pages,
                'publisher' => $book->publisher,
                'country' => $book->country,
                'release_date' => \Carbon\Carbon::parse($book->release_date)->isoFormat('YYYY-MM-DD'),
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\BookRequest $request, Book $book)
    {
        $validated = $request->validated();
        $authors = collect($validated['authors']);
        $book_title = $book->name;
        unset($validated['authors']);
        $book->update($validated);
        $book->authors()->delete();
        $authors->each(function($author) use($book) {
            $book->authors()->create([
                'fullname' => $author
            ]);
        });
        return new \Illuminate\Http\JsonResponse([
            'status_code' => 200,
            'status' => 'success',
            'message' => 'The book '. $book_title .' was updated successfully',
            'data' => [
                'id' => $book->id,
                'name' => $book->name,
                'isbn' => $book->isbn,
                'authors' => $book->authors->map(function($author) {
                    return $author->fullname;
                }),
                'number_of_pages' => $book->number_of_pages,
                'publisher' => $book->publisher,
                'country' => $book->country,
                'release_date' => \Carbon\Carbon::parse($book->released)->isoFormat('YYYY-MM-DD'),
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book_title = $book->name;
        $book->delete();
        return new \Illuminate\Http\JsonResponse([
            'status_code' => 204,
            'status' => 'success',
            'message' => 'The book '. $book_title .' was deleted successfully',
            'data' => []
        ], 200);
    }
}
