<?php

namespace App;

use App\{Role, Utils};

class RoleValidator {
  public static function create($role) {
    $errors = [];

    if (empty($role['name'])) {
      array_push($errors, 'Name is required');
    }

    $existingRole = Role::selectOne(['name' => $role['name']]);
    if (!empty($existingRole)) {
      array_push($errors, 'Name already exists.');
    }

    return $errors;
  }

  public static function update($role) {
    $errors = [];

    if (empty($role['name'])) {
      array_push($errors, 'Name is required');
    }

    $existingRole = Role::selectOne(['name' => $role['name']]);
    if (!empty($existingRole) && $existingRole['id'] != $role['id']) {
      array_push($errors, 'Name already exists.');
    }

    return $errors;
  }
}