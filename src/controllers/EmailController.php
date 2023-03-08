<?php
  
namespace App;

class EmailController {
  public static function showResetEmailTemplate($request, $response, $service) {
    $service->render('views/email/reset_password_email.php');
  }
}