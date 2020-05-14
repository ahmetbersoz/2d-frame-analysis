<?php 
	$handle = fopen("input_files/matprops_".$id.".csv", "r");

	echo "<b> Types of materials </b>";
	echo "<br>";

	while ($data = fgetcsv($handle, ",")) {
		
		echo '<b>'.$data[0].')</b> A= '.$data[1].' m<sup>2</sup>, I= '.$data[2].' m<sup>4</sup>, E= '.$data[3].' MPa';
		echo "<br>";		
	}
	echo "<hr/>";

	fclose($handle);
?>