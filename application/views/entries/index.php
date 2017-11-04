<h2><?php echo $title; ?></h2>

<?php foreach ($winners as $winner): ?>

        <h3><?php echo $winner['FirstName']." ".$winner['LastName']; ?></h3>

<?php endforeach; ?>
