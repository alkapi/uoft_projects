<?php
	if (!isset($_SESSION['userinfo'])) { 
		header('Location: index.php');
	}
	/*
	if (isset($SESSION['err'])) {
		$mssg = 'Please enter a correct student number.'; 
	} else {
		$mssg = 'Enter the student number of the child you wish to add to your account below.';
	}
	*/
?>

<h4>
Enter the student number of the child you wish to add to your account below.<br />
</h4>

<script>
$("#stunum").click(function(ind, el) {
	$.ajax({
		url: "sec_stu.php",
		type: "POST",
		cache: false,
		data: {sec_stu: $("#stunumval").attr("value")},
		success: function(html) {
			if (html=='success') {
       			alert("Student added successfully!");
			} else { alert("Please add a new existing student!"); }
		},
        error: function(err){ alert(err); }
	});
    return false;
});
</script>
<form action="./sec_stu.php" method="post">
	<fieldset>
		<legend>Add a child to your account</legend>
		<br />
		<label>Student Number:<br /><input type="text" id="stunumval" name="sec_stu" /></label>
		<br /><br />	
		<input type="submit" value="Add student" id="stunum" name="submit_stu" />
		<input type="reset" value="Clear" />
	</fieldset>
</form>
