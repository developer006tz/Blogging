<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Create Topic - Admin</title>
</head>

<body>

<?php include ROOT_PATH . '/views/partials/admin_navbar.php';?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include ROOT_PATH . '/views/partials/admin_sidebar.php';?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <form action="<?php url("/admin/topics"); ?>" method="post" class="admin-form sm-box">
          <h1 class="center form-title">Create Topic</h1>

          <?php include ROOT_PATH . '/views/partials/form_errors.php';?>

          <div class="input-group">
            <label for="name">Name</label>
            <input type="text" value="<?php echo $this->name; ?>" name="name" id="name" class="input-control">
          </div>
          <div class="input-group">
            <label for="description" class="description">Description</label>
            <textarea
              class="input-control"
              name="description"
              id="description"
              cols="30"
              rows="4"
            ><?php echo $this->description; ?></textarea>
          </div>
          <div class="input-group submit-group">
            <button type="submit" class="btn primary-btn big-btn">Save</button>
          </div>
        </form>
      </div>
    </div>
    <!-- // Page Content -->
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
  <script src="<?php echo ASSETS_URL . '/js/admin.js' ?>"></script>

</body>

</html>