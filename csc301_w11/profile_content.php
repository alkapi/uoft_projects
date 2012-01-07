<?php
/* Incorporate teacher's dynamic content here */
/* Dynamic output of parent's schedule and appointment 
 * booking */
 	session_start();
	$want = $_POST['content'];
	if (strpos($want, 'View Schedule')>0){
		include('./schedule_stu.php');
	} else if (strpos($want, 'Book Appointment')>0) {
?>
    <div id="students">
<?php
        include('./student.php');
?>
    </div>
    <div id="courses">
        
    </div>
    <div id="booktable">
        <h2>Please select a student</h2>   
    </div>
<?php
	} else if (strpos($want, 'Settings')>0) {
       	include('./settings.php');
	} else if (strpos($want, 'Add a student')>0) {
        include_once('./add_sec_student.php');
    } else {
?>
		<p>Unknown selection!</p>
<?php
	}
?>
