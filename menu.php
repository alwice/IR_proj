<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" conetent="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="selfstyle.css" rel="stylesheet" type="text/css">
	<script src="selfscript.js" type="text/javascript"></script>
	<?php session_start();?>
</head>
<body>
	<div id="navigation">
		<ul><b>	
			<li><a href="index.php">Home</a></li>
			<li>All Documents' Terms
				<ul><li><a href="preprocess.php">Terms List</a></li>
				<li><a href="tfidf.php">TFIDF</a></li></ul>
			<li>Query
				<ul><li><a href="query_tfidf.php">Query TFIDF</a></li>
				<li><a href="query_rank.php">Query Rank</a></li></ul>
		</b></ul>
	</div>
</body>
</html>