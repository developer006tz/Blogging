<?php
include 'path.php';
$isEditting = isset($this->collection['slug']);
$collectionPostIds = array_column($this->collectionPosts, 'id');
$allPosts = \App\Utils::removePostBodyColumn($this->posts);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">
  <style>
    tbody tr td:nth-child(2) {
      width: 100%;
    }
  </style>

  <title>Manage Collection - Admin</title>
</head>

<body>

  <?php include ROOT_PATH . '/views/partials/admin_navbar.php';?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include ROOT_PATH . '/views/partials/admin_sidebar.php';?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <div class="table-container double-column">
          <!-- sm-box - ALL POSTS TABLE  -->
          <div class="sm-box">
            <h2>All Posts</h2>
            <br>
            <div class="table-actions">
              <div>
                <label for="search-post-input">Search posts</label>
                <input type="text" name="search-post-input" id="search-post-input">
              </div>
            </div>
            <table class="all-posts-table">
              <thead>
                <th>SN</th>
                <th class="center" colspan="2">Title</th>
              </thead>
              <tbody>
                <?php if (!empty($allPosts)): ?>
                  <?php foreach ($allPosts as $key => $post): ?>
                    <tr
                      class="post-tr-<?php echo $post['id']; ?> <?php echo in_array($post['id'], $collectionPostIds) ? 'selected' : ''; ?>"
                      data-id="<?php echo $post['id']; ?>"
                      data-title="<?php echo $post['title']; ?>"
                      onclick="selectPost(this)"
                    >
                      <td><?php echo $this->currentPage > 1 ? ($this->currentPage - 1) * $this->numberPerPage + $key + 1 : $key + 1; ?></td>
                      <td><?php echo $post['title']; ?></td>
                      <td style="width: 50px;">
                        <ion-icon class="hide checkmark-icon" name="checkmark-done-outline"></ion-icon>
                      </td>
                    </tr>
                  <?php endforeach;?>
                <?php else: ?>
                  <tr>
                    <td colspan="3">No posts!</td>
                  </tr>
                <?php endif;?>
              </tbody>
              <tfoot>
                <td colspan="3">
                  <p>To find more posts, type in the search box above</p>
                </td>
              </tfoot>
            </table>
            <textarea name="collection-posts" id="collection-posts" class="collection-posts hide">
              <?php echo json_encode($this->collectionPosts); ?>
            </textarea>
            <textarea name="all-posts" id="all-posts" class="all-posts hide">
              <?php echo json_encode($allPosts); ?>
            </textarea>
          </div>
          <!-- // sm-box - ALL POSTS TABLE -->


          <!-- sm-box - SELECTED POSTS TABLE  -->
          <div class="sm-box">
            <form action="<?php url("/admin/collections/save-posts"); ?>" method="post">
              <h2><?php echo $this->collection['title']; ?></h2>
              <?php include ROOT_PATH . '/views/partials/form_errors.php';?>
              <?php if ($isEditting): ?>
                <input type="hidden" name="collection_id" value="<?php echo $this->collection['id']; ?>">
              <?php endif;?>
              <p>
                ✨ Tip: Drag and drop to order items
              </p>
              <table class="selected-posts-table">
                <thead>
                  <tr>
                    <th colspan="2">
                      <a href="<?php url("/collection/" . $collection['slug']); ?>" target="_blank" class="link">
                        <?php echo $this->collection['title']; ?>
                      </a>
                    </th>
                  </tr>
                  <tr>
                    <th>SN</th>
                    <th class="center" colspan="2">Title</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($this->collectionPosts)): ?>
                    <?php foreach ($this->collectionPosts as $key => $post): ?>
                      <tr
                        class="post-tr-<?php echo $post['id']; ?> selected"
                        data-id="<?php echo $post['id']; ?>"
                        data-title="<?php echo $post['title']; ?>"
                        onclick="unSelectPost(this)"
                      >
                        <td class="sort-order">
                          <?php echo isset($post['sort_order']) ? $post['sort_order'] : $key + 1; ?>
                        </td>
                        <td class="title">
                          <?php echo $post['title']; ?>
                          <input type="hidden" name="postIds[]" value="<?php echo $post['id']; ?>">
                          <input type="hidden" class="sort_order_input" name="sort_order[]" value="<?php echo $post['sort_order']; ?>">
                        </td>
                        <td style="width: 50px;">
                          <ion-icon data-id="<?php echo $post['id'] ?>" class="close-icon hide" name="close-outline"></ion-icon>
                        </td>
                      </tr>
                    <?php endforeach;?>
                  <?php else: ?>
                    <tr>
                      <td colspan="3">No posts!</td>
                    </tr>
                  <?php endif;?>
                </tbody>
                <tfoot class="table-pagination">
                  <td colspan="3">
                    <button type="submit" class="btn primary-btn long-btn">Save</button>
                  </td>
                </tfoot>
              </table>
            </form>
          </div>
          <!-- // sm-box - SELECTED POSTS TABLE  -->
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
    const allPostsTableBody = document.querySelector('.all-posts-table tbody');
    const selectedPostsTableBody = document.querySelector('.selected-posts-table tbody');
    const selectedPosts = JSON.parse(document.querySelector('.collection-posts').value) || [];

    // Implement drag/drop ordering
    $(".selected-posts-table tbody").sortable({
      update: function(event, ui) {
        $tableRows = $(this).children('tr');

        $tableRows.each(function() {
          $currentTableRow = $(this);
          const newSortOrder = $currentTableRow.index() + 1;

          $currentTableRow.children('td.sort-order').html(newSortOrder);
          $currentTableRow.children('td.title').children('.sort_order_input').val(newSortOrder);
        });
      }
    });

    // Search
    const searchInput = document.querySelector('#search-post-input');
    const allPostsData = JSON.parse(document.querySelector('#all-posts').value);
    searchInput.addEventListener('keyup', async function(event) {
      const searchTerm = event.target.value;
      if (searchTerm === '' || searchTerm.length < 2) {
        displayAllPosts(allPostsData);
        return;
      }
      const formData = new FormData();
      formData.append('searchTerm', searchTerm);

      const res = await fetch(`${baseUrl}/admin/posts/search`, {
        method: 'POST',
        body: formData
      });
      const data = await res.json();
      displayAllPosts(data);
    });

    function displayAllPosts(posts) {
      let tableRows = '';

      posts.forEach((post, index) => {
        const selectedClass = selectedPostExists(post.id.toString()) ? 'selected' : '';
        tableRows = tableRows + `<tr class="post-tr-${post.id} ${selectedClass}" onclick="selectPost(this)" data-id="${post.id}" data-title="${post.title}">
          <td>${index + 1}</td>
          <td>${post.title}</td>
          <td style="width: 50px;">
            <ion-icon class="checkmark-icon" name="checkmark-done-outline"></ion-icon>
          </td>
        </tr>`;
      });

      allPostsTableBody.innerHTML = tableRows || `<tr><td colspan="6">No posts found!</td></tr>`;
    }

    function displaySelectedPosts() {
      let postRows = '';

      selectedPosts.forEach((post, index) => {
        postRows = `${postRows}
          <tr class="post-tr-${post.id} selected" onclick="unSelectPost(this)" data-id="${post.id}" data-title="${post.title}">
            <td class="sort-order">
              ${post.sort_order ?? index + 1}
              </td>
              <td class="title">
              ${post.title}
              <input type="hidden" name="postIds[]" value="${post.id}" />
              <input type="hidden" class="sort_order_input" name="sort_order[]" value="${post.sort_order ?? index + 1}">
            </td>
            <td style="width: 50px;">
              <ion-icon data-id="${post.id}" class="close-icon hide" name="close-outline"></ion-icon>
            </td>
          </tr>
        `;
      });
      selectedPostsTableBody.innerHTML = postRows;
    }

    function selectedPostExists(postId) {
      return selectedPosts.some(post => post.id === postId);
    }

    function selectPost(e) {
      e.classList.toggle('selected');
      const {id, title} = e.dataset;

      if (selectedPostExists(id)) {
        removePostById(id);
      } else {
        selectedPosts.push({ id, title });
      }
      displaySelectedPosts();
    }

    function removePostById(id) {
      const indexToDelete = selectedPosts.findIndex(post => post.id === id);
      selectedPosts.splice(indexToDelete, 1);
    }

    function unSelectPost(e) {
      removePostById(e.dataset.id);
      document.querySelector(`.post-tr-${e.dataset.id}`).classList.toggle('selected');
      displaySelectedPosts();
    }

    displaySelectedPosts();

  </script>
</body>
</html>