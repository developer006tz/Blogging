<div class="sidebar">
  <?php if (!empty($this->topics)): ?>
    <div class="sidebar-section topics-section">
      <h2 class="title">Topics</h2>
      <div class="topic-list">
        <?php foreach ($this->topics as $topic): ?>
          <a href="<?php url("/topics/" . $topic['slug']); ?>"><?php echo $topic['name']; ?></a>
        <?php endforeach;?>
      </div>
    </div>
  <?php endif;?>
  <div class="sidebar-section">
    <img src="<?php echo ASSETS_URL . '/images/sidebar-tall-image.jpg'; ?>" width="100%" />
  </div>
</div>