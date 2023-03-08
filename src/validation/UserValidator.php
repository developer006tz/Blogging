<?php

namespace App;

use App\{User, Utils};

class UserValidator {
  public static function username($username) {
    $errors = [];
    if (empty($username)) {
      array_push($errors, 'Username is required');
    }

    return $errors;
  }

  public static function email($email) {
    $errors = [];
    if (empty($email)) {
      array_push($errors, 'Email is required');
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      array_push($errors, 'Email is invalid');
    }

    return $errors;
  }

  public static function passwords($password, $passwordConf) {
    $errors = [];
    if (empty($password)) {
      array_push($errors, 'Password is required');
    }
    if (!empty($password) && empty($passwordConf)) {
      array_push($errors, 'Please confirm password');
    }

    $passwordsProvided = !empty($password) && !empty($passwordConf);
    if ($passwordsProvided && $password != $passwordConf) {
      array_push($errors, 'The two passwords do not match');
    }

    return $errors;
  }

  public static function avatar($file) {
    $errors = [];

    if (isset($file) && $file['size'] > 2000000) {
      array_push($errors, 'Maximum image size is 2MB');
    }

    return $errors;
  }

  public static function create($user, $file) {
    $errors = [];

    $usernameErrors = self::username($user['username']);
    $emailErrors = self::email($user['email']);
    $passwordErrors = self::passwords($user['password'], $user['passwordConf']);
    $avatarErrors = self::avatar($file);
    $errors = array_merge($errors, $usernameErrors, $emailErrors, $passwordErrors, $avatarErrors);

    $conditions = [
      'username' => $user['username'],
      'email' => $user['email']
    ];
    $existingUser = User::selectOne($conditions, 'OR');
    if (!empty($existingUser)) {
      if (strtolower($existingUser['username']) == strtolower($user['username'])) {
        array_push($errors, 'Username already taken');
      }
      if (strtolower($existingUser['email']) == strtolower($user['email'])) {
        array_push($errors, 'Email already exists');
      }
    }

    return $errors;
  }

  public static function update($user, $avatarFile) {
    $errors = [];

    $usernameErrors = self::username($user['username']);
    $emailErrors = self::email($user['email']);
    $passwordErrors = self::passwords($user['password'], $user['passwordConf']);
    $avatarErrors = self::avatar($avatarFile);
    $errors = array_merge($errors, $usernameErrors, $emailErrors, $passwordErrors, $avatarErrors);

    $existingUser = User::selectOne(['id' => $user['id']]);
    if (empty($user['oldPassword'])) {
      array_push($errors, 'Please provide old password');
    } else if(isset($user['oldPassword']) && !\password_verify($user['oldPassword'], $existingUser['password'])) {
      array_push($errors, 'Old password does not match');
    }

    $conditions = [
      'username' => $user['username'],
      'email' => $user['email']
    ];
    $existingUser2 = User::selectOne($conditions, 'OR');
    if (!empty($existingUser2)) {
      if (strtolower($existingUser2['username']) == strtolower($user['username']) && $existingUser2['id'] != $user['id']) {
        array_push($errors, 'Username already taken');
      }
      if (strtolower($existingUser2['email']) == strtolower($user['email']) && $existingUser2['id'] != $user['id']) {
        array_push($errors, 'Email already exists');
      }
    }

    return $errors;
  }

  public static function login($loginDetails) {
    $errors = [];

    if (empty($loginDetails['username'])) {
      array_push($errors, 'Username is required');
    }

    if (empty($loginDetails['password'])) {
      array_push($errors, 'Password is required');
    }

    return $errors;
  }
}