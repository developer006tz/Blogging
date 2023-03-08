<?php

namespace App;

class Permission extends Db {
  static $tableName = 'permissions';
  static $timestamps = true;
  static $fillable = ['name', 'slug', 'description'];


}
