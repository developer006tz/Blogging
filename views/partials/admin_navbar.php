<header>
  <ion-icon name="menu-sharp" class="menu-icon"></ion-icon>
  <div class="nav-overlay"></div>

  <a href="<?php url('/'); ?>" class="logo-wrapper td-none">
    <div>EVENINGCLASS<span>101</span></div>
  </a>
  <nav>
    <ul class="navmenu">
      <?php if (isset($_SESSION['id'])): ?>
        <li class="navitem">
          <a href="#">
            <?php $avatarUrl = !empty($_SESSION['image']) ? $_SESSION['image'] : 'avatar.jpg';?>
            <img src="<?php echo ASSETS_URL . '/images/avatar/' . $avatarUrl; ?>" alt="" class="user-icon">
            <span><?php echo $_SESSION['username']; ?></span>
            <ion-icon name="chevron-down-outline" class="chevron-down"></ion-icon>
          </a>
          <ul class="dropdown">
            <li><a href="<?php url('/logout'); ?>">Logout</a></li>
          </ul>
        </li>
      <?php endif;?>
    </ul>
  </nav>
</header>
