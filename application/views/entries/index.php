<h2><?php echo $title; ?></h2>


<table>
<tr>
	<th>First Name</th>
	<th>Last Name</th>
	<th>Email</th>
	<th>Image</th>
</tr>
<?php foreach ($entries as $entry): ?>

        <tr>
		<td><?php echo $entry['FirstName'] ?></td>
		<td><?php echo $entry['LastName'] ?></td>
		<td><?php echo $entry['Email'] ?></td>
		<td><img src="<?php echo $entry['ImageUrl'] ?>" width="200" /></td>
	</tr>


<?php endforeach; ?>
</table>
