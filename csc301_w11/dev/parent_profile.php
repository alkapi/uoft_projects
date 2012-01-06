<!DOCTYPE html>
<!-- parent_profile.php is the profile view for the parent that allows
     a parent to view his/her child or children's schedule, book/unbook 
     an appointment or change his/her password -->
<html>
	<head>
		<script src="jquery-1.7.min.js" type="text/javascript"></script>
		<script src="jquery.tools.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="parent_profile.js"></script>
		<!--[if IE]>
			<link rel="stylesheet" type="text/css" href="ie.css" />
		<![endif]-->
		<![if !IE]>
			<link rel="stylesheet" type="text/css" href="style.css" />
		<![endif]>
		<title>Parent Profile</title>
	</head>
	<body>
		<ul id="menu">
			<li id="li_book" class="active"><a>Book Appointment</a></li>
			<li id="li_schedule"><a>View Schedule</a></li>
			<li id="li_addstudent" ><a>Add a student</a></li>
			<li id="setting_parent"><a>Settings</a></li>
			<li id="logout_parent" class="bye"><a href="logout.php">Logout</a></li>
		</ul>
		<div id="container">
            <h1 class="profile_header">Welcome, <?php echo $_SESSION['user_fname'] . " " . 
                $_SESSION['user_lname'] . "!"; ?></h1>
            <div id="tablike">
                <div id="students">
                    <?php include_once("./student.php"); ?>
                </div>
                <div id="courses">
                    
                </div>
                <div id="booktable">
                    <h2>Please select a student</h2>
                </div>
            </div>
        </div>
        <div class="simple_overlay" id="detail">
        	<h2 id="test">test</h2>
        	<button id="confirm">CONFIRM</h1>
        	<button id="cancel" class="close">CANCEL</h1>
        </div>
	</body>
</html>
