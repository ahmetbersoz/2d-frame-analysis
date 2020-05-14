<script type="text/javascript">
	$(document).ready(function() {
		var InputsWrapper = $("#inputs");
		var AddButton = $("#addButton");
		var x = InputsWrapper.length;
		var FieldCount = 1;
		$(AddButton).click(function (e) {
			FieldCount++;
			$(InputsWrapper).append('<div><label>X: </label><input name="xCoor[]" type="text" class="coor"> m <label>Y: </label><input name="yCoor[]" type="text" class="coor"> m <a href="#" class="removeclass">&times;</a><br></div>');
			x++;			
		});
		$("body").on("click",".removeclass", function(e) {
			if (x>1) {
				$(this).parent('div').remove();
				x--;
			}
			return false;	
		});
	});
</script>


<?php 
	if (isset($_POST["saveAndContinue"])) {

		$allFieldsCompleted = true;

		foreach ($_POST["xCoor"] as $xCoor) {
			if(!is_numeric($xCoor)) { $allFieldsCompleted = false; }
		}
		foreach ($_POST["yCoor"] as $yCoor) {
			if(!is_numeric($yCoor)) { $allFieldsCompleted = false; }
		}
		if(!$allFieldsCompleted && !isset($_GET["id"])) {
			echo "You have entered missed/wrong inputs.";
			die();
		}
	
		else { 

			$now = time();

			$fp = fopen('input_files/nodes_'.$now.'.csv', 'a');

			$numNodes = count($_POST["xCoor"]);			

			for ($i=1; $i <=$numNodes; $i++) { 
				$row = $i.",".$_POST["xCoor"][$i-1].",".$_POST["yCoor"][$i-1];
				fwrite($fp, $row."\r\n");				
			}

			fclose($fp);			

			header("Location: pre_process.php?nodes&id=".$now);

		}
	}
?>

<ul id="steps">
	<li class="step current">Step 1: Define Nodal Points</li>
	<li class="step">Step 2: Define Material Properties</li>
	<li class="step">Step 3: Define Members</li>
	<li class="step">Step 4: Define Boundary Conditions</li>
	<li class="step">Step 5: Define Loads</li>
	<li class="step">Step 6: Run Analysis</li>
</ul>

<hr/>

<div class="clear"></div>
<p id="info">
	Define X and Y nodal point coordinates of the structure
</p>
<div class="clear"></div>




<form action="" method="post">
	<div id="inputs">
		<div>
		<label>X: </label><input name="xCoor[]" type="text" class="coor"> m
		<label>Y: </label><input name="yCoor[]" type="text" class="coor"> m
		<a href="#" class="removeclass">&times;</a>
		<br>
		</div>	
	</div>

	<input id="addButton" name="add" type="button" value="Add More">
<hr/>
	<input class="nextStep" name="saveAndContinue" type="submit" value="Next Step >">
</form>



