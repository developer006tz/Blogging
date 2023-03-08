<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Manage Collections - Blog</title>
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
          <h1 class="center">Post Collections</h1>
          <hr>
          <?php include ROOT_PATH . '/views/partials/message.php';?>

          <div class="table-actions">
            <span></span>
            <a href="<?php url("/admin/collections/create"); ?>" class="btn primary-btn small-btn">
              <ion-icon name="add-circle-outline" class="icon"></ion-icon> Add Post Collection
            </a>
          </div>
          <p>✨ Tip: Drag and drop to order items (only the first four will feature on hompage)</p>
          <table class="post-collections-table">
            <thead>
              <th>SN</th>
              <th>Collection Title</th>
              <th># of posts</th>
            </thead>
            <tbody>
              <?php foreach ($this->collections as $key => $collection): ?>
                <tr data-id="<?php echo $collection['id']; ?>">
                  <td class="position"><?php echo $key + 1; ?></td>
                  <td style="max-width: 70%">
                    <?php echo $collection['title'] ?>
                    <div class="td-action-buttons">
                      <a href="<?php url("/admin/collections/confirm-delete/" . $collection['slug']); ?>" class="delete">Delete</a>
                      <span class="inline-divider">|</span>
                      <a href="<?php url("/admin/collections/" . $collection['id'] . "/edit"); ?>" class="edit">Edit</a>
                      <span class="inline-divider">|</span>
                      <a href="<?php url("/admin/collections/posts/" . $collection['id']); ?>" class="edit">Posts</a>
                    </div>
                  </td>
                  <td><?php echo $collection['post_count']; ?></td>
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

  <!-- Jquery used for drag and drop reordering of selected posts -->
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- General Admin scripts -->
  <script src="<?php echo ASSETS_URL . '/js/admin.js' ?>"></script>

  <script>
    // Implement drag/drop ordering
    $(".post-collections-table tbody").sortable({
      update: function(event) {
        const tableRows = event.target.querySelectorAll('tr');
        const positions = [];

        tableRows.forEach((tr, index) => {
          const newPosition = index + 1;
          tr.querySelector('td.position').innerHTML = newPosition;
          positions.push({ id: tr.dataset.id, position: newPosition });
        });

        const formData = new FormData();
        formData.append('positions', JSON.stringify(positions));
        fetch(`${baseUrl}/admin/collections/save-positions`, { method: 'POST', body: formData }).then(res => res.json()).then(data => console.log(data));
      }
    });
  </script>

</body>
</html>