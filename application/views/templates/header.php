<html>
<title>Typeform Developer Platform demo</title>

<div>

<a href="/index.php/Welcome/index">Home</a>
<br/>
<?php
if (!isset($_SESSION['access_token'])) {
	echo "Typeform account not authorized: <a href='/index.php/Authorize/index'>Authorize Typeform</a>";
}
?>

</div>
