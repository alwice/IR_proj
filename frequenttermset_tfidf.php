<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>Frequent Termset Weight</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="frequenttermset_tfidf.php">&nbsp;Frequent Termset</a>&nbsp;&nbsp;
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
	<ol><li><a href='#termset'>Termset</a></li>
		<li><a href='#query_weight'>Query Weight</a></li>
		<?php for($j=0,$no=1; $j<sizeof($doc_arr_str); $j++,$no++){ ?>
	<li><a href='#doc<?php echo $no;?>'>Doc<?php echo $no;?></a></li>
	<?php }?></ol>
	
	<?php
		//process query
		$str_query=isset($_POST['query'])?$_POST['query']:$_SESSION['str_query'];
		$_SESSION['str_query']=$str_query;
		$threshold=isset($_POST['threshold'])?$_POST['threshold']:$_SESSION['threshold'];
		$_SESSION['threshold']=$threshold;
		$arr_query=explode(' ', $str_query);
		$str_unique_query=implode(' ', array_unique($arr_query));
		$arr_unique_query=explode(' ', $str_unique_query);
		$_SESSION['arr_query']=$arr_query;
		$_SESSION['str_unique_query']=$str_unique_query;
		$_SESSION['arr_unique_query']=$arr_unique_query;

		//set termset+
		$termset_arr_str[0]=NULL;
		$termset_arr_str[1]=$arr_unique_query[0];
		$termset_arr_str[2]=$arr_unique_query[1];
		$termset_arr_str[3]=$arr_unique_query[2];
		$termset_arr_str[4]=$arr_unique_query[0].' '.$arr_unique_query[1];
		$termset_arr_str[5]=$arr_unique_query[0].' '.$arr_unique_query[2];
		$termset_arr_str[6]=$arr_unique_query[1].' '.$arr_unique_query[2];
		$termset_arr_str[7]=$arr_unique_query[0].' '.$arr_unique_query[1].' '.$arr_unique_query[2];

		echo "<ol><b><li id='termset'>Termset</li></b>";
		echo "Default Termset: ";
		print_r($termset_arr_str);
		echo "<br><br>";

		//process termset into arr
		for($i=0; $i<sizeof($termset_arr_str); $i++){
			$termset_arr_arr[$i]=explode(' ', $termset_arr_str[$i]);
		}
		//find existed
		for($i=0; $i<sizeof($termset_arr_str); $i++){
			$termset_documents[$i]=array();
			$calc_docs=0;
			for($j=0,$n=1; $j<sizeof($doc_arr_arr); $j++,$n++){
				$calc_terms=0;
				for($k=0; $k<sizeof($termset_arr_arr[$i]); $k++){
					if(in_array($termset_arr_arr[$i][$k], $doc_arr_arr[$j])){
						$calc_terms++;
					}
				}
				if($calc_terms==sizeof($termset_arr_arr[$i])){
					$calc_docs++;
					array_push($termset_documents[$i], "d".$n);
				}
			}
			$termset_hz[$i]=$calc_docs;
		}
		//delete empty termset
		$termset_temp_hz=$termset_new_key=array_diff($termset_hz,[0]);
		$termset_temp_hz=array_values($termset_temp_hz);
		
		for($t=$threshold-1; $t>0; $t--){
			$termset_new_key=array_diff($termset_new_key,[$t]);	
		}
		$termset_new_hz=array_values($termset_new_key);
		$termset_new_arr_str=array_values(array_intersect_key($termset_arr_str, $termset_new_key));
		$termset_new_doc=array_values(array_intersect_key($termset_documents, $termset_new_key));
		//process new_termset into arr
		for($i=0; $i<sizeof($termset_new_arr_str); $i++){
			$termset_new_arr_arr[$i]=explode(' ', $termset_new_arr_str[$i]);
		}

		echo "New Termset: ";
		print_r($termset_new_arr_arr);
		echo "<br><br>";

		//save into session
		$_SESSION['termset_arr_arr']=$termset_arr_arr;
		$_SESSION['termset_arr_str']=$termset_arr_str;
		$_SESSION['termset_new_arr_str']=$termset_new_arr_str;
		$_SESSION['termset_new_doc']=$termset_new_doc;
		$_SESSION['termset_new_hz']=$termset_new_hz;
		$_SESSION['termset_new_arr_arr']=$termset_new_arr_arr;
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
		//for 1t query
		for($k=0; $k<sizeof($arr_unique_query); $k++){
			//tf for 1t query 
			$hz1t[$k]=0;
			if(in_array($arr_unique_query[$k], $arr_unique_query)){
				$tmp=array_count_values($arr_unique_query);
				$hz1t[$k]=$tmp[$arr_unique_query[$k]];
			}
			$freq_ft1t_query[$k]=$hz1t[$k];
			if($freq_ft1t_query[$k]>0){
				$tf_ft1t_query[$k]=number_format(1+log($freq_ft1t_query[$k],2), 3, '.', ',');
			}
			else{
				$tf_ft1t_query[$k]=0;
			}
			
			//idf for 1t doc & query
			if($termset_temp_hz[$k]<sizeof($doc_arr_str)){
				$idf_ft1t[$k]=number_format(log(1+sizeof($doc_arr_str)/$termset_temp_hz[$k],2), 3, '.', ',');
			}
			else{
				$idf_ft1t[$k]=0;
			}
		
			//weight for 1t query 
			$tfidf_ft1t_query[$k]=number_format($tf_ft1t_query[$k]*$idf_ft1t[$k], 3, '.', ',');
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
		
			//idf for doc & query
			if($termset_new_hz[$k]<sizeof($doc_arr_str)){
				$idf_ft[$k]=number_format(log(1+sizeof($doc_arr_str)/$termset_new_hz[$k],2), 3, '.', ',');
			}
			else{
				$idf_ft[$k]=0;
			}
		
			//weight for query 
			$tfidf_ft_query[$k]=number_format($tf_ft_query[$k]*$idf_ft[$k], 3, '.', ',');
		}
		echo "<b><li id='query_weight'>Query Weight</li></b>";
	?>
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
				<td>tf_1t_query</td><td>idf_1t</td><td>weight_1t_query</td>
			</thead>
			<?php for($k=0,$n=1; $k<sizeof($arr_unique_query); $k++,$n++){?>
			<tr>
				<td><?php echo $arr_unique_query[$k];?></td>
				<td><?php echo $tf_ft1t_query[$k];?></td>
				<td><?php echo $idf_ft1t[$k];?></td>
				<td><?php echo $tfidf_ft1t_query[$k];?></td>
			</tr>
			<?php }?>
		</table>
		<br><br>
	<?php
		//for doc
		for($j=0,$no=1; $j<sizeof($doc_arr_str); $j++,$no++){
			echo "<b><li id='doc".$no."'>Doc".$no."</li></b>";
			//for 1t doc
			for($k=0; $k<sizeof($arr_unique_query); $k++){
				//tf for 1t doc 
				$hz1t[$k]=0;
				if(in_array($arr_unique_query[$k], $doc_arr_arr[$j])){
					$tmp=array_count_values($doc_arr_arr[$j]);
					$hz1t[$k]=$tmp[$arr_unique_query[$k]];
				}
				$freq_ft1t_doc[$j][$k]=$hz1t[$k];
				if($freq_ft1t_doc[$j][$k]>0){
					$tf_ft1t_doc[$j][$k]=number_format(1+log($freq_ft1t_doc[$j][$k],2), 3, '.', ',');
				}
				else{
					$tf_ft1t_doc[$j][$k]=0;
				}			

				//weight for 1t doc 
				$tfidf_ft1t_doc[$j][$k]=number_format($tf_ft1t_doc[$j][$k]*$idf_ft1t[$k], 3, '.', ',');

			} 
			//for doc
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

				//weight for doc 
				$tfidf_ft_doc[$j][$k]=number_format($tf_ft_doc[$j][$k]*$idf_ft[$k], 3, '.', ',');
			}
	?>
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
					<td>tf_1t_doc</td><td>idf_1t</td><td>weight_1t_doc</td>
				</thead>
				<?php for($k=0,$n=1; $k<sizeof($arr_unique_query); $k++,$n++){?>
				<tr>
					<td><?php echo $n;?></td>
					<td><?php echo $arr_unique_query[$k];?></td>
					<td><?php echo $tf_ft1t_doc[$j][$k];?></td>
					<td><?php echo $idf_ft1t[$k];?></td>
					<td><?php echo $tfidf_ft1t_doc[$j][$k];?></td>
				</tr>
				<?php }?>
			</table>
			<br><br>
	<?php }
		echo "</ol><br>";
		//store into session
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