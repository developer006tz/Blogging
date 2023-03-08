<?php

namespace App;

use App\{Utils};

class Message {
  public static function success($message) {
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = "success";
  }

  public static function failure($message) {
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = "danger";
  }

  public static function warning($message) {
    $_SESSION['message'] = $message;
    $_SESSION['messageType'] = "warning";
  }

}
