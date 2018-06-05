<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>Frequent Termset Rank</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="frequenttermset_tfidf.php">&nbsp;Frequent Termset Weight</a>&nbsp;&nbsp;>
	<a href="frequenttermset_rank.php">&nbsp;Frequent Termset Rank</a>&nbsp;&nbsp;
	<br><br>
	<?php
		//load session
		$doc_arr_str=$_SESSION['doc_arr_str'];
		$arr_unique_query=$_SESSION['arr_unique_query'];
		$arr_unique_doc=$_SESSION['arr_unique_doc'];
		$tfidf_ft_doc=$_SESSION['tfidf_ft_doc'];
		$tfidf_ft_query=$_SESSION['tfidf_ft_query'];
		$tfidf_ft1t_doc=$_SESSION['tfidf_ft1t_doc'];
		$tfidf_ft1t_query=$_SESSION['tfidf_ft1t_query'];

		//calculate cross_query
		$query_square=0;
		for($i=0; $i<sizeof($arr_unique_query); $i++){
			$query_square+=pow($tfidf_ft1t_query[$i],2);
		}

		for($j=0; $j<sizeof($doc_arr_str); $j++){
			$dot_product[$j]=0;
			$doc_square[$j]=0;
			//calculate cross_doc
			for($k=0; $k<sizeof($arr_unique_doc[$j]); $k++){
				$doc_square[$j]+=pow($tfidf_ft1t_doc[$j][$k],2);
			}

			//calculate dot&cross product
			for($i=0; $i<sizeof($tfidf_ft_query); $i++){
				$cross_product[$j]=sqrt($query_square)*sqrt($doc_square[$j]);
				if($tfidf_ft_query[$i]!=0 && $tfidf_ft_doc[$j][$i]!=0){
					$dot_product[$j]+=$tfidf_ft_query[$i]*$tfidf_ft_doc[$j][$i];
				}
			}
			//calculate the rank
			$rank_compute[$j]=number_format($dot_product[$j]/$cross_product[$j], 3, '.', ',');
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
		<?php 
			}
			$rerank=array_values($rank_compute);
			$redocument=array_keys($rank_compute);	
			for($i=0;$i<sizeof($rank_compute);$i++){
				$ft_rank[$i][0]=$redocument[$i]+1;
				$ft_rank[$i][1]=$rerank[$i];
			}
			$_SESSION['ft_ranking']=$ft_rank;
			$_SESSION['ft_rank_value']=$rerank;
			$_SESSION['ft_rank_doc']=$redocument;
		?>
	</table>
</body>
</html>