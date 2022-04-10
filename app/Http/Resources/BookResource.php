<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'isbn' => $this->isbn,
            'authors' => $this->authors->map(function($author) {
                return $author->fullname;
            }),
            'number_of_pages' => $this->number_of_pages,
            'publisher' => $this->publisher,
            'country' => $this->country,
            'release_date' => Carbon::parse($this->release_date)->isoFormat('YYYY-MM-DD'),
        ];
    }

    public function with($request)
    {
        return [
            'status_code' => 201,
            'status' => 'success',
        ];
    }
}
