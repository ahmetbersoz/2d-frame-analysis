<?php 
	if(isset($_GET["id"])) {
		$id = htmlspecialchars($_GET["id"]); 		
	}
?>


<?php 
	if (isset($_POST["add"])) {


		$nodeNum 	= htmlspecialchars($_POST["nodeNum"]); 
		$trans_x	= htmlspecialchars($_POST["trans_x"]); 
		$trans_y 	= htmlspecialchars($_POST["trans_y"]); 
		$rotation 	= htmlspecialchars($_POST["rotation"]); 

		$fp = fopen('input_files/bcs_'.$id.'.csv', 'a');

		if($trans_x!=1) {
			$trans_x = 0;
		}
		if($trans_y!=1) {
			$trans_y = 0;
		}
		if($rotation!=1) {
			$rotation = 0;
		}

		$row = $nodeNum.",".$trans_x.",".$trans_y.",".$rotation;

		fwrite($fp, $row."\r\n");				
		
		fclose($fp);	

		header("Location: pre_process.php?nodes&matprops&members&bcs&id=".$id);

	}

	if (isset($_POST["saveAndContinue"])) {		
		header("Location: pre_process.php?nodes&matprops&members&bcs&loads&id=".$id);
	}

	if(isset($_POST["undo"])) {

		$filename = 'input_files/bcs_'.$id.'.csv';
		$arr = file($filename);
		if ($arr === false) {
		  die('Failed to read ' . $filename);
		}

		if(!isset($arr[1])) {
			header("Location: pre_process.php?nodes&matprops&members&bcs&id=".$id);
		}
		
		array_pop($arr);

		file_put_contents($filename, implode(PHP_EOL, $arr));

		//header("Location: pre_process.php?nodes&matprops&members&bcs&id=".$id);		

	}

?>

<ul id="steps">
	<li class="step">Step 1: Define Nodal Points</li>
	<li class="step">Step 2: Define Material Properties</li>
	<li class="step">Step 3: Define Members</li>
	<li class="step current">Step 4: Define Boundary Conditions</li>
	<li class="step">Step 5: Define Loads</li>
	<li class="step">Step 6: Run Analysis</li>
</ul>

<hr/>

<div class="clear"></div>
<p id="info">
	Define supports in the structure. Check for restraint state.</br></br>
	Trans-X : Translation in global x direction </br>
	Trans-Y : Translation in global x direction </br>
	Rotation: Rotation about global z direction </br>
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
		<label>Trans-X</label><input type="checkbox" name="trans_x" value="1">
		<label>Trans-Y</label> <input type="checkbox" name="trans_y" value="1">
		<label>Rotation</label><input type="checkbox" name="rotation" value="1">

		<br>
		</div>	
	</div>

	<input id="addButton" name="add" type="submit" value="Add This BC"><input name="undo" type="submit" value="Undo">
<hr/>
	<input class="nextstep" name="saveAndContinue" type="submit" value="Next Step >">
</form>

