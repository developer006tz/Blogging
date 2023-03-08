<?php

namespace App;

use App\{Permission, Utils};

class PermissionValidator {
  public static function create($permission) {
    $errors = [];

    if (empty($permission['name'])) {
      array_push($errors, 'Name is required');
    }

    $existingPermission = Permission::selectOne(['name' => $permission['name']]);
    if (!empty($existingPermission)) {
      array_push($errors, 'Name already exists.');
    }

    return $errors;
  }

  public static function update($permission) {
    $errors = [];

    if (empty($permission['name'])) {
      array_push($errors, 'Name is required');
    }

    $existingPermission = Permission::selectOne(['name' => $permission['name']]);
    if (!empty($existingPermission) && $existingPermission['id'] != $permission['id']) {
      array_push($errors, 'Name already exists.');
    }

    return $errors;
  }
}