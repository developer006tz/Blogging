<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Confirm Delete - Admin</title>
</head>

<body>

<?php include ROOT_PATH . '/views/partials/admin_navbar.php';?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include ROOT_PATH . '/views/partials/admin_sidebar.php';?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <div class="table-container lg-box">
          <h1>Are you sure?</h1>
          <hr>

          <div class="responsive-table" style="font-size: 18px;">
            <p>
              Are you sure you want to delete the post titled <strong><i><?php echo $this->title; ?></i></strong> Permanently?
            </p>
            <p style="color: red">This action cannot be undone.</p>
            <a href="<?php url("/admin/posts/permanently-delete/" . $this->id); ?>" class="btn danger-btn small-btn">
                <ion-icon name="trash-outline" class="icon"></ion-icon> Permanently Delete
            </a>
            <a href="<?php url("/admin/posts/trash"); ?>" class="btn">
              Do nothing
            </a>
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