<?php 

	if(isset($_GET["id"])) {
		$id = htmlspecialchars($_GET["id"]); 		
	}

	if(shell_exec("analysis.exe")) {
		header("Location: /2dframeanalysis/post_process.php?suppreacs&disps&id=".$id);
	}
	else {
		echo "Something went wrong.";
	}

?>