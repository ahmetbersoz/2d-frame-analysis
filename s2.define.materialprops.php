<?php 
	if(isset($_GET["id"])) {
		$id = htmlspecialchars($_GET["id"]); 
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		var InputsWrapper = $("#inputs");
		var AddButton = $("#addButton");
		var x = InputsWrapper.length;
		var FieldCount = 1;
		$(AddButton).click(function (e) {
			FieldCount++;
			$(InputsWrapper).append('<div><label>A: </label><input name="A[]" type="text" class="mat"> m <sup>2 </sup><label> I: </label><input name="I[]" type="text" class="mat"> m <sup>4 </sup><label> E: </label><input name="E[]" type="text" class="mat"> MPa</sup><a href="#" class="removeclass">&times;</a><br></div>');
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

		foreach ($_POST["A"] as $A) {
			if(!is_numeric($A)) { $allFieldsCompleted = false; }
		}
		foreach ($_POST["I"] as $I) {
			if(!is_numeric($I)) { $allFieldsCompleted = false; }
		}
		foreach ($_POST["E"] as $E) {
			if(!is_numeric($E)) { $allFieldsCompleted = false; }
		}
		if(!$allFieldsCompleted) {
			echo "You have entered missed/wrong inputs.";
			die();
		}
	
		else { 

			$fp = fopen('input_files/matprops_'.$id.'.csv', 'a');

			$numNodes = count($_POST["A"]);			

			for ($i=1; $i <=$numNodes; $i++) { 
				$row = $i.",".$_POST["A"][$i-1].",".$_POST["I"][$i-1].",".$_POST["E"][$i-1];
				fwrite($fp, $row."\r\n");				
			}

			fclose($fp);			

			header("Location: pre_process.php?nodes&matprops&id=".$id);

		}
	}

	if(isset($_POST["previousStep"]))  {
		header("Location: pre_process.php?id=".$id);		
	}
?>

<ul id="steps">
	<li class="step">Step 1: Define Nodal Points</li>
	<li class="step current">Step 2: Define Material Properties</li>
	<li class="step">Step 3: Define Members</li>
	<li class="step">Step 4: Define Boundary Conditions</li>
	<li class="step">Step 5: Define Loads</li>
	<li class="step">Step 6: Run Analysis</li>
</ul>

<hr/>

<div class="clear"></div>
<p id="info">
	A: Cross-sectional area, 
	I: Moment of Inertia,
	E: Modulus of Elasticity
</p>
<div class="clear"></div>


<form action="" method="post">
	<div id="inputs">
		
		<br>
		<div>
		<label>A: </label><input name="A[]" type="text" class="mat"> m <sup>2</sup>
		<label>I: </label><input name="I[]" type="text" class="mat"> m <sup>4</sup>
		<label>E: </label><input name="E[]" type="text" class="mat"> MPa
		<a href="#" class="removeclass">&times;</a>
		<br>
		</div>	
	</div>

	<input id="addButton" name="add" type="button" value="Add More">
	
	<hr/>
	<input class="nextstep" name="saveAndContinue" type="submit" value="Next Step >">
</form>

