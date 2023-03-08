<?php  if(!empty($_SESSION['message'])): ?>
  <?php  if (isset($_SESSION['messageType']) && $_SESSION['messageType'] == "success"): ?>
    <div class="message success">
      <ion-icon name="checkmark-circle" class="message-icon"></ion-icon>
      <span><?php echo $_SESSION['message']; ?></span>
    </div>
  <?php elseif(isset($_SESSION['messageType']) && $_SESSION['messageType'] == "warning"): ?>
    <div class="message warning">
      <ion-icon name="warning-outline" class="message-icon"></ion-icon>
      <span><?php echo $_SESSION['message']; ?></span>
    </div> 
  <?php elseif(isset($_SESSION['messageType']) && $_SESSION['messageType'] == "danger"): ?>
    <div class="message danger">
      <ion-icon name="alert-circle-outline" class="message-icon"></ion-icon>
      <span><?php echo $_SESSION['message']; ?></span>
    </div> 
  <?php else: ?>
    <div class="message success">
      <ion-icon name="checkmark-circle" class="message-icon"></ion-icon>
      <span><?php echo $_SESSION['message']; ?></span>
    </div>
  <?php endif; ?>
<?php endif; ?>

<?php

unset($_SESSION['message']);
unset($_SESSION['messageType']);
