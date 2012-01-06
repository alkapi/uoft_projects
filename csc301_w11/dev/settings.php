<?php
	/* settings.php allows a user to change his/her password */
	if (!isset($_SESSION['userinfo'])) {
		header('Location: index.php');
	}
?>
<h4>
Enter your current password and new password below.<br />
Your new password must be at least 6 characters long.
</h4>

<form action="./chpasswd.php" method="post">
	<fieldset>
		<legend>Update your password</legend>
		<br />
		<label>Current password:<br /><input type="password" name="oldpass" /></label>
		<br /><br />
		<label>New password:<br /><input type="password" name="newpass1" /></label>
		<br /><br />
		<label>Re-type new password:<br /><input type="password" name="newpass2" /></label>
		<br /><br />
		<input type="submit" value="Change Password" name="submit" />
		<input type="reset" value="Clear" />
	</fieldset>
</form>
