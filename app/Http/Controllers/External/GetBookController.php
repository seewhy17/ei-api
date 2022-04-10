<?php

namespace App\Http\Controllers\External;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;

class GetBookController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $response = \App\Services\IceAndFireAPIService::get($request->get('name'));
        $books = (object) collect($response);
        return new \Illuminate\Http\JsonResponse([
            'status_code' => !$this->isEmpty($books) ? 200 : 404,
            'status' => !$this->isEmpty($books) ? 'success': 'not found',
            'data' => $books->map(function($book) {
                return [
                    'name' => $book->name,
                    'isbn' => $book->isbn,
                    'authors' => $book->authors,
                    'number_of_pages' => $book->numberOfPages,
                    'publisher' => $book->publisher,
                    'country' => $book->country,
                    'release_date' => Carbon::parse($book->released)->isoFormat('YYYY-MM-DD'),
                ];
            }),
        ], !$this->isEmpty($books) ? 200 : 404);
    }

    private function isEmpty($books)
    {
        return count($books) < 1;
    }
}
