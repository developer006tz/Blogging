<?php

namespace App;

class DashboardController {
  public static function show($request, $response, $service) {
    $service->render('views/admin/dashboard.php');
  }

}
