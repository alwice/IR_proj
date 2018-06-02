<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>TFIDF</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="preprocess.php">&nbsp;Terms List</a>&nbsp;&nbsp;>
	<a href="tfidf.php">&nbsp;TFIDF</a>&nbsp;&nbsp;
	<br><br>
	<?php
		$doc_arr_str=$_SESSION['doc_arr_str'];
		$doc_arr_arr=$_SESSION['doc_arr_arr'];
		$str_all=$_SESSION['str_all'];
		$arr_terms=$_SESSION['arr_terms'];
		$str_terms=$_SESSION['str_terms'];
	?>
	<button onclick="topFunction()" id="topBtn" title="Go to top">Top</button>
	<ol><li><a href='#tf'>tf</a></li>
		<li><a href='#idf'>idf</a></li>
		<li><a href='#tfidf'>tfidf</a></li>
	</ol>
	
	<ol><b><li id='tf'>tf</li></b>
		<table border="1">
			<thead><tr>
				<td>Number</td><td>Vocabulary</td>
				<td>Document 1</td><td>Document 2</td><td>Document 3</td><td>Document 4</td><td>Document 5</td>
				<td>Document 6</td><td>Document 7</td><td>Document 8</td><td>Document 9</td><td>Document 10</td>
			</tr></thead>
			<?php for($i=0,$no=1; $i<sizeof($arr_terms); $i++,$no++){?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $arr_terms[$i];?></td>
					<?php for($j=0; $j<sizeof($doc_arr_str); $j++){?>
						<td><?php 
							$hz=0;
							//check string not array
							//if(strpos($documents[$j], $arr_terms[$i]) !== false){$hz++;} 
							if(in_array($arr_terms[$i], $doc_arr_arr[$j])){
								$tmp=array_count_values($doc_arr_arr[$j]);
		    					$hz=$tmp[$arr_terms[$i]];
		    				}
							$freq[$i][$j]=$hz;
							if($freq[$i][$j]>0){
								$tf[$i][$j]=number_format(1+log($freq[$i][$j],2), 3, '.', ',');
								echo $tf[$i][$j];
							}
							else{
								$tf[$i][$j]=0;
								echo "-";
							}
						?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</table>

		<br>
		<b><li id='idf'>idf</li></b>
		<table border="1">
			<thead><tr>
				<td>Number</td><td>Terms</td>
				<td>n</td><td>idf</td>
			</tr></thead>
			<?php for($i=0,$no=1; $i<sizeof($arr_terms); $i++,$no++){?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $arr_terms[$i];?></td>
					<td><?php for($j=0,$n=0; $j<sizeof($doc_arr_str); $j++){
							$hz=0;
							if(in_array($arr_terms[$i], $doc_arr_arr[$j])){$hz++;} 
							if($hz>0){$n++;}
							$existedDocHz[$i]=$n;
						} 
						echo $existedDocHz[$i];?></td>
					<td><?php 
						if($existedDocHz[$i]<10){
							$idf[$i]=number_format(log(10/$existedDocHz[$i],2), 3, '.', ',');
							echo $idf[$i];
						}
						else{
							$idf[$i]=0;
							echo $idf[$i];
						}
					?></td>
				</tr>
			<?php } ?>
		</table>

		<br>
		<b><li id='tfidf'>tfidf</li></b>
		<table border="1">
			<thead><tr>
				<td>Number</td><td>Terms</td>
				<td>Document 1</td><td>Document 2</td>
				<td>Document 3</td><td>Document 4</td>
				<td>Document 5</td><td>Document 6</td>
				<td>Document 7</td><td>Document 8</td>
				<td>Document 9</td><td>Document 10</td>
			</tr></thead>
			<?php for($i=0,$no=1; $i<sizeof($arr_terms); $i++,$no++){?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $arr_terms[$i];?></td>
					<?php for($j=0; $j<sizeof($doc_arr_str); $j++){?>
						<td><?php 
							$tfidf[$i][$j]=number_format($tf[$i][$j]*$idf[$i], 3, '.', ',');
							if($tfidf[$i][$j]>0){
								echo $tfidf[$i][$j];
							}
							else{
								$tfidf[$i][$j]=number_format(0, 0);
								echo '-';
							}
						?></td>
					<?php } ?>
				</tr>
			<?php } 
			?>
		</table>
	</ol>
	<?php
		//transpose tf&tfidf into [doc][term]
		array_unshift($tf, null);
		$tf_trans=call_user_func_array('array_map', $tf);
		array_unshift($tfidf, null);
		$tfidf_trans=call_user_func_array('array_map', $tfidf);

		//store into session
		$_SESSION['tf']=$tf_trans;
		$_SESSION['idf']=$idf;
		$_SESSION['tfidf']=$tfidf_trans;
	?>
</body>
</html>