<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory, Eloquence;

    protected $guarded = [];

    protected $searchableColumns = [
      'name',
      'publisher',
      'country',
      'release_date',
    ];

    public function authors()
    {
      return $this->hasMany(Author::class);
    }
}
