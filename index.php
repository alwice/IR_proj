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
	<fieldset><legend>Question 2</legend><form action="preprocess.php" method="POST">
		<label>Files Directory: </label>
		<input type="text" name="directory" placeholder="files" required>
		<input type="submit" name="submit_directory" value="Submit">		
	</form></fieldset>
	<br>

	<!--get query-->
	<fieldset><legend>Question 3</legend><form action="query_tfidf.php" method="POST">
		<label>Query: </label>
		<input type="text" name="query" placeholder="query1 query2 query3" required>
		<input type="submit" name="submit_query" value="Submit">
	</form></fieldset>
	<br>

	<!--get 3words query&threshold-->
	<fieldset><legend>Question 4</legend><form action="frequenttermset.php" method="POST">
		<label>Query: </label>
		<input type="text" name="query" placeholder="query1 query2 query3">
		<br><br>
		<label>Threshold: </label><input type="number" name="threshold" placeholder="threshold" required>
		<br><br>
		<input type="submit" name="submit_ft" value="Submit">
	</form></fieldset>

	<!--get query&relevant doc-->
	<fieldset><legend>Question 5</legend><form action="precision_recall.php" method="POST">
		<label>Query: </label>
		<input type="text" name="query" placeholder="query1 query2 query3">
		<label>Relevant Documents: </label>
		<input type="text" name="doc" placeholder="d1 d2 d3">
		<input type="submit" name="submit_query" value="Submit">
	</form></fieldset>
	<br>
	
</body>
</html>

<?php
	
?>


