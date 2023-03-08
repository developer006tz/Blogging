<?php

use App\Utils;
include 'path.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap"
    rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/style.css'; ?>">

  <title><?php echo $this->topic['name'] ?> | Blog</title>
</head>

<body>

  <?php include ROOT_PATH . '/views/partials/public_navbar.php';?>

  <!-- Page Banner -->
  <section class="page-banner">
    <div class="banner-container">
      <div class="left-box">
        <div class="breadcrumbs" role="navigation">
          <span>
            <a href="<?php url('/'); ?>">Home</a> >
            <span>
              <?php echo $this->topic['name'] ?>
            </span>
          </span>
        </div>
        <h1 class="banner-title"><?php echo $this->topic['name'] ?></h1>
        <p class="primary-font">
          <?php echo $this->topic['description']; ?>
        </p>
      </div>
    </div>
  </section>
  <!-- // End Page Banner -->

  <!-- Page Content -->
  <div class="page-content">
    <!-- Main Content -->
    <div class="main-content">

      <?php foreach ($this->posts as $key => $post): ?>
        <article class="post-card flat-card">
          <?php if ($post['post_image']): ?>
            <div class="image-wrapper bg-image" style="background-image: url(<?php echo ASSETS_URL . '/images/featured_images/' . $post['post_image']; ?>);"></div>
          <?php endif;?>
          <div class="post-info">
            <div class="topic-wrapper">
              <div></div>
              <span class="read-time"><?php echo Utils::estimateReadTime($post['body']); ?></span>
            </div>
            <div>
                <a href="<?php url("/posts/" . $post['slug']); ?>" class="td-none">
                  <h3 class="post-title"><?php echo $post['title']; ?></h3>
                </a>
            </div>
            <div class="post-preview">
              <p><?php echo Utils::getPostBodyPreview($post['body']); ?></p>
            </div>
            <div class="author-info">
              <div class="author">
                  <img src="<?php echo ASSETS_URL . '/images/avatar/' . $post['user_image']; ?>" class="avatar" alt="">
                  <a class="name simple-link" href="<?php url("/users/" . Utils::slugify($post['username'], '_', false) . "?id=" . $post['user_id']); ?>"><?php echo $post['username']; ?></a>
              </div>
              <div>
                <!-- <a href="#">Read more <ion-icon style="vertical-align: bottom;" name="arrow-forward-outline"></ion-icon></a> -->
              </div>
            </div>
          </div>
        </article>
      <?php endforeach;?>
      <input type="hidden" class="topic-slug" value="<?php echo $this->topic['slug']; ?>" >
      <button class="btn long-btn load-more-btn">Load more</button>
    </div>
    <!-- // End Main Content -->

    <!-- Sidebar -->
    <?php include ROOT_PATH . '/views/partials/public_sidebar.php';?>

  </div>
  <!-- // End Page Content -->

  <!-- Page Footer -->
  <?php include ROOT_PATH . '/views/partials/footer.php';?>

  <!-- Ionicons -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

  <!-- Custom scripts -->
  <script src="<?php echo ASSETS_URL . '/js/public.js' ?>"></script>
  <script>
    let currentPage = 2;
    const topicSlug = document.querySelector('.topic-slug').value;
    loadMoreButton.addEventListener('click', function() {
      const morePostsUrl = `${baseUrl}/topics/${topicSlug}?page=${currentPage}`;
      fetchMorePosts(morePostsUrl);
      currentPage = currentPage + 1;
    });
  </script>
</body>

</html>