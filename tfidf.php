<!DOCTYPE html>
<html>
<head>
	<?php session_start();?>
	<title>tfidf</title>
	<style>
		/*style to top btn*/
		#topBtn {
		    display: none; /* Hidden by default */
		    position: fixed; /* Fixed/sticky position */
		    bottom: 20px; /* Place the button at the bottom of the page */
		    right: 30px; /* Place the button 30px from the right */
		    z-index: 99; /* Make sure it does not overlap */
		    border: none; /* Remove borders */
		    outline: none; /* Remove outline */
		    background-color: red; /* Set a background color */
		    color: white; /* Text color */
		    cursor: pointer; /* Add a mouse pointer on hover */
		    padding: 15px; /* Some padding */
		    border-radius: 10px; /* Rounded corners */
		    font-size: 18px; /* Increase font size */
		}

		#topBtn:hover {
		    background-color: #555; /* Add a dark-grey background on hover */
		}
	</style>
	<script>
		// When the user scrolls down 20px from the top of the document, show the button
		window.onscroll = function() {scrollFunction()};

		function scrollFunction() {
		    if (document.body.scrollTop >20 || document.documentElement.scrollTop >20) {
		        document.getElementById("topBtn").style.display = "block";
		    } else {
		        document.getElementById("topBtn").style.display = "none";
		    }
		}

		// When the user clicks on the button, scroll to the top of the document
		function topFunction() {
		    document.body.scrollTop = 0; // For Safari
		    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
		}
	</script>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="tfidf.php">&nbsp;TFIDF</a>&nbsp;&nbsp;
	<?php
		$doc_arr_str=$_SESSION['doc_arr_str'];
		$doc_arr_arr=$_SESSION['doc_arr_arr'];
		$str_all=$_SESSION['string_all'];
		$arr_terms=$_SESSION['array_terms'];
		$str_terms=$_SESSION['string_terms'];
	?>
	<button onclick="topFunction()" id="topBtn" title="Go to top">Top</button>
	<ol><li><a href='#tf'>tf</a></li>
		<li><a href='#idf'>idf</a></li>
		<li><a href='#tfidf'>tfidf</a></li>
	</ol>

	<br>
	<ol><b><li id='tf'>tf</li></b>
		<table border="1">
			<thead><tr>
				<td>Number</td><td>Vocabulary</td>
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
							$idf[$i]=number_format(0, 0);
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
							else
								echo '-';
						?></td>
					<?php } ?>
				</tr>
			<?php } 
				$_SESSION['tf']=$tf;
				$_SESSION['idf']=$idf;
				$_SESSION['tfidf']=$tfidf;
			?>
		</table>
	</ol>
</body>
</html>