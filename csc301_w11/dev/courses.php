<!-- courses.php is part of the parent_profile that is used to retrieve 
 * the courses a student takes and display them in lists. It gets the student
 * number from information stored in session and calls database communication 
 * function in model.php to get the values needed -->
<?php	
	session_start();
    include_once('model.php');
	$stuNum = $_POST['stuNum'];
	$courses = get_courses($stuNum);
	echo "<ul id=\"courses\">";
    $parentbooked = get_parent_bookings($_SESSION['userinfo']['accountId'], $stuNum);
    $alreadybooked = array();
    if (is_null($parentbooked)){
        $alreadybooked[] = "not a course";
    } else {
        foreach ($parentbooked as $value){
            $alreadybooked[] = $value['courseCode'];
        }
    }
	foreach ($courses as $key => $value) {
		if (substr($key, 0, 6) == 'course') {
            ?><li <?php
            if (is_null($value)) {} else {
                if (in_array($value, $alreadybooked)) {
                    echo "class=\"already\" onclick=\"\">$value</li>";
                }else{
                    echo "class=\"notyet\" onclick=\"\">$value</li>";
                }
            }
        }
	}
	echo "</ul>"
?>
