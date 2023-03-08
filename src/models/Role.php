<?php

namespace App;

class Role extends Db {
  static $tableName = 'roles';
  static $timestamps = true;
  static $fillable = ['name', 'slug', 'description'];

  public static function selectWithUserCount() {
    $sql = "SELECT r.*, COUNT(u.role_id) AS user_count FROM roles r 
            LEFT JOIN users u ON u.role_id = r.id GROUP BY r.id";
    $stmt = self::executeQuery($sql);
    $roles = $stmt->fetchAll();
    return $roles;
  }

  public static function getPermissionIds($role_id) {
    $sql = "SELECT p.* FROM roles r 
            INNER JOIN permission_role pr ON r.id = pr.role_id 
            INNER JOIN permissions p ON p.id = pr.permission_id 
            WHERE r.id = :role_id";
    $stmt = self::executeQuery($sql, ['role_id' => $role_id]);
    $permissions = $stmt->fetchAll(\PDO::FETCH_COLUMN, 0);
    return $permissions;
  }

  public static function assignPermissions($role_id, $permissionIds) {
    $deleteQuery = "DELETE FROM permission_role WHERE role_id=:role_id";
    $stmt1 = self::executeQuery($deleteQuery, ['role_id' => $role_id]);

    foreach ($permissionIds as $permission) {
      $assignQuery = "INSERT INTO permission_role SET role_id=:role_id, permission_id=:permission_id";
      $data = [
        'role_id' => $role_id,
        'permission_id' => $permission
      ];
      self::executeQuery($assignQuery, $data);
    }
  }

}
