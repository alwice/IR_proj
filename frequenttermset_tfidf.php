<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>Frequent Termset Weight</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="frequenttermset_tfidf.php">&nbsp;Frequent Termset Weight</a>&nbsp;&nbsp;
	<br><br>
	<?php
		//load session
		$doc_arr_str=$_SESSION['doc_arr_str'];
		$doc_arr_arr=$_SESSION['doc_arr_arr'];
		$threshold=$_SESSION['threshold'];
		$arr_query=$_SESSION['arr_query'];
		$arr_unique_query=$_SESSION['arr_unique_query'];
		$termset_new_arr_str=$_SESSION['termset_new_arr_str'];
		$termset_new_doc=$_SESSION['termset_new_doc'];
		$termset_new_hz=$_SESSION['termset_new_hz'];
		$termset_new_arr_arr=$_SESSION['termset_new_arr_arr'];
	?>
	<button onclick="topFunction()" id="topBtn" title="Go to top">Top</button>
	<ol><li><a href='#termset'>Termset</a></li>
		<li><a href='#query_weight'>Query Weight</a></li>
		<?php for($j=0,$no=1; $j<sizeof($doc_arr_str); $j++,$no++){ ?>
	<li><a href='#doc<?php echo $no;?>'>Doc<?php echo $no;?></a></li>
	<?php }?></ol>
	
	<?php
		echo "<ol><b><li id='termset'>Termset</li></b>";
	?>
	<table border=1>
		<thead>
			<td>Set of Terms</td>
			<td>Documents</td>
			<td>Frequency</td>
		</thead>
		<?php for($i=0; $i<sizeof($termset_new_arr_str); $i++){?>
		<tr>
			<td><?php echo $termset_new_arr_str[$i];?></td>
			<td><?php for($k=0; $k<sizeof($termset_new_doc[$i]); $k++){ 
				echo $termset_new_doc[$i][$k]." ";
				}?></td>
			<td><?php echo $termset_new_hz[$i];?></td>
		</tr>
		<?php }?>
	</table>
	<br>
		
	<?php
		//for doc
		for($j=0,$no=1; $j<sizeof($doc_arr_str); $j++,$no++){
			//doc 1t process & tf & idf & tfidf
			$arr_unique_doc[$j]=explode(' ', implode(' ', array_unique($doc_arr_arr[$j])));
			//find existed
			for($k=0; $k<sizeof($arr_unique_doc[$j]); $k++){
				//process idf for 1t doc 
				$calc_docs=0;
				for($l=0; $l<sizeof($doc_arr_arr); $l++){
					//process tf for 1t doc 
					$calc_terms=0;
					if($j==$l){
						if(in_array($arr_unique_doc[$j][$k], $doc_arr_arr[$l])){
							$tmp=array_count_values($doc_arr_arr[$l]);
							$calc_terms=$tmp[$arr_unique_doc[$j][$k]];
							$calc_docs++;
						}
						$freq_ft1t_doc[$j][$k]=$calc_terms;
						//tf for 1t doc 
						if($freq_ft1t_doc[$j][$k]>0){
							$tf_ft1t_doc[$j][$k]=number_format(1+log($freq_ft1t_doc[$j][$k],2), 3, '.', ',');
						}
						else{
							$tf_ft1t_doc[$j][$k]=0;
						}	
					}
					else{
						if(in_array($arr_unique_doc[$j][$k], $doc_arr_arr[$l])){
							$calc_docs++;
						}
					}				
				}
				//idf for 1t doc
				$dfreq_ft1t_doc[$j][$k]=$calc_docs;
				$idf_ft1t_doc[$j][$k]=number_format(log(1+(sizeof($doc_arr_str)/$dfreq_ft1t_doc[$j][$k]),2), 3, '.', ',');
				
				//weight for 1t doc 
				$tfidf_ft1t_doc[$j][$k]=number_format($tf_ft1t_doc[$j][$k]*$idf_ft1t_doc[$j][$k], 3, '.', ',');
			}

			//for doc tf & idf & tfidf
			for($k=0; $k<sizeof($termset_new_arr_arr); $k++){
				//tf for doc 
				for($l=0; $l<sizeof($termset_new_arr_arr[$k]); $l++){
					$hz[$k][$l]=0;
					if(in_array($termset_new_arr_arr[$k][$l], $doc_arr_arr[$j])){
						$tmp=array_count_values($doc_arr_arr[$j]);
						$hz[$k][$l]=$tmp[$termset_new_arr_arr[$k][$l]];
					}
				}
				$freq_ft_doc[$j][$k]=min($hz[$k]);
				if($freq_ft_doc[$j][$k]>0){
					$tf_ft_doc[$j][$k]=number_format(1+log($freq_ft_doc[$j][$k],2), 3, '.', ',');
				}
				else{
					$tf_ft_doc[$j][$k]=0;
				}		

				//idf for doc & query
				if($termset_new_hz[$k]<sizeof($doc_arr_str)){
					$idf_ft[$k]=number_format(log(1+sizeof($doc_arr_str)/$termset_new_hz[$k],2), 3, '.', ',');
				}
				else{
					$idf_ft[$k]=0;
				}
		
				//weight for doc 
				$tfidf_ft_doc[$j][$k]=number_format($tf_ft_doc[$j][$k]*$idf_ft[$k], 3, '.', ',');
			}
		}

		//for query
		//for 1t query
		for($k=0; $k<sizeof($arr_unique_query); $k++){
			//tf for 1t query 
			$hz1t[$k]=0;
			if(in_array($arr_unique_query[$k], $arr_query)){
				$tmp=array_count_values($arr_query);
				$hz1t[$k]=$tmp[$arr_unique_query[$k]];
			}
			$freq_ft1t_query[$k]=$hz1t[$k];
			if($freq_ft1t_query[$k]>0){
				$tf_ft1t_query[$k]=number_format(1+log($freq_ft1t_query[$k],2), 3, '.', ',');
			}
			else{
				$tf_ft1t_query[$k]=0;
			}
			
			//idf for 1t query
			if($termset_new_hz[$k]<sizeof($doc_arr_str)){
				$idf_ft1t_query[$k]=number_format(log(1+sizeof($doc_arr_str)/$termset_new_hz[$k],2), 3, '.', ',');
			}
			else{
				$idf_ft1t_query[$k]=0;
			}
		
			//weight for 1t query 
			$tfidf_ft1t_query[$k]=number_format($tf_ft1t_query[$k]*$idf_ft1t_query[$k], 3, '.', ',');
		}

		//for query
		for($k=0; $k<sizeof($termset_new_arr_arr); $k++){
			//tf for query 
			for($l=0; $l<sizeof($termset_new_arr_arr[$k]); $l++){
				$hz[$k][$l]=0;
				if(in_array($termset_new_arr_arr[$k][$l], $arr_unique_query)){
					$tmp=array_count_values($arr_unique_query);
					$hz[$k][$l]=$tmp[$termset_new_arr_arr[$k][$l]];
				}
			}
			$freq_ft_query[$k]=min($hz[$k]);
			if($freq_ft_query[$k]>0){
				$tf_ft_query[$k]=number_format(1+log($freq_ft_query[$k],2), 3, '.', ',');
			}
			else{
				$tf_ft_query[$k]=0;
			}
		
			//weight for query 
			$tfidf_ft_query[$k]=number_format($tf_ft_query[$k]*$idf_ft[$k], 3, '.', ',');
		}
		echo "<b><li id='query_weight'>Query Weight</li></b>";
	?>
		<!--Print query weight-->
		<table border=1>
			<thead>
				<td>Terms</td>
				<td>tf_query</td><td>idf</td><td>weight_query</td>
			</thead>
			<?php for($k=0,$n=1; $k<sizeof($termset_new_arr_arr); $k++,$n++){?>
			<tr>
				<td><?php echo $termset_new_arr_str[$k];?></td>
				<td><?php echo $tf_ft_query[$k];?></td>
				<td><?php echo $idf_ft[$k];?></td>
				<td><?php echo $tfidf_ft_query[$k];?></td>
			</tr>
			<?php }?>
		</table>
		<br><br>
		<table border=1>
			<thead>
				<td>Terms</td>
				<td>tf_1t_query</td><td>idf_1t_query</td><td>weight_1t_query</td>
			</thead>
			<?php for($k=0,$n=1; $k<sizeof($arr_unique_query); $k++,$n++){?>
			<tr>
				<td><?php echo $arr_unique_query[$k];?></td>
				<td><?php echo $tf_ft1t_query[$k];?></td>
				<td><?php echo $idf_ft1t_query[$k];?></td>
				<td><?php echo $tfidf_ft1t_query[$k];?></td>
			</tr>
			<?php }?>
		</table>
		<br><br>
	<?php 
		for($j=0,$no=1; $j<sizeof($doc_arr_str); $j++,$no++){
			echo "<b><li id='doc".$no."'>Doc".$no."</li></b>";
	?>
			<!--Print document weight-->
			<table border=1>
				<thead>
					<td>Number</td><td>Terms</td>
					<td>tf_doc</td><td>idf</td><td>weight_doc</td>
				</thead>
				<?php for($k=0,$n=1; $k<sizeof($termset_new_arr_arr); $k++,$n++){?>
				<tr>
					<td><?php echo $n;?></td>
					<td><?php echo $termset_new_arr_str[$k];?></td>
					<td><?php echo $tf_ft_doc[$j][$k];?></td>
					<td><?php echo $idf_ft[$k];?></td>
					<td><?php echo $tfidf_ft_doc[$j][$k];?></td>
				</tr>
				<?php }?>
			</table>
			<br><br>

			<table border=1>
				<thead>
					<td>Number</td><td>Terms</td>
					<td>tf_1t_doc</td><td>idf_1t_doc</td><td>weight_1t_doc</td>
				</thead>
				<?php for($k=0,$n=1; $k<sizeof($arr_unique_doc[$j]); $k++,$n++){?>
				<tr>
					<td><?php echo $n;?></td>
					<td><?php echo $arr_unique_doc[$j][$k];?></td>
					<td><?php echo $tf_ft1t_doc[$j][$k];?></td>
					<td><?php echo $idf_ft1t_doc[$j][$k];?></td>
					<td><?php echo $tfidf_ft1t_doc[$j][$k];?></td>
				</tr>
				<?php }?>
			</table>
			<br><br>
	<?php }
		echo "</ol><br>";
		//store into session
		$_SESSION['arr_unique_doc']=$arr_unique_doc;
		$_SESSION['tf_ft_doc']=$tf_ft_doc;
		$_SESSION['idf_ft']=$idf_ft;
		$_SESSION['tfidf_ft_doc']=$tfidf_ft_doc;
		$_SESSION['tf_ft_query']=$tf_ft_query;
		$_SESSION['tfidf_ft_query']=$tfidf_ft_query;
		$_SESSION['tfidf_ft1t_doc']=$tfidf_ft1t_doc;
		$_SESSION['tfidf_ft1t_query']=$tfidf_ft1t_query;
		
		//echo "<script>location.href='frequenttermset_rank.php';</script>";
	?>
</body>
</html>