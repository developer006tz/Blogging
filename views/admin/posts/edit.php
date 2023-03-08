<?php include 'path.php';?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Theme included stylesheets -->
  <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
    integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
    crossorigin="anonymous" />

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">

  <title>Edit post - Admin</title>
</head>

<body>

  <?php include ROOT_PATH . '/views/partials/admin_navbar.php';?>

  <main class="page-wrapper">
    <div class="sidebar-overlay"></div>
    <?php include ROOT_PATH . '/views/partials/admin_sidebar.php';?>

    <!-- Page Content -->
    <div class="page-content">
      <div class="admin-container">
        <form action="<?php url("/admin/posts/update/" . $this->id); ?>" method="post" class="admin-form md-box" id="edit-post" enctype="multipart/form-data">
          <h1 class="center form-title">Update Post</h1>
          <input type="hidden" name="id" value="<?php echo $this->id ?>" id="post_id" />
          <div class="message-div"></div>

          <?php include ROOT_PATH . '/views/partials/form_errors.php';?>

          <div class="input-group">
            <label for="title">Title</label>
            <input type="text" value="<?php echo $this->title; ?>" name="title" id="title" class="input-control">
          </div>
          <div class="input-group">
            <label for="post-editor">Body</label>
            <textarea name="body" id="post-body" class="hide"><?php echo html_entity_decode(html_entity_decode($this->body)); ?></textarea>
            <div id="post-editor" class="post-editor"><?php echo html_entity_decode(html_entity_decode($this->body)); ?></div>
          </div>
          <div class="post-details">
            <div class="select-topic-wrapper">
              <div class="input-group">
                <label for="topic">Topics</label>
                <select name="topic_id" id="topic" class="input-control">
                  <option></option>
                  <?php foreach ($this->topics as $key => $value): ?>
                    <option
                      value="<?php echo $value['id'] ?>"
                      <?php echo $this->topic_id == $value['id'] ? 'selected' : ''; ?>
                    >
                      <?php echo $value['name'] ?>
                    </option>
                  <?php endforeach;?>
                </select>
              </div>
            </div>
            <div class="image-wrapper">
              <input type="file" name="image" onchange="displayImage(this)" class="hide image-input">
              <div class="input-group image-div bg-image" style="background-image: url(<?php echo ASSETS_URL . '/images/featured_images/' . $this->image; ?>); height: 150px; border: none; background-color: gray;">
                <span class="choose-image-icon">
                  <ion-icon name="image-outline" class="image-outline"></ion-icon>
                  <span>Choose Image</span>
                </span>
              </div>
            </div>
          </div>
          <div class="input-group">
            <label for="published">
              <input type="checkbox" name="published" <?php echo $this->published ? 'checked' : '' ?> id="published" >
              Published
            </label>
          </div>

          <div class="input-group submit-group">
            <button type="submit" class="btn primary-btn big-btn">Update</button>
          </div>
        </form>
      </div>
    </div>
    <!-- // Page Content -->
  </main>

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
    integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
    crossorigin="anonymous"></script>

  <!-- select 2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
    integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
    crossorigin="anonymous"></script>

  <!-- Quill JS -->
  <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script src="<?php echo ASSETS_URL . '/js/admin.js' ?>"></script>
  <script src="<?php echo ASSETS_URL . '/js/post_quill_editor.js' ?>"></script>
  <script>
    // Preview post image
    const changeImageButton = document.querySelector('.image-div');
    const imageInput = document.querySelector('.image-input');
    changeImageButton.addEventListener('click', function(e) {
      imageInput.click();
    });

    function displayImage(e) {
      if (e.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e){
          changeImageButton.style.backgroundImage = `url(${e.target.result})`;
          changeImageButton.style.height = '150px';
          changeImageButton.style.border = 'none';
          document.querySelector('.choose-image-icon').classList.add('hide');
        }
        reader.readAsDataURL(e.files[0]);
      }
    }
  </script>

</body>

</html>