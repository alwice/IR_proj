<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>Query Rank</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="query.php">&nbsp;Query TFIDF</a>&nbsp;&nbsp;>
	<a href="queryrank.php">&nbsp;Query Rank</a>&nbsp;&nbsp;
	<br><br>
	<?php
		//load session
		$doc_arr_str=$_SESSION['doc_arr_str'];
		$doc_arr_arr=$_SESSION['doc_arr_arr'];
		$str_all=$_SESSION['str_all'];
		$arr_terms=$_SESSION['arr_terms'];
		$str_terms=$_SESSION['str_terms'];
		$tfidf_query=$_SESSION['tfidf_query'];
		$tfidf_doc=$_SESSION['tfidf_doc'];
		$vector_unique_docquery=$_SESSION['vector_unique_docquery'];
	
		for($j=0; $j<sizeof($doc_arr_str); $j++){
			$dot_product[$j]=0;
			$query_square[$j]=0;
			$doc_square[$j]=0;
			//calculate dot&cross product
			for($i=0; $i<sizeof($vector_unique_docquery[$j]); $i++){
				$dot_product[$j]+=$tfidf_query[$j][$i]*$tfidf_doc[$j][$i];
				$query_square[$j]+=pow($tfidf_query[$j][$i],2);
				$doc_square[$j]+=pow($tfidf_doc[$j][$i],2);
				$cross_product[$j]=sqrt($query_square[$j])*sqrt($doc_square[$j]);
			}
			//calculate the rank
			$rank_compute[$j]=$dot_product[$j]/$cross_product[$j];
		}
		arsort($rank_compute);
	?>
	<table border=1>
		<thead>
			<td>Doc</td>
			<td>Rank Compute</td>
		</thead>
		<?php foreach ($rank_compute as $doc => $comp){?>
		<tr>
			<td>Doc<?php echo $doc+1;?></td>
			<td><?php echo $comp;?></td>
		</tr>
		<?php }?>
	</table>
</body>
</html>