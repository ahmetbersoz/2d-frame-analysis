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
	<title> 2D Frame Analysis Pre-Processor</title>
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
				if (isset($_GET["loads"])) {
					echo '<img class="render" src="drawing.php?nodes&members&bcs&loads&id='.$id.'"/>';
				}
				elseif (isset($_GET["bcs"])) {
					echo '<img class="render" src="drawing.php?nodes&members&bcs&id='.$id.'"/>';
				}				
				elseif (isset($_GET["members"])) {
					echo '<img class="render" src="drawing.php?nodes&members&id='.$id.'"/>';
				}
				elseif (isset($_GET["nodes"])) {
					echo '<img class="render" src="drawing.php?nodes&id='.$id.'"/>';
				}
				elseif (isset($_GET["id"])) {
					echo '<img class="render" src="drawing.php?nodes&id='.$id.'"/>';
				}
				else {	
			?>

			<div id="hellomessagecontainer">
				<h2 id="hellomessage"> 
					Online
				</h2>
				<h2 id="hellomessage"> 
					2D Frame Analysis
				</h2>
				<h2 id="hellomessage"> 
					Software
				</h2>				
			</div>

			<?php
				}
			?>
		</div>

		<div id="right-panel">


			<?php 

				if (isset($_GET["run"])) {
					include("s6.run.analysis.php");
				}
				elseif (isset($_GET["loads"])) {
					include("s5.define.loads.php");
				}
				elseif (isset($_GET["bcs"])) {
					include("s4.define.bcs.php");
				}
				elseif (isset($_GET["matprops"])) {
					include("s3.define.members.php");
				}
				elseif (isset($_GET["nodes"])) {					
					include("s2.define.materialprops.php");
				}
				else {
					include("s1.define.nodalpoints.php");	
				}					
							
			?>

		</div>
	</div>
	<div class="clear"></div>
	<footer>
		<a href="about.php">About</a>
	</footer>
</body>
</html>