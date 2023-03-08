<?php if (!empty($this->errors)): ?>
  <ul class="form-errors">
    <?php foreach ($this->errors as $error): ?>
      <li> - <?php echo $error; ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
