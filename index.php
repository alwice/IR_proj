<!DOCTYPE html>
<html>
<head>
	<?php
	include('menu.php');
	include('class.pdf2text.php');
	?>
	<title>Main</title>
</head>
<body>
	
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;
	<br><br>

	<!--set directory-->
	<fieldset><form action="preprocess.php" method="POST">
		<label>Files Directory: </label>
		<input type="text" name="directory" placeholder="files" required>
		<input type="submit" name="submit_directory" value="Submit">		
	</form></fieldset>
	<br>
	<!--get query-->
	<fieldset><form action="query_tfidf.php" method="POST">
		<label>Query: </label>
		<input type="text" name="query" placeholder="query1 query2 query3" required>
		<br><br>
		<label>Threshold: </label><input type="number" name="threshold" placeholder="threshold">
		<br><br>
		<input type="submit" name="submit_query" value="Submit">
	</form></fieldset>
	
</body>
</html>

<?php
	
?>


