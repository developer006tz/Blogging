<?php

use App\Utils;
include 'path.php';

$firstCollection = isset($this->collections[0]) ? $this->collections[0] : null;
$secondCollection = isset($this->collections[1]) ? $this->collections[1] : null;
$thirdCollection = isset($this->collections[2]) ? $this->collections[2] : null;
$fourthCollection = isset($this->collections[3]) ? $this->collections[3] : null;

?>

<?php

function displayPostCollection($collection) {
    if (!isset($collection)) {
        return null;
    }
    ?>
    <section class="page-section slider-container">
      <div class="slider-header">
        <h2 style="margin: 0px; padding: 0px;"><?php echo $collection['title']; ?></h2>
        <a href="<?php url("/collection/" . $collection['slug']); ?>">See All</a>
      </div>
      <ion-icon class="slider-arrow prev-arrow" name="arrow-back-circle-outline"></ion-icon>
      <ion-icon class="slider-arrow next-arrow" name="arrow-forward-circle-outline"></ion-icon>
      <div class="post-slider">
        <?php foreach ($collection['posts'] as $post): ?>
          <article class="post-card">
            <div class="image-wrapper bg-image" style="background-image: url(<?php echo ASSETS_URL . '/images/featured_images/' . $post['post_image']; ?>);"></div>
            <div class="post-info">
              <div class="topic-wrapper">
                <?php if (isset($post['topic_name'])): ?>
                  <a href="<?php url("/topics/" . $post['topic_slug']); ?>" class="topic-tag"><?php echo $post['topic_name']; ?></a>
                <?php else: ?>
                  <span></span>
                <?php endif;?>
                <span class="read-time"><?php echo Utils::estimateReadTime($post['body']); ?></span>
              </div>
              <div>
                <a href="<?php url("/posts/" . $post['slug']); ?>" style="text-decoration: none;">
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
                  <a href="<?php url("/posts/" . $post['slug']); ?>">Read more <ion-icon style="vertical-align: bottom;" name="arrow-forward-outline">
                    </ion-icon></a>
                </div>
              </div>
            </div>
          </article>
        <?php endforeach;?>

      </div>
    </section>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Slick Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap"
    rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/admin_style.css'; ?>">
  <link rel="stylesheet" href="<?php echo ASSETS_URL . '/css/style.css'; ?>">

  <title><?php echo $this->pageTitle; ?></title>
</head>

<body>

  <?php include ROOT_PATH . '/views/partials/public_navbar.php';?>

  <!-- Page Content -->
  <div class="page-content homepage">

    <?php include ROOT_PATH . '/views/partials/message.php';?>

    <!-- Page Hero -->
    <section class="featured-container">
      <article class="post-card flat-card">
        <div class="image-wrapper bg-image" style="background-image: url(<?php echo ASSETS_URL . '/images/featured_images/' . $this->featuredPost['post_image']; ?>);" ></div>
        <div class="post-info">
          <div class="topic-wrapper">
            <?php if (isset($this->featuredPost['topic_name'])): ?>
              <a href="<?php url("/topics/" . $this->featuredPost['topic_slug']); ?>" class="topic-tag"><?php echo $this->featuredPost['topic_name']; ?></a>
            <?php else: ?>
              <span></span>
            <?php endif;?>
            <span class="read-time"><?php echo $this->featuredPost['read_time']; ?></span>
          </div>
          <div>
            <a href="<?php url("/posts/" . $this->featuredPost['slug']); ?>">
              <h3 class="post-title"><?php echo $this->featuredPost['title']; ?></h3>
            </a>
          </div>
          <div class="post-preview">
            <p>
              <?php echo $this->featuredPost['preview']; ?>
            </p>
          </div>
          <div class="author-info">
            <div class="author">
              <img src="<?php echo ASSETS_URL . '/images/avatar/' . $this->featuredPost['user_image']; ?>" class="avatar" alt="">
              <a class="name simple-link" href="<?php url("/users/" . Utils::slugify($this->featuredPost['username'], '_', false) . "?id=" . $this->featuredPost['user_id']); ?>">
                <?php echo $this->featuredPost['username']; ?>
              </a>
            </div>
            <div>
              <a href="<?php url("/posts/" . $this->featuredPost['slug']); ?>">
                Read more 
                <ion-icon style="vertical-align: bottom;" name="arrow-forward-outline"></ion-icon>
              </a>
            </div>
          </div>
        </div>
      </article>
    </section>
    <!-- // Page Hero -->

    <!-- Posts Collection Carousel -->
    <?php echo displayPostCollection($firstCollection); ?>

    <!-- Posts Collection Carousel -->
    <?php echo displayPostCollection($secondCollection); ?>


    <!-- Topics Section -->
    <?php if (!empty($this->topics)): ?>
      <section class="page-section topics-container">
      <div>
        <h2>Explore articles in various topics</h2>
        <p>Our articles are organized into topics</p>
      </div>
      <div class="topics-pills">
        <?php foreach ($this->topics as $topic): ?>
          <a href="<?php url("/topics/" . $topic['slug']); ?>"><?php echo $topic['name']; ?></a>
        <?php endforeach;?>
      </div>
    </section>
    <?php endif;?>
    <!-- // End Topics Section -->

    <!-- Posts Collection Carousel -->
    <?php echo displayPostCollection($thirdCollection); ?>

    <!-- Posts Collection Carousel -->
    <?php echo displayPostCollection($fourthCollection); ?>

  </div>
  <!-- // Page Content -->

  <!-- Page Footer -->
  <?php include 'partials/footer.php';?>

  <!-- Scripts -->
  <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <!-- Slick Js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

  <script>
    $('.post-slider').each(function (index, slider) {
      $(slider).slick({
        dots: false,
        nextArrow: $(slider).siblings('.next-arrow'),
        prevArrow: $(slider).siblings('.prev-arrow'),
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [
          {
            breakpoint: 1024,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 3,
            }
          },
          {
            breakpoint: 870,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 2,
            }
          },
          {
            breakpoint: 550,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              infinite: true,
            }
          }
        ]
      });
    });
  </script>

  <!-- Custom scripts -->
  <script src="<?php echo ASSETS_URL . '/js/public.js' ?>"></script>

</body>

</html>