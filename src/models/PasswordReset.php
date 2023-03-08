<?php

namespace App;

class PasswordReset extends Db {
  static $tableName = 'password_resets';
  static $fillable = ['token', 'user_id', 'expires_at'];
  static $timestamps = true;

}
