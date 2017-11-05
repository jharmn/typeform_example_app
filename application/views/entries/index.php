<h2><?php echo $title; ?></h2>


<table>
<tr><th>First Name</th><th>Last Name</th></tr>
<?php foreach ($entries as $entry): ?>

        <tr><td><?php echo $entry['FirstName']."</td><td>".$entry['LastName']; ?></td></tr>


<?php endforeach; ?>
</table>
