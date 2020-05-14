<?php 
	if(isset($_GET["id"])) {
		$id = htmlspecialchars($_GET["id"]); 		
	}
?>


<?php 
	if (isset($_POST["runAnalysis"])) {

		$nodes0 	= 'input_files/nodes_'.$id.'.csv';
		$nodes 		= 'analysis/inputs/XY.csv';

		$matprops0 	= 'input_files/matprops_'.$id.'.csv';
		$matprops 	= 'analysis/inputs/M.csv';

		$members0 	= 'input_files/members_'.$id.'.csv';
		$members 	= 'analysis/inputs/C.csv';

		$bcs0	 	= 'input_files/bcs_'.$id.'.csv';
		$bcs 	 	= 'analysis/inputs/S.csv';

		$loads0	 	= 'input_files/loads_'.$id.'.csv';
		$loads 	 	= 'analysis/inputs/L.csv';

	if (!copy($nodes0, $nodes) ||
		!copy($matprops0, $matprops) ||
		!copy($members0, $members) ||
		!copy($bcs0, $bcs) ||
		!copy($loads0, $loads)
		) 
		{
			echo "file copy business went wrong.";
		}

		header("Location: /2dframeanalysis/analysis/startanalysis.php?id=".$id);
	}
?>

<ul id="steps">
	<li class="step">Step 1: Define Nodal Points</li>
	<li class="step">Step 2: Define Material Properties</li>
	<li class="step">Step 3: Define Members</li>
	<li class="step">Step 4: Define Boundary Conditions</li>
	<li class="step">Step 5: Define Loads</li>
	<li class="step current">Step 6: Run Analysis</li>
</ul>

<hr/>

<div class="clear"></div>
<p id="info">
	When you click the "Run Analysis" button, the program will give support reactions and nodal displacements of the structure. It may take few seconds.
</p>
<div class="clear"></div>


<form action="" method="post">
	<input name="runAnalysis" type="submit" value="Run Analysis">
</form>

