<?php

namespace App\Controllers;

use App\Libraries\Twitch;
use CodeIgniter\Controller;
use Config\Services;

class Live extends BaseController
{
  public function index()
  {
    //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOm51bGwsImxvY2FsSWQiOiIxIiwiaXNTdWIiOnRydWUsImlhdCI6MTY3MzE1NTQ0NSwiZXhwIjoxNzU5NTU1NDQ1LCJleHBfZGF0YSI6IjA0XC8xMFwvMjAyNSAwMjoyNDowNSIsImVudmlyb25tZW50IjoidGVzdGluZyJ9.BeUY-SLsqBYf8hhigGsIvtqPi6JHsR_Cv6wjlEY8BfUNkbvXpzTSoex_-Q8ozcjy57H48dTEBhOk6iYB-hH9rQ
    $credentials = (object) session('decodedTokenAcesso');
    pre($credentials);
    $twitch = new Twitch([
      'clientId' => getenv('clientIdTwitch'),
      'clientSecret' => getenv('clientSecretTwitch'),
    ]);
    pre($credentials);
    echo  $twitch->getTimeLive($credentials);
  }
}
