<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>Query TFIDF</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="query_tfidf.php">&nbsp;Query tfidf</a>&nbsp;&nbsp;
	<br><br>
	<?php
		//load session
		$doc_arr_str=$_SESSION['doc_arr_str'];
		$doc_arr_arr=$_SESSION['doc_arr_arr'];
		$arr_terms=$_SESSION['arr_terms'];
		$idf=$_SESSION['idf'];
		$tfidf=$_SESSION['tfidf'];
	?>
	<button onclick="topFunction()" id="topBtn" title="Go to top">Top</button>
	<ol><?php for($j=0,$no=1; $j<sizeof($doc_arr_str); $j++,$no++){ ?>
	<li><a href='#doc<?php echo $no;?>'>Doc<?php echo $no;?></a></li>
	<?php }?></ol>
	
	<?php
		//process query
		$str_query=isset($_POST['query'])?$_POST['query']:$_SESSION['str_query'];
		$_SESSION['str_query']=$str_query;
		$arr_query=explode(' ', $str_query);
		$arr_unique_query=explode(' ', implode(' ', array_unique($arr_query)));
		$_SESSION['arr_query']=$arr_query;

		//combine query with doc
		echo "<ol>";
		for($j=0,$no=1; $j<sizeof($doc_arr_str); $j++,$no++){
			$vector_docquery[$j]=array_merge($doc_arr_arr[$j], $arr_unique_query);
			$vector_unique_docquery[$j]=explode(' ', implode(' ', array_unique($vector_docquery[$j])));
			echo "<b><li id='doc".$no."'>Doc".$no."</li></b>";

			//tf for query
			for($k=0; $k<sizeof($vector_unique_docquery[$j]); $k++){
				$hz=0;
				if(in_array($vector_unique_docquery[$j][$k], $arr_query)){
					$tmp=array_count_values($arr_query);
					$hz=$tmp[$vector_unique_docquery[$j][$k]];
				}
				$freq_query[$j][$k]=$hz;
				if($freq_query[$j][$k]>0){
					$tf_query[$j][$k]=number_format(1+log($freq_query[$j][$k],2), 3, '.', ',');
				}
				else{
					$tf_query[$j][$k]=0;
				}	
			}

			//idf for query
			for($i=0; $i<sizeof($arr_terms); $i++){
				for($k=0; $k<sizeof($vector_unique_docquery[$j]); $k++){
					if($vector_unique_docquery[$j][$k]==$arr_terms[$i]){
						$idf_query[$j][$k]=$idf[$i];
					}
				}
			}

			//tfidf for query
			for($k=0; $k<sizeof($vector_unique_docquery[$j]); $k++){	
				$tfidf_query[$j][$k]=number_format($tf_query[$j][$k]*$idf_query[$j][$k], 3, '.', ',');
				if($tfidf_query[$j][$k]==0){
					$tfidf_query[$j][$k]=number_format($tfidf_query[$j][$k], 0, '.', ',');
				}
			}

			//tfidf in doc
			for($k=0; $k<sizeof($vector_unique_docquery[$j]); $k++){
				if(in_array($vector_unique_docquery[$j][$k], $doc_arr_arr[$j])){
					for($l=0; $l<sizeof($doc_arr_arr[$j]); $l++){
						if($vector_unique_docquery[$j][$k]==$doc_arr_arr[$j][$l]){
							for($i=0; $i<sizeof($arr_terms); $i++){
								if($vector_unique_docquery[$j][$k]==$arr_terms[$i]){
									$tfidf_doc[$j][$k]=$tfidf[$j][$i];	
								}
							}						
						}
					}
				}
				else{
					$tfidf_doc[$j][$k]=0;
				}
			}
	?>
			<table border=1>
				<thead>
					<td>Number</td><td>Terms</td>
					<td>tf_query</td><td>idf_query</td><td>tfidf_query</td><td>tfidf_doc</td>
				</thead>
				<?php for($k=0,$n=1; $k<sizeof($vector_unique_docquery[$j]); $k++,$n++){?>
				<tr>
					<td><?php echo $n;?></td>
					<td><?php echo $vector_unique_docquery[$j][$k];?></td>
					<td><?php echo $tf_query[$j][$k];?></td>
					<td><?php echo $idf_query[$j][$k];?></td>
					<td><?php echo $tfidf_query[$j][$k];?></td>
					<td><?php echo $tfidf_doc[$j][$k];?></td>
				</tr>
				<?php }?>
			</table>
			<br><br>
	<?php
		}
		echo "</ol><br>";
		//store into session
		$_SESSION['tf_query']=$tf_query;
		$_SESSION['idf_query']=$idf_query;
		$_SESSION['tfidf_query']=$tfidf_query;
		$_SESSION['tfidf_doc']=$tfidf_doc;
		$_SESSION['vector_unique_docquery']=$vector_unique_docquery;
		echo "</ol>";

		//echo "<script>location.href='query_rank.php';</script>";
	?>
</body>
</html>