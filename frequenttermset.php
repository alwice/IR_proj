<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>Frequent Termset</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="frequenttermset.php">&nbsp;Frequent Termset</a>&nbsp;&nbsp;
	<br><br>
	<button onclick="topFunction()" id="topBtn" title="Go to top">Top</button>
	<ol><li><a href='#oritermset'>Original Termset</a></li>
		<?php for($t=0, $th=1; $t<3; $t++,$th++){?>
			<li><a href='#<?php echo $th;?>termsets'>Frequent <?php echo $th;?>-Termsets</a></li>
		<?php }?>
		<li><a href='#newtermset'>New Termset</a></li>
	</ol>
	
	<?php
		//load session
		$str_query=$_SESSION['str_query'];


		//process query
		$doc_arr_arr=$_SESSION['doc_arr_arr'];
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
		$termset_arr_str[4]=$arr_unique_query[0].', '.$arr_unique_query[1];
		$termset_arr_str[5]=$arr_unique_query[0].', '.$arr_unique_query[2];
		$termset_arr_str[6]=$arr_unique_query[1].', '.$arr_unique_query[2];
		$termset_arr_str[7]=$arr_unique_query[0].', '.$arr_unique_query[1].', '.$arr_unique_query[2];

		echo "<ol><b><li id='oritermset'>Original Termset</li></b>";
		echo "<table border=1><thead><td><b>Original Termset</b></td></thead>";
		for($s=0; $s<sizeof($termset_arr_str); $s++){
			if($s==0){
				echo "<tr><td>{}</td></tr>";
			}
			else{
				echo "<tr><td>{".$termset_arr_str[$s]."}</td></tr>";
			}
		}
		echo "</table>";
		echo "Threshold: ".$threshold;
		echo "<br><br>";

		//process termset into arr
		for($i=0; $i<sizeof($termset_arr_str); $i++){
			$termset_arr_arr[$i]=explode(', ', $termset_arr_str[$i]);
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
			$termset_new_arr_arr[$i]=explode(', ', $termset_new_arr_str[$i]);
		}		
		
		//for frequent termsets
		for($t=0,$th=1; $t<3; $t++,$th++){
			echo "<b><li id='".$th."termsets'>Frequent ".$th."-Termsets</li></b>";
			echo "<table border=1><thead><td><b>Set of Terms</b></td><td><b>Documents</b></td></thead>";
				for($i=0,$n=0; $i<sizeof($termset_new_arr_str); $i++,$n++){
					if(sizeof($termset_new_arr_arr[$n])==$th){
						echo "<tr><td>".$termset_new_arr_str[$i]."</td><td>";
							for($k=0; $k<sizeof($termset_new_doc[$i]); $k++){ 
								echo $termset_new_doc[$i][$k]." ";
							}
						echo "</td></tr>";
					}
				}
			echo "</table><br>";
		}

		//for new term
		echo "<b><li id='newtermset'>New Termset</li></b>";
	?>
	<table border=1>
		<thead>
			<td><b>Set of Terms</b></td>
			<td><b>Documents</b></td>
		</thead>
		<?php for($i=0; $i<sizeof($termset_new_arr_str); $i++){?>
		<tr>
			<td><?php echo $termset_new_arr_str[$i];?></td>
			<td><?php for($k=0; $k<sizeof($termset_new_doc[$i]); $k++){ 
				echo $termset_new_doc[$i][$k]." ";
			//	$count_ft=$k;
				}?></td>
		</tr>
		<?php }?>
	</table>
	<?php echo "There are ".$count_ft." Frequent Termsets.";?>
	<br>	
	
	<?php 
		echo "</ol><br>";
		//store into session
		$_SESSION['termset_arr_arr']=$termset_arr_arr;
		$_SESSION['termset_arr_str']=$termset_arr_str;
		$_SESSION['termset_new_arr_str']=$termset_new_arr_str;
		$_SESSION['termset_new_doc']=$termset_new_doc;
		$_SESSION['termset_new_hz']=$termset_new_hz;
		$_SESSION['termset_new_arr_arr']=$termset_new_arr_arr;
		//echo "<script>location.href='frequenttermset_rank.php';</script>";
	?>
</body>
</html>