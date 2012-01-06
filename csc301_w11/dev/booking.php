<table id="booktable">
	<?php 
		include_once("model.php");
		$mysqlhours = array('2', '3', '4', '6', '7', '8');
		$bookedslots = get_booked_timeslots($_POST['courseCode']);
		foreach ($mysqlhours as $hour) {
			echo "<tr>";
			for ($i = 0; $i < 60; $i+=5) {
				echo "<td ";
				$time = $hour.":".(($i < 10)?'0'.$i:$i);
				if (in_array("0".$time.":00", $bookedslots)) {
					echo  "class=\"timeslot blocked\"";
				}else {
					echo  "class=\"timeslot open\" rel=\"#detail\"";
				}
				echo ">$time</td>";
			}
			echo "</tr>";
		}
	?>
</table>
<table>
	<tr>
		<td id="greentd"></td>
		<td> : open slot </td>
		<td id="spacetd"></td>
		<td id="redtd"></td>
		<td> : booked slot </td>
	</tr>
</table>


