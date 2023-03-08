<?php include('path.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Post Trash - Admin</title>
</head>

<body>

  <?php include(ROOT_PATH . '/views/partials/admin_navbar.php'); ?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include(ROOT_PATH . '/views/partials/admin_sidebar.php'); ?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <div class="table-container lg-box">
          <h1 class="center">Trash</h1>
          <hr>

          <?php include(ROOT_PATH . '/views/partials/message.php'); ?>

          <div class="table-actions">
            <div></div>

            <div class="table-buttons">
              <a href="<?php url("/admin/posts"); ?>" class="btn primary-btn small-btn">
                <ion-icon name="settings-outline" class="icon"></ion-icon> Manage Posts
              </a>
            </div>
          </div>
          <div class="responsive-table">
            <table>
              <thead>
                <th>SN</th>
                <th>Author</th>
                <th>Title</th>
                <th>Topic</th>
                <th>Views</th>
              </thead>
              <tbody>
                <?php if (!empty($this->posts)): ?>
                <?php foreach($this->posts as $key => $post): ?>
                <tr>
                  <td>
                    <?php echo $key + 1; ?>
                  </td>
                  <td>
                    <?php echo $post['username']; ?>
                  </td>
                  <td>
                    <a target="_blank" href="<?php url("/posts/" . $post['slug']); ?>">
                      <?php echo $post['title']; ?>
                    </a>
                    <div class="td-action-buttons">
                      <a href="<?php url("/admin/posts/restore/" . $post['id']); ?>" class="edit">Restore</a>
                      <span class="inline-divider">|</span>
                      <a href="<?php url("/admin/posts/confirm-delete/" . $post['id']); ?>" class="trash">Delete</a>
                    </div>
                  </td>
                  <td>
                    <?php echo $post['topic_name']; ?>
                  </td>
                  <td>
                    <?php echo $post['view_count']; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                  <td colspan="5">No posts in the trash!</td>
                </tr>
                <?php endif; ?>
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