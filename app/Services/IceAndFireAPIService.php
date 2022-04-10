<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class IceAndFireAPIService
{

  public static function get($name=null)
  {
      return (new static())($name);
  }

  public static function httpClient()
  {
      return (\App::environment('production')) ? Http::acceptJson() : Http::withOptions(['verify' => false])->acceptJson();
  }

  public function __invoke($name=null)
  {
    $response = (!is_null($name)) ?
      self::httpClient()->get(config('iceandfire.api_url') . "?name=$name") :
      self::httpClient()->get(config('iceandfire.api_url'));
    // return $response;
    return (object) json_decode($response);
  }
}



