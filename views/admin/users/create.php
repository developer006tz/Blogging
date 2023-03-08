<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Create User - Admin</title>
</head>

<body>

<?php include ROOT_PATH . '/views/partials/admin_navbar.php';?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include ROOT_PATH . '/views/partials/admin_sidebar.php';?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <form action="<?php url("/admin/users"); ?>" method="post" enctype="multipart/form-data" class="admin-form sm-box">
            <h1 class="center form-title">Create User</h1>

            <div class="input-group avatar-input-group center">
              <input type="file" name="avatar" onChange="displayImage(this)" id="avatar-input" class="hide avatar-input">
              <button type="button" class="change-avatar-btn">
                <span>change</span>
              </button>
              <label for="avatar-input">Profile Image (optional)</label>
            </div>

            <?php include ROOT_PATH . '/views/partials/form_errors.php';?>

            <div class="input-group">
              <label for="username">Username</label>
              <input type="text" name="username" value="<?php echo $this->username; ?>" id="username" class="input-control">
            </div>
            <div class="input-group">
              <label for="email">Email</label>
              <input type="email" name="email" value="<?php echo $this->email; ?>" id="email" class="input-control">
            </div>
            <div class="input-group">
              <label for="password">Password</label>
              <input type="password" name="password" value="<?php echo $this->password; ?>" id="password" class="input-control">
            </div>
            <div class="input-group">
              <label for="passwordConf">Password Confirmation</label>
              <input type="password" name="passwordConf" value="<?php echo $this->passwordConf; ?>" id="passwordConf" class="input-control">
            </div>
            <div class="input-group">
              <label for="role_id">Role</label>
              <select name="role_id" id="role_id" class="input-control">
                <option></option>

                <?php foreach ($this->roles as $key => $role): ?>
                  <option value="<?php echo $role['id']; ?>" <?php echo $role['id'] == $this->role_id ? 'selected' : '' ?> ><?php echo $role['name']; ?></option>
                <?php endforeach;?>

              </select>
            </div>
            <div class="input-group">
              <label for="bio">Bio</label>
              <textarea
              name="bio"
              id="bio"
              cols="30"
              rows="4"
              class="input-control"><?php echo $this->bio; ?></textarea>
            </div>
            <div class="input-group">
              <label for="twitter">Twitter (optional)</label>
              <input type="text" name="twitter" value="<?php echo $this->twitter; ?>" id="twitter" class="input-control">
            </div>
            <div class="input-group">
              <label for="linkedin">LinkedIn (optional)</label>
              <input type="text" name="linkedin" value="<?php echo $this->linkedin; ?>" id="linkedin" class="input-control">
            </div>
            <div class="input-group">
              <label for="instagram">Instagram (optional)</label>
              <input type="text" name="instagram" value="<?php echo $this->instagram; ?>" id="instagram" class="input-control">
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
  <script>
    const changeAvatarButton = document.querySelector('.change-avatar-btn');
    const avatarInput = document.querySelector('.avatar-input');
    changeAvatarButton.addEventListener('click', function(e) {
      avatarInput.click();
    });

    function displayImage(e) {
      if (e.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          changeAvatarButton.style.backgroundImage = `url(${e.target.result})`;
        }
        reader.readAsDataURL(e.files[0]);
      }
    }
  </script>

</body>

</html>