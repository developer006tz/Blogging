<?php
include 'path.php';
$usersFilter = isset($_SESSION['usersFilter']) ? $_SESSION['usersFilter'] : 'NEWEST';

$currenUsersData = [
    'users' => $this->users,
    'currentPage' => $this->currentPage,
    'numberPerPage' => $this->numberPerPage,
    'pageNumbers' => $this->pageNumbers,
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

<?php include ROOT_PATH . '/views/partials/admin_navbar.php';?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include ROOT_PATH . '/views/partials/admin_sidebar.php';?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <div class="table-container lg-box">
          <h1 class="center">Users</h1>
          <hr>

          <?php include ROOT_PATH . '/views/partials/message.php';?>

          <div class="table-actions">
            <div class="table-filter-group">
              <input type="text" name="search-term" id="table-search-input" placeholder="Search...">
              <select name="filter-users" id="filter-users" class="filter-users <?php echo $usersFilter != 'ALL' ? 'filter-selected' : '' ?>">
                <option value="ALL" <?php echo $usersFilter == 'NEWEST' ? 'selected' : '' ?>>-- Filter Users --</option>
                <option value="NEWEST" <?php echo $usersFilter == 'NEWEST' ? 'selected' : '' ?>>Newest</option>
                <option value="OLDEST" <?php echo $usersFilter == 'OLDEST' ? 'selected' : '' ?>>Oldest</option>
                <option value="VERIFIED" <?php echo $usersFilter == 'VERIFIED' ? 'selected' : '' ?>>Verified</option>
                <option value="UNVERIFIED" <?php echo $usersFilter == 'UNVERIFIED' ? 'selected' : '' ?>>Unverified</option>
                <option value="USER" <?php echo $usersFilter == 'USER' ? 'selected' : '' ?>>User</option>
                <option value="STAFF" <?php echo $usersFilter == 'STAFF' ? 'selected' : '' ?>>Staff</option>
                <option value="Admin" <?php echo $usersFilter == 'Admin' ? 'selected' : '' ?>>Admin</option>
                <option value="Author" <?php echo $usersFilter == 'Author' ? 'selected' : '' ?>>Author</option>
                <option value="Editor" <?php echo $usersFilter == 'Editor' ? 'selected' : '' ?>>Editor</option>
              </select>
            </div>

            <div class="table-buttons">
              <a href="<?php url("/admin/users/create"); ?>" class="btn primary-btn small-btn">
                <ion-icon name="add-circle-outline" class="icon"></ion-icon> Add User
              </a>
            </div>
          </div>
          <div class="responsive-table">
            <table>
              <thead>
                <th>SN</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
              </thead>
              <tbody>
                <?php if (!empty($this->users)): ?>
                  <?php foreach ($this->users as $key => $user): ?>
                    <tr>
                      <td><?php echo $this->currentPage > 1 ? ($this->currentPage - 1) * $this->numberPerPage + $key + 1 : $key + 1; ?></td>
                      <td><?php echo $user['username']; ?></td>
                      <td>
                        <?php echo $user['email']; ?>
                        <div class="td-action-buttons">
                          <a href="<?php url("/admin/users/" . $user['id'] . "/edit"); ?>" class="edit">Edit</a>
                          <span class="inline-divider">|</span>
                          <a href="<?php url("/admin/users/delete/" . $user['id']); ?>" class="delete">Delete</a>
                        </div>
                      </td>
                      <td><?php echo $user['role_name']; ?></td>
                    </tr>
                  <?php endforeach;?>
                <?php else: ?>
                  <tr>
                    <td colspan="4">No users!</td>
                  </tr>
                <?php endif;?>
              </tbody>
              <tfoot class="table-pagination">
                <td colspan="2">
                </td>
                <td colspan="5">
                  <div class="pagination-links">
                    <?php foreach ($this->pageNumbers as $key => $value): ?>
                      <?php if ($this->currentPage == $value): ?>
                        <button href="?page=<?php echo $value; ?>" class="link active" disabled><?php echo $value; ?></button>
                      <?php elseif ($value == '...'): ?>
                        <button href="?page=<?php echo $value; ?>" class="link" disabled><?php echo $value; ?></button>
                      <?php else: ?>
                        <a href="?page=<?php echo $value; ?>" class="link"><?php echo $value; ?></a>
                      <?php endif;?>
                    <?php endforeach;?>
                  </div>
                </td>
              </tfoot>
            </table>
            <textarea name="current-users" id="current-users" class="hide">
              <?php echo json_encode($currenUsersData); ?>
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

    // Search
    const searchInput = document.querySelector('#table-search-input');
    const allUsersData = JSON.parse(document.querySelector('#current-users').value);
    const filterUsersDropdown = document.querySelector('.filter-users');

    function insertUsers(users, showPaginationLinks = false) {
      const tableBody = document.querySelector('tbody');
      const tablePagination = document.querySelector('.table-pagination');
      let tableRows = '';

      users?.forEach((user, index) => {
        const displayIndex = allUsersData.currentPage > 1 ? (allUsersData.currentPage - 1) * allUsersData.numberPerPage + index + 1 : index + 1;
        tableRows = tableRows + `<tr>
          <td>${showPaginationLinks ? displayIndex : index + 1}</td>
          <td>${user.username}</td>
          <td>
            <a href="#">${user.email}</a>
            <div class="td-action-buttons">
              <a href="${baseUrl}/admin/users/${user.id}/edit" class="edit">Edit</a>
              <span class="inline-divider">|</span>
              <a href="${baseUrl}/admin/users/delete/${user.id}" class="delete">Delete</a>
            </div>
          </td>
          <td>${user.role_name || ''}</td>
        </tr>`;
      });

      tableBody.innerHTML = tableRows || `<tr><td colspan="4">No users found!</td></tr>`;

      if (showPaginationLinks) {
        tablePagination.classList.remove('hide');
      } else {
        tablePagination.classList.add('hide');
      }
    }

    // Search users
    searchInput.addEventListener('keyup', async function(event) {
      const searchTerm = event.target.value;
      if (searchTerm === '' || searchTerm.length < 1) {
        insertUsers(allUsersData.users, true);
        return;
      }
      const formData = new FormData();
      formData.append('searchTerm', searchTerm);

      const res = await fetch('/admin/users/search', {
        method: 'POST',
        body: formData
      });
      const data = await res.json();
      insertUsers(data);
    });

    // Filter users
    filterUsersDropdown.addEventListener('change', async function(e) {
      const usersFilter = e.target.value;

      usersFilter.toLowerCase() !== 'all' ? filterUsersDropdown.classList.add('filter-selected') : filterUsersDropdown.classList.remove('filter-selected');

      const res = await fetch('/admin/users/filter-users?filter=' + usersFilter);
      const data = await res.json();
      insertUsers(data.users);

      const tablePagination = document.querySelector('.table-pagination');
      tablePagination.classList.remove('hide');
      let pageLinks = '';

      if (data.pageNumbers.length <= 1) {
        return;
      }

      displayPaginationLinks(tablePagination, data.pageNumbers, data.currentPage);
    });

  </script>

</body>

</html>