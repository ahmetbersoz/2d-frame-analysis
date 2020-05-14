<?php

/* optimizasyon çalışmaları :( 
session_start();
if(isset($_SESSION['screen_width']) AND isset($_SESSION['screen_height'])){
    $_SESSION['leftpanel_width'] = 600;
    $_SESSION['rightpanel_width'] = $_SESSION['screen_width']*0.40;
} else if(isset($_REQUEST['width']) AND isset($_REQUEST['height'])) {
    $_SESSION['screen_width'] = $_REQUEST['width'];
    $_SESSION['screen_height'] = 400;
    header('Location: ' . $_SERVER['PHP_SELF']);
} else {
    echo '<script type="text/javascript">
	var varWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	var varHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    window.location = "' . $_SERVER['PHP_SELF'] . '?width="+varWidth+"&height="+varHeight;</script>';
}
*/

	session_start();

    $_SESSION['leftpanel_width'] = 600;
    $_SESSION['rightpanel_width'] = 400;
    $_SESSION['screen_width'] = 1000;
    $_SESSION['screen_height'] = 600;

?>

<html>
<head>
	<title> 2D Frame Analysis Post-Processor</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
</head>
<body>
	<div id="container">
		<div id="left-panel">
			&nbsp;
			<?php 
				if(isset($_GET["id"])) {
					$id = htmlspecialchars($_GET["id"]);				
				}
			?>

			<?php 
				if(isset($_GET["suppreacs"])) {
					echo '<img class="render" src="drawing.php?nodes&members&suppreacs&loads&id='.$id.'"/>';
				}
			?>

		</div>

		<div id="right-panel">
			
			<div class="clear"></div>
		<p id="info">
			Analysis had been completed. Support reactions are as shown in the figure.
		</p>
		<div class="clear"></div>

		<br><br>
		<h3> Displacements </h3>


			<?php 

				if(isset($_GET["disps"])) {
						
					if(file_exists("analysis/outputs/dsorted.csv")) {

						$fp = fopen('analysis/outputs/dsorted.csv', 'a');

						$handle = fopen("analysis/outputs/dsorted.csv", "r");

						$i=1;

						echo '<table border="1" cellpadding="5" cellspacing="20">';
						echo '<tr>
								<td><b>Node #</b></td>
								<td><b>X (mm)</b></td>
								<td><b>Y (mm)</b></td>
								<td><b>Rotation (rad)</b></td>
							  </tr>
							  ';

						while ($disp = fgetcsv($handle, ",")) {	

							echo '<tr>';
							echo '<td><b>'.$i.'</b></td>';
							echo '<td>'.$disp[0].'</td>';
							echo '<td>'.$disp[1].'</td>';
							echo '<td>'.$disp[2].'</td>';
							echo '</tr>';

							$i++;

						}	

						echo "</table>";	

						fclose($fp);

					}
				}



				/* For undo button
				$filename = 'input_files/nodes_1390223988.csv';
				$arr = file($filename);
				if ($arr === false) {
				  die('Failed to read ' . $filename);
				}
				array_pop($arr);
				file_put_contents($filename, implode(PHP_EOL, $arr));
				*/				
							
			?>

			<form action="pre_process.php">
				<input type="submit" value="New Analysis" />
			</form>

		</div>
	</div>
	<div class="clear"></div>
	<footer>
		<a href="about.php">About</a>
	</footer>
</body>
</html>