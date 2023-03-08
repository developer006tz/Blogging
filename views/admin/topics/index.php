<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Manage Topics - Blog</title>
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
          <h1 class="center">Topics</h1>
          <hr>
          <?php include ROOT_PATH . '/views/partials/message.php';?>

          <div class="table-actions">
            <span></span>
            <a href="<?php url("/admin/topics/create"); ?>" class="btn primary-btn small-btn">
              <ion-icon name="add-circle-outline" class="icon"></ion-icon> Add Topic
            </a>
          </div>
          <table>
            <thead>
              <th>SN</th>
              <th>Topic</th>
              <th># of articles</th>
            </thead>
            <tbody>
              <?php foreach ($this->topics as $key => $topic): ?>
                <tr>
                  <td><?php echo $key + 1; ?></td>
                  <td>
                    <?php echo $topic['name'] ?>
                    <div class="td-action-buttons">
                      <a href="<?php url("/admin/topics/" . $topic['id'] . "/edit"); ?>" class="edit">Edit</a>
                      <span class="inline-divider">|</span>
                      <a href="<?php url("/admin/topics/delete/" . $topic['id']); ?>" class="delete">Delete</a>
                    </div>
                  </td>
                  <td><?php echo $topic['post_count']; ?></td>
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
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