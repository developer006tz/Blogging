<?php

namespace App;

use App\{Utils};

class Mail {
  public static function send($receiverEmail, $message) {

    Utils::dd($receiverEmail, $message);
  }
}
