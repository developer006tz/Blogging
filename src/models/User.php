<?php

namespace App;

use App\{Utils};

class User extends Db {
  static $tableName = 'users';
  static $fillable = [
    'role_id',
    'username',
    'email',
    'verified',
    'image',
    'password',
    'token',
    'bio',
    'twitter',
    'linkedin',
    'instagram'
  ];
  static $timestamps = true;

  public static function paginate($numberOfRecords = 10, $currentPage = 1) {
    $sql = "SELECT u.*, r.name AS role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id ";
    $allRoleNames = ['Admin', 'Author', 'Editor'];

    if (isset($_SESSION['usersFilter'])) {
      if (in_array($_SESSION['usersFilter'], $allRoleNames)) {
        $sql = $sql . " WHERE r.name = '" . $_SESSION['usersFilter'] . "' ORDER BY u.created_at DESC";
      } else {
        switch ($_SESSION['usersFilter']) {
          case 'NEWEST':
          case 'ALL':
            $sql = $sql . "ORDER BY u.created_at DESC";
            break;
          case 'OLDEST':
            $sql = $sql . "ORDER BY u.created_at ASC";
            break;
          case 'USER':
            $sql = $sql . "WHERE u.role_id IS NULL ORDER BY u.created_at DESC";
            break;
          case 'STAFF':
            $sql = $sql . "WHERE u.role_id IS NOT NULL ORDER BY u.created_at DESC";
            break;
          case 'VERIFIED':
            $sql = $sql . "WHERE u.verified=1 ORDER BY u.created_at DESC";
            break;
          case 'UNVERIFIED':
            $sql = $sql . "WHERE u.verified=0 ORDER BY u.created_at DESC";
            break;
          default:
            $sql = $sql . "ORDER BY u.created_at DESC";
            break;
        }
      }
    } else {
      $sql = $sql . "ORDER BY u.created_at DESC";
    }

    $countStmt = self::executeQuery($sql);
    $pagesCount = ceil($countStmt->rowCount() / $numberOfRecords);
    
    $sql = $sql . " LIMIT :currentPage,:numberOfRecords";
    $data = [
      'currentPage' => ($currentPage - 1) * $numberOfRecords,
      'numberOfRecords' => $numberOfRecords
    ];
    $stmt = self::executeQuery($sql, $data, false);
    $users = $stmt->fetchAll();
    $pageNumbers = Utils::getPaginationLinks($currentPage, $pagesCount);

    return [
      'users' => $users,
      'numberPerPage' => $numberOfRecords,
      'pagesCount' => $pagesCount,
      'currentPage' => $currentPage,
      'pageNumbers' => $pageNumbers
    ];
  }

  public static function getUserPermissions($user_id) {
    $sql = "SELECT u.id, p.name FROM users u 
            INNER JOIN permission_role p_r ON u.role_id = p_r.role_id
            INNER JOIN permissions p ON p.id = p_r.permission_id WHERE u.id=:user_id";
    $stmt = User::executeQuery($sql, ['user_id' => $user_id]);
    $permissions = $stmt->fetchAll(\PDO::FETCH_COLUMN, 1);
    return $permissions;
  }

  public static function getUsersWithRole() {
    $sql = "SELECT u.*, r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id";
    $stmt = self::executeQuery($sql);
    return $stmt->fetchAll();
  }

  public static function searchUsers($queryString) {
    $sql = "SELECT u.*, r.name as role_name FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id
            WHERE username LIKE CONCAT('%', :queryString, '%') 
            OR email LIKE CONCAT('%', :queryString, '%')";

    $stmt = self::executeQuery($sql, ['queryString' => $queryString]);
    $posts = $stmt->fetchAll();
    return $posts;
  }
}
