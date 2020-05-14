<?php 

	session_start();
	
	include("functions/drawing_functions.php");

	if(isset($_GET["id"])) {
		$id 		= htmlspecialchars($_GET["id"]);
		$max_width 	= maxWidth($id);
		$max_height = maxHeight($id);
		$im_width	= $_SESSION["leftpanel_width"];
		$im_height	= $_SESSION["screen_height"];
		$im 		= imagecreatetruecolor($im_width,$im_height);


	}
	
	//image properties
	$dot_w		= 7;
	$dot_h		= 7;
	$font_path	= "css/arial.ttf";
	$font_size	= 10;
	$angle 		= 0;

	//colors
	imagecolorallocate($im, 255, 255, 255); //background color
	$white 	= imagecolorallocate($im, 255, 255, 255);	
	imagefill($im, 0, 0, $white);
	$blue 	= imagecolorallocate($im, 0, 0, 255);
	$black 	= imagecolorallocate($im, 0, 0, 0);
	$red 	= imagecolorallocate($im, 255, 0, 0);
	$green 	= imagecolorallocate($im, 0, 150, 255);

	
	if (isset($_GET["nodes"])) {		

		$handle = fopen("input_files/nodes_".$id.".csv", "r");

		while ($data = fgetcsv($handle, ",")) {
			/*
			echo coorTransX($id,$data[1]).", ".coorTransY($id,$data[2]);
			echo "<br>";
			*/
			imagefilledellipse($im, coorTransX($id,$data[1]), coorTransY($id,$data[2]), $dot_w, $dot_h, $black);			
			imagettftext($im, $font_size, $angle, nodeNum(coorTransX($id,$data[1])), nodeNum(coorTransY($id,$data[2])), $red, $font_path, $data[0]);
		}

		fclose($handle);

	}	

	if (isset($_GET["members"])) {

		$handle = fopen("input_files/members_".$id.".csv", "r");

		while ($member = fgetcsv($handle, ",")) {

			$handle2 = fopen("input_files/nodes_".$id.".csv", "r");
			while ($node = fgetcsv($handle2, ",")) {
				if ($member[1]==$node[0]) {
					$x1 = $node[1];
					$y1 = $node[2];
				}
				if ($member[2]==$node[0]) {
					$x2 = $node[1];
					$y2 = $node[2];					
				}				
			}

			if(isset($x1) && isset($x2) && isset($y1) && isset($y2)) {
					imagelinethick($im, coorTransX($id,$x1), coorTransY($id,$y1), coorTransX($id,$x2), coorTransY($id,$y2), $blue, $thick=4);				
			}

		}

		fclose($handle);
		fclose($handle2);
	}
	
	if (isset($_GET["bcs"])) {

		if(file_exists("input_files/bcs_".$id.".csv")) {

			$handle = fopen("input_files/bcs_".$id.".csv", "r");

			if(!$handle) {
				break;
			}

			while ($bc = fgetcsv($handle, ",")) {

				$handle2 = fopen("input_files/nodes_".$id.".csv", "r");
				while ($node = fgetcsv($handle2, ",")) {
					if ($bc[0]==$node[0]) {
						$x = $node[1];
						$y = $node[2];
					}		
				}
				fclose($handle2);

				$trans_x 	= $bc[1];
				$trans_y 	= $bc[2];
				$rotation 	= $bc[3];

				if(isset($x) && isset($y)) {

					if ($trans_x==1 && $trans_y==1 && $rotation==1) {
						addFixedSupport($x,$y,$id,$im);
					}
					elseif($trans_x==1 && $trans_y==1 && $rotation==0) {
						addPinSupport($x,$y,$id,$im);	
					}
					elseif($trans_x==1 && $trans_y==0 && $rotation==0) {
						addRollerSupport($x,$y,$id,$im,"H");	
					}
					elseif($trans_x==0 && $trans_y==1 && $rotation==0) {
						addRollerSupport($x,$y,$id,$im,"V");	
					}
						
				}

			}

			fclose($handle);
			

		}

	}


	if (isset($_GET["loads"])) {

		if(file_exists("input_files/loads_".$id.".csv")) {

			$handle = fopen("input_files/loads_".$id.".csv", "r");

			while ($load = fgetcsv($handle, ",")) { 

				$handle2 = fopen("input_files/nodes_".$id.".csv", "r");
				while ($node = fgetcsv($handle2, ",")) {
					if ($load[0]==$node[0]) {
						$x = $node[1];
						$y = $node[2];
					}		
				}

				$Fx	= $load[1];
				$Fy	= $load[2];
				$M	= $load[3];

				if(!empty($Fx) && $Fx!=0) {
					addFx($x,$y,$Fx,$im,$id,$black,10,$font_path);
				}

				if(!empty($Fy) && $Fy!=0) {
					addFy($x,$y,$Fy,$im,$id,$black,10,$font_path);
				}

				if(!empty($M) && $M!=0) {
					addM($x,$y,$M,$im,$id,$black,10,$font_path);
				}

			}
		}

	}

	if(isset($_GET["suppreacs"])) {

		if(file_exists("analysis/outputs/rs.csv")) {

			$handle = fopen("analysis/outputs/rs.csv", "r");

			while ($load = fgetcsv($handle, ",")) { 

				$handle2 = fopen("input_files/nodes_".$id.".csv", "r");
				while ($node = fgetcsv($handle2, ",")) {
					if ($load[0]==$node[0]) {
						$x = $node[1];
						$y = $node[2];
					}		
				}

				$Fx	= $load[1];
				$Fy	= $load[2];
				$M	= $load[3];

				if(!empty($Fx) && $Fx!=0) {
					addFx($x,$y,$Fx,$im,$id,$green,10,$font_path);
				}

				if(!empty($Fy) && $Fy!=0) {
					addFy($x,$y,$Fy,$im,$id,$green,10,$font_path);
				}

				if(!empty($M) && $M!=0) {
					addM($x,$y,$M,$im,$id,$green,10,$font_path);
				}

			}
		}

	}






	
	header('Content-Type: image/png');
	imagepng($im);
	imagedestroy($im);
	

	

	


?>