<!DOCTYPE html>
<html>
<head>
	<?php
	session_start();
	include('class.pdf2text.php');
	?>
	<title></title>
</head>
<body>
	<?php
		//process directory&get file
		$submit_directory=isset($_POST['submit_directory']) ? $_POST['submit_directory'] : NULL;
		if($submit_directory!=NULL){
			$directory=$_POST['directory'];
			for($i=1,$f=0; $i<=10; $i++,$f++){
				$a = new PDF2Text();
				$a->setFilename($directory.'/P'.$i.'.pdf');
				$a->decodePDF();
				//save pdf into array
				$files_ori[$f]=$a->output();
			}
		}

		//process regular expression
		for($f=0; $f<10; $f++){
			//capital to lower case
			$string1=strtolower($files_ori[$f]);

			//(\d),(\d)=3,000->\1\2=3000 \1= 1stgrp(\d)
			//remove biaodianfuhao
			//strips excess space
			$pattern1=array('/(\d)[,](\d)/', '/[,?!;:"\'\/()]/', '/[-]/', '/(\S?)[.](\S?)/', '/\s\s+/');
			$replacement1=array('\1\2', '', ' ', '\1\2', ' ');
			$string2=preg_replace($pattern1, $replacement1, $string1);
/*			echo $string2;
			echo '<br><br>';
*/			

			switch ($f) {
				case 0://P1.pdf
					$array=explode(' ', $string2);
					$array[145]='kampung';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;
				
				case 1://P2.pdf
					$array=explode(' ', $string2);
					$array[117]='from';
					$array[206]='affected';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				case 2://P3.pdf
					$array=explode(' ', $string2);
					$array[37]='prime';
					$array[87]='casting';
					$array[206]='affected';
					$array[221]='important';
					$array[266]='bn';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				case 3://P4.pdf
					$array=explode(' ', $string2);
					$array[21]='bobby';
					$array[65]='today';
					$array[111]='realised';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				case 4://P5.pdf
					$array=explode(' ', $string2);
					$array[37]='classified';
					$array[80]='khairi';
					$array[123]='13th';
					$array[164]='peoples';
					$array[204]='throughout';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				case 5://P6.pdf
					$array=explode(' ', $string2);
					$array[68]='ridzuan';
					$array[108]='four';
					$array[148]='volunteers';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				case 6://P7.pdf
					$array=explode(' ', $string2);
					$array[127]='on';
					$array[86]='centre';
					$array[168]='director';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				case 7://P8.pdf
					$array=explode(' ', $string2);
					$array[58]='after';
					$array[105]='expected';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				case 8://P9.pdf
					$array=explode(' ', $string2);
					$array[275]='13';
					array_splice($array, 276, 1);
					$array[41]='announcement';
					$array[83]='the';
					$array[156]='revenue';
					$array[198]='but';
					$array[236]='need';
					$array[277]='points';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;
					//got space between 1 3 in P9.pdf
					/*if($f==8){
						$pattern2=array('/(\d+)\s(\d+)/');
						$replacement2=array('\1\2');
						$string2=preg_replace($pattern2, $replacement2, $string2);
						echo $string2;
						echo '<br><br>';
					}*/

				case 9://P10.pdf
					$array=explode(' ', $string2);
					$array[37]='general';
					$array[119]='2014';
					$array[191]='gst';
					array_pop($array);
					//print_r($array);
					$string2=implode(' ', $array);
					break;

				default:
					break;
			}
		
			//remove repeated word
			//explode=string2array
			$_SESSIONS['files_prepared'][$f]=$string2;
			$string3=implode(' ',array_unique(explode(' ', $string2)));
			//echo $string3;
			//echo '<br><br>';

			$files_process[$f]=$string3;
/*			echo $files_process[$f];
			echo '<br><br>';
*/		}

		//save all into individual term in an array
		$string_all=implode(' ', $files_process);
		$array_terms=array_unique(explode(' ', $string_all));
		$string_terms=implode(' ', $array_terms);
		$_SESSIONS['string_all']=$string_all;
		$_SESSIONS['array_terms']=$array_terms;
		$_SESSIONS['string_terms']=$string_terms;
		asort($array_terms);
		//print_r($array_terms);
		foreach($array_terms as $x=>$x_value){
		   echo "Value=" . $x_value;
		   echo "<br>";
	   }

	//	echo "<script>location.href='tfidf.php';</script>";
		
	?>
</body>
</html>