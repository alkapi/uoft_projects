<?php
	include_once('model.php');

	/* Unit tests for functions used by PT Interview system */

	/* Test of model function that verifies 
     * account holder's identity */
	function test_verify_account() {
		$acc_noexist = verify_account('melinda@test.ca', 'flower');
		if ($acc_noexist == false) {
			echo 'Passed test verifying user with no account not in system<br />';
		} else {
			echo 'Failed test verifying user with no account not in system<br />';
		}
		$acc_exist = verify_account('lisa@test.ca', 'flower');
		if ($acc_exist == '1') {
			echo('Passed test verifying user with account in system<br />');
		}
		else { 
			echo('Failed test verifying user with account in system<br />');
		}	
	} 

	/* Test of function in model.php that verifies account creation */
	function test_create_account() {
		$acc_create = create_account('ada@lovelace.ca', 'cool');
		$acc_exist = verify_account('ada@lovelace.ca', 'cool');
		if ($acc_exist == false) {
			echo('Failed test to create an account<br />');
		} else { echo('Passed test to create an account<br />'); }

	}

	function test_update_passwd() {
		$new_pass = update_passwd('flower', 'flowers');
		if ($new_pass) {
			echo "Passed test to update account<br/>";
		} else {
			echo "Failed test to update account<br/>";
		}
	}

	test_verify_account();
	test_create_account();
	test_update_passwd();


?>
