<?php
  include('path.php');
  $postsFilter = isset($_SESSION['postsFilter']) ? $_SESSION['postsFilter'] : 'ALL';

  $currenPostsData = [
    'posts' => \App\Utils::removePostBodyColumn($this->posts),
    'currentPage' => $this->currentPage,
    'numberPerPage' => $this->numberPerPage,
    'pageNumbers' => $this->pageNumbers
  ];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Blog - Admin</title>
</head>

<body>

  <?php include(ROOT_PATH . '/views/partials/admin_navbar.php'); ?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include(ROOT_PATH . '/views/partials/admin_sidebar.php'); ?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <div class="table-container lg-box" style="margin-top: 0px;">
          <h1 class="center">Posts</h1>
          <hr>

          <?php include(ROOT_PATH . '/views/partials/message.php'); ?>

          <form class="featured-post-form" action="<?php url("/admin/posts/update-featured-post"); ?>" method="post">
            <strong>Featured Post:</strong>
            <span class="title-wrapper">
              <?php echo isset($this->featuredPost['title']) ? $this->featuredPost['title'] : 'Please set a featured post'; ?>
              <button type="button" class="change-featured-post">Change</button>
            </span>
            <span class="input-wrapper hide">
              <input type="text" name="title" class="input-control input-control-sm"
                value="<?php echo isset($this->featuredPost['title']) ? $this->featuredPost['title'] : ''; ?>"
                placeholder="Enter post title..." required>
              <button type="submit" class="btn btn-primary">Update</button>
            </span>
          </form>


          <div class="table-actions">
            <div class="table-filter-group">
              <input type="text" name="search-term" id="search-post-input" placeholder="Search...">
              <select name="filter-posts" id="filter-posts"
                class="filter-posts <?php echo $postsFilter != 'ALL' ? 'filter-selected' : '' ?>">
                <option value="ALL">--Filter --</option>
                <option value="ALL">All</option>
                <option value="OLDEST" <?php echo $postsFilter=='OLDEST' ? 'selected' : '' ?>>Oldest</option>
                <option value="NEWEST" <?php echo $postsFilter=='NEWEST' ? 'selected' : '' ?>>Newest</option>
                <option value="POPULAR" <?php echo $postsFilter=='POPULAR' ? 'selected' : '' ?>>Most Popular</option>
                <option value="PUBLISHED" <?php echo $postsFilter=='PUBLISHED' ? 'selected' : '' ?>>Published</option>
                <option value="DRAFTS" <?php echo $postsFilter=='DRAFTS' ? 'selected' : '' ?>>Drafts</option>
              </select>
            </div>

            <div class="table-buttons">
              <a href="<?php url("/admin/posts/trash"); ?>" class="btn warning-btn small-btn">
                <ion-icon name="trash-outline" class="icon"></ion-icon> Trash
              </a>
              <a href="<?php url("/admin/posts/create"); ?>" class="btn primary-btn small-btn">
                <ion-icon name="add-circle-outline" class="icon"></ion-icon> Add Post
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
                <th class="center">Publish</th>
              </thead>
              <tbody>
                <?php if (!empty($this->posts)): ?>
                <?php foreach($this->posts as $key => $post): ?>
                <tr>
                  <td>
                    <?php echo $this->currentPage > 1 ? ($this->currentPage - 1) * $this->numberPerPage + $key + 1 : $key + 1; ?>
                  </td>
                  <td>
                    <?php echo $post['username']; ?>
                  </td>
                  <td>
                    <a href="<?php url("/posts/" . $post['slug']); ?>" target="_blank">
                      <?php echo $post['title']; ?>
                    </a>
                    <div class="td-action-buttons">
                      <a href="<?php url("/admin/posts/trash/" . $post['id']); ?>" class="trash">Trash</a>
                      <span class="inline-divider">|</span>
                      <a href="<?php url("/admin/posts/" . $post['id'] . "/edit"); ?>" class="edit">Edit</a>
                      <span class="inline-divider">|</span>
                      <a href="<?php url("/admin/posts/" . $post['id'] . "/related"); ?>" class="edit">Related Posts</a>
                    </div>
                  </td>
                  <td>
                    <?php echo $post['topic_name']; ?>
                  </td>
                  <td>
                    <?php echo $post['view_count']; ?>
                  </td>
                  <td class="center td-action">
                    <a href="<?php url("/admin/posts/toggle-publish/" . $post['id']); ?>" title="Publish">
                      <?php echo $post['published'] ? 'Unpublish' : 'Publish' ?>
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                  <td colspan="6">No posts!</td>
                </tr>
                <?php endif; ?>

              </tbody>
              <tfoot class="table-pagination">
                <td colspan="6">
                  <div class="pagination-links">
                    <?php foreach ($this->pageNumbers as $key => $value): ?>
                    <?php if ($this->currentPage == $value): ?>
                    <button href="?page=<?php echo $value; ?>" class="link active" disabled>
                      <?php echo $value; ?>
                    </button>
                    <?php elseif($value == '...'):?>
                    <button href="?page=<?php echo $value; ?>" class="link" disabled>
                      <?php echo $value; ?>
                    </button>
                    <?php else:?>
                    <a href="?page=<?php echo $value; ?>" class="link">
                      <?php echo $value; ?>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                  </div>
                </td>
              </tfoot>
            </table>
            <textarea name="current-posts" id="current-posts" class="hide">
              <?php echo json_encode($currenPostsData); ?>
            </textarea>
          </div>
        </div>
      </div>
    </div>
    <!-- // Page Content -->
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
  <script src="<?php echo ASSETS_URL . '/js/admin.js' ?>"></script>
  <script>
    const featuredPostTitleWrapper = document.querySelector('.featured-post-form .title-wrapper');
    const featuredPostInputWrapper = document.querySelector('.featured-post-form .input-wrapper');
    const changeFeaturedPostButton = document.querySelector('.change-featured-post');

    changeFeaturedPostButton.addEventListener('click', function (e) {
      featuredPostTitleWrapper.classList.add('hide');
      featuredPostInputWrapper.classList.remove('hide');
      featuredPostInputWrapper.querySelector('input').select();
    });

    // Search
    const searchInput = document.querySelector('#search-post-input');
    const allPostsData = JSON.parse(document.querySelector('#current-posts').value);
    const filterPostsDropdown = document.querySelector('.filter-posts');

    function insertPosts(posts, showPaginationLinks = false) {
      const tableBody = document.querySelector('tbody');
      const tablePagination = document.querySelector('.table-pagination');
      let tableRows = '';

      posts.forEach((post, index) => {
        const displayIndex = allPostsData.currentPage > 1 ? (allPostsData.currentPage - 1) * allPostsData.numberPerPage + index + 1 : index + 1;
        tableRows = tableRows + `<tr>
          <td>${showPaginationLinks ? displayIndex : index + 1}</td>
          <td>${post.username}</td>
          <td>
            <a target="_blank" href="${baseUrl}/posts/${post.slug}">${post.title}</a>
            <div class="td-action-buttons">
              <a href="${baseUrl}/admin/posts/${post.id}/edit" class="edit">Edit</a>
              <span class="inline-divider">|</span>
              <a href="${baseUrl}/admin/posts/trash/${post.id}" class="trash">Trash</a>
              <span class="inline-divider">|</span>
              <a href="${baseUrl}/admin/posts/${post.id}/related" class="edit">Related Posts</a>
            </div>
          </td>
          <td>${post.topic_name || ''}</td>
          <td>${post.view_count}</td>
          <td class="center td-action">
            <a href="${baseUrl}/admin/posts/toggle-publish/${post.id}" title="Publish">
              ${post.published * 1 ? 'Unpublish' : 'Publish'}
            </a>
          </td>
        </tr>`;
      });

      tableBody.innerHTML = tableRows || `<tr><td colspan="6">No posts found!</td></tr>`;

      if (showPaginationLinks) {
        tablePagination.classList.remove('hide');
      } else {
        tablePagination.classList.add('hide');
      }
    }

    // Search posts
    searchInput.addEventListener('keyup', async function (event) {
      const searchTerm = event.target.value;
      if (searchTerm === '' || searchTerm.length < 2) {
        insertPosts(allPostsData.posts, true);
        return;
      }
      const formData = new FormData();
      formData.append('searchTerm', searchTerm);

      const res = await fetch(`${baseUrl}/admin/posts/search`, {
        method: 'POST',
        body: formData
      });
      const data = await res.json();
      insertPosts(data);
    });

    // Filter posts
    filterPostsDropdown.addEventListener('change', async function (e) {
      const postsFilter = e.target.value;

      postsFilter !== 'ALL' ? filterPostsDropdown.classList.add('filter-selected') : filterPostsDropdown.classList.remove('filter-selected');

      const res = await fetch(`${baseUrl}/admin/posts/filter-posts?filter=${postsFilter}`);
      const data = await res.json();
      insertPosts(data.posts);

      const tablePagination = document.querySelector('.table-pagination');
      displayPaginationLinks(tablePagination, data.pageNumbers, data.currentPage);
    });

  </script>
</body>

</html>