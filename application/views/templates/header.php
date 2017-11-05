<html>
<title>Typeform Developer Platform demo</title>

<div>

<a href="/index.php/Welcome/index">Home</a>

<?php
if (!isset($_SESSION['access_token'])) {
	echo "Not logged in: <a href='/index.php/Authorize/index'>Authenticate to Typeform</a>";
}
?>

</div>
