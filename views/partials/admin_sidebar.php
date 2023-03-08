<div id="sidebar" class="sidebar">
  <div class="sidebar-author-mobile">
    <img src="<?php echo ASSETS_URL . '/images/avatar/' . $avatarUrl; ?>" class="avatar" alt="">
    <h3 class="author-name"><?php
    echo $_SESSION['username'];
     ?></h3>
    <a href="<?php url('/logout') ?>" class="logout-link">Logout</a>
  </div>
  <ul class="list-menu">
    <li>
      <a href="<?php url("/dashboard"); ?>">
        <ion-icon class="menu-icon" name="speedometer-outline"></ion-icon>
        Dashboard
        <ion-icon name="chevron-forward-outline" class="chevron-forward"></ion-icon>
      </a>
    </li>
    <li>
      <a href="<?php url("/admin/posts"); ?>">
        <ion-icon class="menu-icon" name="reader-outline"></ion-icon>
        Posts
        <ion-icon name="chevron-forward-outline" class="chevron-forward"></ion-icon>
      </a>
    </li>
    <li>
      <a href="<?php url("/admin/topics"); ?>">
        <ion-icon class="menu-icon" name="grid-outline"></ion-icon>
        Topics
        <ion-icon name="chevron-forward-outline" class="chevron-forward"></ion-icon>
      </a>
    </li>
    <li>
      <a href="<?php url("/admin/users"); ?>">
        <ion-icon class="menu-icon" name="people-outline"></ion-icon>
        Users
        <ion-icon name="chevron-forward-outline" class="chevron-forward"></ion-icon>
      </a>
    </li>
    <li>
      <a href="<?php url("/admin/roles"); ?>">
        <ion-icon class="menu-icon" name="lock-closed-outline"></ion-icon>
        Roles
        <ion-icon name="chevron-forward-outline" class="chevron-forward"></ion-icon>
      </a>
    </li>
    <li>
      <a href="<?php url("/admin/permissions"); ?>">
        <ion-icon class="menu-icon" name="key-outline"></ion-icon>
        Permissions
        <ion-icon name="chevron-forward-outline" class="chevron-forward"></ion-icon>
      </a>
    </li>
    <li>
      <a href="<?php url("/admin/collections"); ?>">
        <ion-icon class="menu-icon" name="reader-outline"></ion-icon>
        Collections
        <ion-icon name="chevron-forward-outline" class="chevron-forward"></ion-icon>
      </a>
    </li>
  </ul>
</div>