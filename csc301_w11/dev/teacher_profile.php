<?php

$times = get_times($_SESSION["teacherId"]);
$blocked = array();
$booked = array();
foreach ($times as $time){
	if ($time["blocked"] == 1){
		$blocked[] = substr($time["time"], 0, strrpos($time["time"], ":"));
	} else {
		$booked[] = substr($time["time"], 0, strrpos($time["time"], ":"));
	}
}

?>
<!DOCTYPE html>
<!-- teacher_profile.php is the profile view for the teacher allowing him/her
     to view his/her schedule and block/unblock timeslots -->
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css">
		<script type="text/javascript" src="jquery-1.7.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="teacher_menu.js"></script>
		<script type="text/javascript" src="teacher.js"></script>
		<title>Teacher Profile</title>
		<link rel="stylesheet" href="style.css" type="text/css" />
	</head>
	<body>
		<ul id="menu">
			<li id="li_time" class="active"><a>Time Slot</a></td>
			<li id="li_settings"><a>Settings</a></td>
			<li id="logout"><a href="./logout.php">Logout</a></td>
		</ul>
		<div id="container">
			
			
			<h1>Welcome, <?php echo $_SESSION['user_fname'] . '!' ?></h1>
			
			<div id="teachertime" class="menuchoice">
			<table>
				<?php 
					$mysqlhours = array('2', '3', '4', '6', '7', '8');
					foreach ($mysqlhours as $hour) {
						echo "<tr>";
						for ($i = 0; $i < 60; $i+=5) {
							echo "<td";
							if ($i<10){
							$timefor = "0".$hour.":0".$i;
							} else {
							$timefor = "0".$hour.":".$i;
							}
							if (in_array($timefor, $blocked)){
								echo" class='timeslot blocked'";
							} else if (in_array($timefor, $booked)){
								echo" class='timeslot booked'";
							} else {
								echo" class='timeslot open'";
							}

							echo ">$hour:";
							if ($i < 10) {
								echo "0";
							}
							echo "$i</td>";
						}
						echo "</tr>";
					}
				?>
			</table>
				<h3>Legend:</h3>
				<div class="box-cont"><div class="box red"></div> : Blocked time slot</div>
				<div class="box-cont"><div class="box green"></div> : Open time slot</div>
<div class="box-cont">				<div class="box brown"></div> : Booked time slot</div>
			</div>
			<div id="teacher_settings" class="hide">
				<?php include_once('settings.php'); ?>
			</div>
		</div>

	</body>
</html>
