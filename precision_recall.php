<!DOCTYPE html>
<html>
<head>
	<?php include('menu.php');?>
	<title>Precision Recall</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="precision_recall.php">&nbsp;Precision Recall</a>&nbsp;&nbsp;
	<br><br>
	<form aciton="" method="POST">
		<button name="submit" type="submit" value="submit">Reset Relevant Document</button>
	</form>
	<br>
	<?php
		//load session
		$query_rank_doc=$_SESSION['query_rank_doc'];
		
		//randomize relavant doc
		$doc_no=rand(1,10);
		$random_relevant=array_rand($query_rank_doc, $doc_no);
		
		//catch relavant doc from user, else use session, else use random
		$_SESSION['doc_relevant']=isset($_SESSION['doc_relevant'])?$_SESSION['doc_relevant']:$random_relevant;
		$doc_relevant_str=isset($_POST['doc'])?$_POST['doc']:NULL;
		if($doc_relevant_str==NULL){
			$doc_relevant_arr=$_SESSION['doc_relevant'];
		}
		else{
			$doc_relevant_arr=explode(' ', $doc_relevant_str);
		}
		$_SESSION['doc_relevant']=$doc_relevant_arr;

		//mark relevant
		$marking_doc_relevant=array(0,0,0,0,0,0,0,0,0,0);
		for($k=0; $k<sizeof($doc_relevant_arr); $k++){
			for($i=0; $i<sizeof($query_rank_doc); $i++){
				if($query_rank_doc[$i]==$doc_relevant_arr[$k]){
					$marking_doc_relevant[$i]=1;
				}
			}
		}
		$marked_relevant=array();
		for($i=0; $i<sizeof($marking_doc_relevant); $i++){
			$marked_relevant[$i][0]="d".$query_rank_doc[$i];
			$marked_relevant[$i][1]=$marking_doc_relevant[$i];
		}
		
		//calculate RnA
		$rel_ret=0;
		$retrieved=0;
		for($i=0; $i<sizeof($marked_relevant); $i++){
			if($marked_relevant[$i][1]==1){
				$rel_ret++;
			}
			$retrieved++;
			//calculate precision&recall
			$precision[$i]=number_format($rel_ret/$retrieved, 3, '.', ',');;
			$relevant=sizeof($doc_relevant_arr);
			$recall[$i]=number_format($rel_ret/$relevant, 3, '.', ',');;
		}
	?>
	<table border=1>
		<thead>
			<td>Number</td>
			<td>Document</td>
			<td>Relevant Marking</td>
			<td>Precision</td>
			<td>Recall</td>
		</thead>
		<?php for($i=0; $i<sizeof($marked_relevant); $i++){?>
		<tr>
			<td><?php echo $i+1;?></td>
			<td><?php echo $marked_relevant[$i][0];?></td>
			<td><?php echo $marked_relevant[$i][1];?></td>
			<td><?php echo $precision[$i];?></td>
			<td><?php echo $recall[$i];?></td>
		</tr>
		<?php 
			}
			$_SESSION['marked_relevant']=$marked_relevant;
			$_SESSION['precision']=$precision;
			$_SESSION['recall']=$recall;
		?>
	</table>
</body>
</html>
<?php
	$reset=isset($_POST['submit'])?$_POST['submit']:NULL;
	if($reset!=NULL){
		unset($_SESSION['doc_relevant']);
		echo "<script>location.href='index.php';</script>";
	}
?>