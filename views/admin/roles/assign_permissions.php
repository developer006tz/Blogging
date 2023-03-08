<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Assign Permissions - Admin</title>
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
          <h1 class="center"><?php echo $this->role['name']; ?>'s Permissions</h1>
          <hr>

          <?php if (!empty($this->role['description'])): ?>
            <div class="role-description">
              <h3><?php echo $this->role['name']; ?></h3>
              <?php echo $this->role['description']; ?>
            </div>
          <?php endif;?>

          <div class="table-actions">
            <span></span>
            <a href="create.html" class="btn primary-btn small-btn">
              <ion-icon name="add-circle-outline" class="icon"></ion-icon> Add Role
            </a>
          </div>
          <form action="<?php url("/admin/assign-permissions/" . $this->role['id']); ?>" method="post" id="assign-permissions-form">
            <table>
              <thead>
                <th>SN</th>
                <th>Permission</th>
                <th class="center">
                  <label for="select-all">
                    All
                    <input type="checkbox" id="select-all">
                  </label>
                </th>
              </thead>
              <tbody>
                <?php foreach ($this->allPermissions as $key => $permission): ?>
                  <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td>
                      <label for="add-user">
                        <?php echo $permission['name']; ?>
                      </label>
                    </td>
                    <td class="center td-action">
                      <input type="checkbox" name="<?php echo $permission['id']; ?>" id="add-user" <?php echo in_array($permission['id'], $this->assignedPermissionIds) ? 'checked' : '' ?> >
                    </td>
                  </tr>
                <?php endforeach;?>

              </tbody>
              <tfoot class="table-pagination">
                <td colspan="6">
                  <button type="submit" class="btn primary-btn long-btn">Save</button>
                </td>
              </tfoot>
            </table>
          </form>
        </div>
      </div>
    </div>
    <!-- // Page Content -->
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
  <script src="<?php echo ASSETS_URL . '/js/admin.js' ?>"></script>

  <script>
    const selectAll = document.querySelector('#select-all');
    const assignPermissionsForm = document.querySelector('#assign-permissions-form');

    selectAll.addEventListener('change', function (event) {
      const checkboxList = assignPermissionsForm.querySelectorAll('td input[type=checkbox]');
      checkboxList.forEach(checkbox => checkbox.checked = selectAll.checked);
    });
  </script>

</body>

</html>