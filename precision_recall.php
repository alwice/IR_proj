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

	<?php
		//load session
		$doc_arr_str=$_SESSION['doc_arr_str'];
		$arr_unique_query=$_SESSION['arr_unique_query'];
		$arr_unique_doc=$_SESSION['arr_unique_doc'];
		$query_ranking=$_SESSION['query_ranking'];
		$query_rank_value=$_SESSION['query_rank_value'];
		$query_rank_doc=$_SESSION['query_rank_doc'];
		
		//randomize relavant doc
		$doc_no=rand(1,10);
		$random_relevant=array_rand($query_rank_doc, $doc_no);
		
		//catch relavant doc from user, else use session, else use random
		$_SESSION['doc_relevant']=isset($_SESSION['doc_relevant'])?$_SESSION['doc_relevant']:$random_relevant;
		$doc_relevant=isset($_POST['doc'])?$_POST['doc']:$_SESSION['doc_relevant'];
		$_SESSION['doc_relevant']=$doc_relevant;
		print_r($doc_relevant);
		echo "<br><br>";

		//mark relevant
		print_r($query_rank_doc);
		echo "<br><br>";
		$marking_doc_relevant=array(0,0,0,0,0,0,0,0,0,0);
		for($k=0; $k<sizeof($doc_relevant); $k++){
			for($i=0; $i<sizeof($query_rank_doc); $i++){
				if($query_rank_doc[$i]==$doc_relevant[$k]){
					$marking_doc_relevant[$i]=1;
				}
			}
		}
		print_r($marking_doc_relevant);





		/*$retrieved=count();
		$precision=$rel_ret/$retrieved;
		$relevant=sizeof($doc_relevant);
		$recall=$rel_ret/$relevant;*/
	?>
	<form aciton="" method="POST">
		<button name="submit" type="submit" value="submit">Reset Relevant Document</button>
	</form>
</body>
</html>
<?php
	$reset=isset($_POST['submit'])?$_POST['submit']:NULL;
	if($reset!=NULL){

		unset($_SESSION['doc_relevant']);
	}
?>