<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Manage Roles - Blog</title>
</head>

<body>

<?php include ROOT_PATH . '/views/partials/admin_navbar.php';?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include ROOT_PATH . '/views/partials/admin_sidebar.php';?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <div class="table-container sm-box">
          <h1 class="center">Roles</h1>
          <hr>
          <?php include ROOT_PATH . '/views/partials/message.php';?>

          <div class="table-actions">
            <span></span>
            <a href="<?php url("/admin/roles/create"); ?>" class="btn primary-btn small-btn">
              <ion-icon name="add-circle-outline" class="icon"></ion-icon> Add Role
            </a>
          </div>
          <div class="responsive-table">
            <table>
              <thead>
                <th>SN</th>
                <th>Role</th>
                <th># of users</th>
                <th>Update Permissions</th>
              </thead>
              <tbody>
                <?php foreach ($this->roles as $key => $role): ?>
                  <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td>
                      <?php echo $role['name']; ?>
                      <div class="td-action-buttons">
                        <a href="<?php url("/admin/roles/" . $role['id'] . "/edit"); ?>" class="edit">Edit</a>
                        <span class="inline-divider">|</span>
                        <a href="<?php url("/admin/roles/delete/" . $role['id']); ?>" class="delete">Delete</a>
                      </div>
                    </td>
                    <td><?php echo $role['user_count']; ?></td>
                    <td class="center td-action">
                      <a href="<?php url("/admin/roles/assign-permissions/" . $role['id']); ?>" class="edit" title="Assign permissions to role">
                        Permissions
                      </a>
                    </td>
                  </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- // Page Content -->
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
  <script src="<?php echo ASSETS_URL . '/js/admin.js' ?>"></script>
</body>

</html>