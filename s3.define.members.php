<?php 
	$memberid = 1;
	if(isset($_GET["id"])) {
		$id = htmlspecialchars($_GET["id"]); 		
	}
	if(isset($_GET["mid"])) {
		$memberid = htmlspecialchars($_GET["mid"]); 
	}
?>


<?php 
	if (isset($_POST["add"])) {

		$startNode = htmlspecialchars($_POST["startNode"]); 
		$endNode = htmlspecialchars($_POST["endNode"]); 
		$matProp = htmlspecialchars($_POST["matProp"]); 

		if(!is_numeric($startNode) || !is_numeric($endNode) || !is_numeric($matProp)) {
			echo "You have entered missed/wrong inputs.";
			die();
		}
	
		else { 

			$fp = fopen('input_files/members_'.$id.'.csv', 'a');

			$row = $memberid.",".$_POST["startNode"].",".$_POST["endNode"].",".$_POST["matProp"];

			fwrite($fp, $row."\r\n");				
			
			fclose($fp);		

			$memberid= $memberid + 1;	

			header("Location: pre_process.php?nodes&matprops&members&id=".$id."&mid=".$memberid);

		}
	}

	if (isset($_POST["saveAndContinue"])) {		
		header("Location: pre_process.php?nodes&matprops&members&bcs&id=".$id);
	}

	if(isset($_POST["undo"])) {

		$filename = 'input_files/members_'.$id.'.csv';
		$arr = file($filename);
		if ($arr === false) {
		  die('Failed to read ' . $filename);
		}
		array_pop($arr);
		file_put_contents($filename, implode(PHP_EOL, $arr));

		$memberid0 = htmlspecialchars($_GET["mid"]); 	

		if($memberid0==2) {
			header("Location: pre_process.php?nodes&matprops&id=".$id);
		}
		else {
			header("Location: pre_process.php?nodes&matprops&members&id=".$id."&mid=".($memberid-1));	
		}		

	}
?>


<ul id="steps">
	<li class="step">Step 1: Define Nodal Points</li>
	<li class="step">Step 2: Define Material Properties</li>
	<li class="step current">Step 3: Define Members</li>
	<li class="step">Step 4: Define Boundary Conditions</li>
	<li class="step">Step 5: Define Loads</li>
	<li class="step">Step 6: Run Analysis</li>
</ul>

<hr/>

<?php 
	if (isset($_GET["matprops"])) {
		include("get.matprops.php");
	}
?>

<div class="clear"></div>
<p id="info">
	Define connectivity and material property of the members
</p>
<div class="clear"></div>


<form action="" method="post">
	<div id="inputs">
		<div>
		<label>Start Node: </label>
		<select name="startNode">
		<?php 
			$handle = fopen("input_files/nodes_".$id.".csv", "r");
			while ($data = fgetcsv($handle, ",")) {
				echo '<option value="'.$data[0].'""> '.$data[0].' </option>';
			}	
					
		?>
		</select>
		<label>End Node: </label>
		<select name="endNode">
		<?php 	
			$handle = fopen("input_files/nodes_".$id.".csv", "r");
			while ($data = fgetcsv($handle, ",")) {
				echo '<option value="'.$data[0].'""> '.$data[0].' </option>';
			}			
		?>
		</select>
		<label>Material Property: </label>
		<select name="matProp">
		<?php 
			$handle = fopen("input_files/matprops_".$id.".csv", "r");
			while ($data = fgetcsv($handle, ",")) {
				echo '<option value="'.$data[0].'""> '.$data[0].' </option>';
			}
			fclose($handle);
		?>
		</select>
		<br>
		</div>	
	</div>

	<input id="addButton" name="add" type="submit" value="Add This Member"><input name="undo" type="submit" value="Undo">
<hr/>
	<input class="nextstep" name="saveAndContinue" type="submit" value="Next Step >">
</form>

