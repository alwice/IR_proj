<!DOCTYPE html>
<html>
<head>
	<?php
	include('class.pdf2text.php');
	?>
	<title></title>
</head>
<body>
	<!--set directory-->
	<form action="preprocess.php" method="POST">
		<input type="text" name="directory">
		<input type="submit" name="submit_directory" value="submit">
	</form>
	
</body>
</html>


