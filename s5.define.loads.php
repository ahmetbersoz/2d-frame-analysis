<?php 
	if(isset($_GET["id"])) {
		$id = htmlspecialchars($_GET["id"]); 		
	}
?>


<?php 
	if (isset($_POST["add"])) {


		$Fx = htmlspecialchars($_POST["Fx"]); 
		$Fy = htmlspecialchars($_POST["Fy"]); 
		$M = htmlspecialchars($_POST["M"]); 
		$nodeNum = htmlspecialchars($_POST["nodeNum"]); 

		if(empty($Fx)) {
			$Fx=0;
		}

		if(empty($Fy)) {
			$Fy=0;
		}

		if(empty($M)) {
			$M=0;
		}


		if(!is_numeric($Fx) || !is_numeric($Fy) || !is_numeric($M)) {
			echo "You have entered missed/wrong inputs.";
			die();
		}



		$fp = fopen('input_files/loads_'.$id.'.csv', 'a');

		$row = $nodeNum.",".$Fx.",".$Fy.",".$M;

		fwrite($fp, $row."\r\n");				
		
		fclose($fp);	

		header("Location: pre_process.php?nodes&matprops&members&bcs&loads&id=".$id);
	}

	if (isset($_POST["saveAndContinue"])) {		
		header("Location: pre_process.php?nodes&matprops&members&bcs&loads&run&id=".$id);
	}

	if(isset($_POST["undo"])) {

		$filename = 'input_files/loads_'.$id.'.csv';
		$arr = file($filename);
		if ($arr === false) {
		  die('Failed to read ' . $filename);
		}

		if(!isset($arr[1])) {
			header("Location: pre_process.php?nodes&matprops&members&bcs&loads&id=".$id);
		}
		else {
			array_pop($arr);
			file_put_contents($filename, implode(PHP_EOL, $arr));
			header("Location: pre_process.php?nodes&matprops&members&bcs&loads&id=".$id);	
		}
		
			

	}
?>

<ul id="steps">
	<li class="step">Step 1: Define Nodal Points</li>
	<li class="step">Step 2: Define Material Properties</li>
	<li class="step">Step 3: Define Members</li>
	<li class="step">Step 4: Define Boundary Conditions</li>
	<li class="step current">Step 5: Define Loads</li>
	<li class="step">Step 6: Run Analysis</li>
</ul>

<hr/>

<div class="clear"></div>
<p id="info">
	Define joint loads in the structure. (For moment: ClockWise(+))
</p>
<div class="clear"></div>


<form action="" method="post">
	<div id="inputs">
		<div>
		<label>Node Number: </label>
		<select name="nodeNum">
		<?php 
			$handle = fopen("input_files/nodes_".$id.".csv", "r");
			while ($data = fgetcsv($handle, ",")) {
				echo '<option value="'.$data[0].'""> '.$data[0].' </option>';
			}					
		?>
		</select>
		<label>F<sub>x</sub>: </label><input name="Fx" type="text" class="load" value="0"> kN
		<label>F<sub>y</sub>: </label><input name="Fy" type="text" class="load" value="0"> kN
		<label>M: </label><input name="M" type="text" class="load" value="0"> kNm

		<br>
		</div>	
	</div>

	<input id="addButton" name="add" type="submit" value="Add This Load"><input name="undo" type="submit" value="Undo">
<hr/>
	<input class="nextstep" name="saveAndContinue" type="submit" value="Next Step >">
</form>


