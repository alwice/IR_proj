<!DOCTYPE html>
<html>
<head>
	<?php
	include('class.pdf2text.php');
	?>
	<title></title>
</head>
<body>
	<div id="navigation" >
		<ul><b>	
			<li><a href="index.php">Home</a></li>
			<li><a href="tfidf.php">tfidf</a></li>		
			<li><a href="images.php">Images</a></li>
			<li><a href="forum.php">Forum</a></li>	
		</b></ul>
	</div>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;
	<br><br>
	
	<!--set directory-->
	<form action="preprocess.php" method="POST">
		<label>Files Directory: </label>
		<input type="text" name="directory" placeholder="files">
		<input type="submit" name="submit_directory" value="submit">
	</form>
	<br>
	<!--get query-->
	<form action="queryrank.php" method="POST">
		<label>Query: </label>
		<input type="text" name="query" placeholder="query">
		<input type="submit" name="submit_query" value="submit">
	</form>
	
</body>
</html>


