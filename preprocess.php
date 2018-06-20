<!DOCTYPE html>
<html>
<head>
	<?php 
		include('menu.php');
		include('class.pdf2text.php');
	?>
	<title>Terms List</title>
</head>
<body>
	<a href="index.php">&nbsp;Home</a>&nbsp;&nbsp;>
	<a href="preprocess.php">&nbsp;Terms List</a>&nbsp;&nbsp;
	<br><br>
	<button onclick="topFunction()" id="topBtn" title="Go to top">Top</button>
	<?php
		//process directory&get file
		$directory=isset($_POST['directory'])?$_POST['directory']:$_SESSION['directory'];
		$_SESSION['directory']=$directory;
		//get files content
		for($i=1,$f=0; $i<=10; $i++,$f++){
			$a = new PDF2Text();
			$a->setFilename($directory.'/P'.$i.'.pdf');
			$a->decodePDF();
			//save pdf into array
			$files_ori[$f]=$a->output();
		}

		//process regular expression
		for($f=0; $f<10; $f++){
			//capital to lower case
			$str1=strtolower($files_ori[$f]);

			//(\d),(\d)=3,000->\1\2=3000 \1= 1stgrp(\d)
			//remove biaodianfuhao
			//strips excess space
			$pattern1=array('/(\d)[,](\d)/', '/[,?!;:"\'\/()]/', '/[-]/', '/(\S?)[.](\S?)/', '/\s\s+/');
			$replacement1=array('\1\2', '', ' ', '\1\2', ' ');
			$str2=preg_replace($pattern1, $replacement1, $str1);

			switch($f){
				case 0://P1.pdf
					$arr=explode(' ', $str2);
					$arr[145]='kampung';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;
				
				case 1://P2.pdf
					$arr=explode(' ', $str2);
					$arr[117]='from';
					$arr[206]='affected';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				case 2://P3.pdf
					$arr=explode(' ', $str2);
					$arr[37]='prime';
					$arr[87]='casting';
					$arr[206]='affected';
					$arr[221]='important';
					$arr[266]='bn';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				case 3://P4.pdf
					$arr=explode(' ', $str2);
					$arr[21]='bobby';
					$arr[65]='today';
					$arr[111]='realised';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				case 4://P5.pdf
					$arr=explode(' ', $str2);
					$arr[37]='classified';
					$arr[80]='khairi';
					$arr[123]='13th';
					$arr[164]='peoples';
					$arr[204]='throughout';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				case 5://P6.pdf
					$arr=explode(' ', $str2);
					$arr[68]='ridzuan';
					$arr[108]='four';
					$arr[148]='volunteers';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				case 6://P7.pdf
					$arr=explode(' ', $str2);
					$arr[127]='on';
					$arr[86]='centre';
					$arr[168]='director';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				case 7://P8.pdf
					$arr=explode(' ', $str2);
					$arr[58]='after';
					$arr[105]='expected';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				case 8://P9.pdf
					$arr=explode(' ', $str2);
					$arr[275]='13';
					array_splice($arr, 276, 1);
					$arr[41]='announcement';
					$arr[83]='the';
					$arr[156]='revenue';
					$arr[198]='but';
					$arr[236]='need';
					$arr[277]='points';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;
				
				case 9://P10.pdf
					$arr=explode(' ', $str2);
					$arr[37]='general';
					$arr[119]='2014';
					$arr[191]='gst';
					array_pop($arr);
					$str2=implode(' ', $arr);
					break;

				default:
					break;
			}
		
			//remove repeated word
			//explode=str2array
			$_SESSION['doc_arr_str'][$f]=$str2;
			$_SESSION['doc_arr_arr'][$f]=explode(' ', $str2);
			$str3=implode(' ',array_unique(explode(' ', $str2)));
			$files_process[$f]=$str3;
		}

		//save all into individual term in an array
		$str_all=implode(' ', $files_process);
		$arr_prepare_terms=array_unique(explode(' ', $str_all));
		$str_terms=implode(' ', $arr_prepare_terms);
		$arr_terms=explode(' ', $str_terms);
		
		$_SESSION['str_all']=$str_all;
		$_SESSION['arr_terms']=$arr_terms;
		$_SESSION['str_terms']=$str_terms;
		for($i=0,$no=1; $i<sizeof($arr_terms); $i++,$no++){
			echo $no."= ".$arr_terms[$i]."<br>";
		}

	echo "<script>location.href='tfidf.php';</script>";
	?>
</body>
</html>