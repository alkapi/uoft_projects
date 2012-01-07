<?php
	/* model.php contains the database query access functions used by
     * the PT Interview Booking system. It uses the PDO Data object to 
	 * perform some SQL injection prevention, and so that the database
	 * can be utilised using any software setup */

	$db_conn = null;

	function book_time($accountId, $time, $stuNum, $courseCode){
		global $db_conn;
		if (is_null($db_conn)) { connect(); }
		
		$teacherId = get_teacherId($courseCode);
		$teacherId = $teacherId["teacherId"];
		$qstr = "INSERT INTO Interviews " .
				"(teacherId, time, blocked, year, accountId, stuNum, courseCode)" .
				"VALUES (:tid, :time, 0, 2011, :accountId, :stuNum, :courseCode)";

		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':tid', $teacherId, PDO::PARAM_INT);
		$pdostm->bindParam(':time', $time, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':accountId', $accountId, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':stuNum', $stuNum, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':courseCode', $courseCode, PDO::PARAM_STR, 40);
		
		$result = $pdostm->execute();
		return $result;
	}
	
	function cancel_time($accountId, $time){
        global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr = "DELETE FROM Interviews " .
				"WHERE accountId=:accountId AND time=:time";

		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':accountId', $accountId, PDO::PARAM_INT);
		$pdostm->bindParam(':time', $time, PDO::PARAM_STR, 40);

		$result = $pdostm->execute();
		return $result;
    }
	
    function block_time($tid, $time){
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr = "INSERT INTO Interviews " .
				"(teacherId, time, blocked, year)" .
				"VALUES (:tid, :time, 1, 2011)";

		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':tid', $tid, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':time', $time, PDO::PARAM_STR, 40);

		$result = $pdostm->execute();
		return $result;

	}

	function unblock_time($tid, $time){
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr = "delete from Interviews " .
				"where teacherId=:tid and time=:time";

		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':tid', $tid, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':time', $time, PDO::PARAM_STR, 40);

		$pdostm->execute();
		

	}
	function get_times($tid){
		global $db_conn; 
		if (is_null($db_conn)) { connect(); }
		
		$qstr = "SELECT * FROM Interviews WHERE teacherId = lower(:tid)"; 
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':tid', $tid, PDO::PARAM_INT);
		
		$result = $pdostm->execute();
		$arr = $pdostm->fetchAll();
		if (empty($arr)) { return NULL; }
		else {
			return $arr;
		}	
	}
	/* Create a persistent connection using PDO */
    function connect() {
    	global $db_conn;
    	if (is_null($db_conn)) {
    		try {
    			$db_conn = new PDO('mysql:host=localhost;dbname=testdb', 'oscar', 'uoft2011', 
				array(PDO::ATTR_PERSISTENT => true));
			} catch (PDOException $e) {
				print "Error!: " . $e->getMessage() . "<br/>";
				die();
			}
		}
	}

	
	/* Upon user login verify that the user is registered in the system.
	 * Returns the users information as an array, or NULL if credentials are
	 * invalid. */
	function verify_account($email, $passwd) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		# Query parent accounts
		$qstr = "SELECT * FROM Accounts " .
				"WHERE email = lower(:email) " .
				"AND passwd = lower(:passwd)";
		
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':email', $email, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':passwd', $passwd, PDO::PARAM_STR, 40);
        
		$valid = $pdostm->execute();
		if ($valid) {echo ".";}
        if ($valid) {
			$arr = $pdostm->fetch(PDO::FETCH_ASSOC);
			if (!empty($arr)) {
				# User is a parent
				$arr['type'] = 'parent';	
				return $arr; 
			}

		} 
		# Query teachers if not found in parents
		$qstr = 'SELECT * FROM Teachers WHERE passwd = lower(:passwd) ' 
				. 'AND email = lower(:email)';

		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':email', $email, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':passwd', $passwd, PDO::PARAM_STR, 40);

		$success = $pdostm->execute();
		if ($success) {
			$arr = $pdostm->fetch(PDO::FETCH_ASSOC);
			if (!empty($arr)) {
				# User is a teacher
				$arr['type'] = 'teacher';
				return $arr;
			}
		}
		return NULL; # Credentials not found
	}
	
	/* Update a user's password in the database */
	function update_passwd($id, $newpass, $usertype) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }
		if ($usertype == 'parent') {
			$qstr = 'UPDATE Accounts SET passwd = :newpass WHERE '
						. 'accountId = :id';
			$pdostm = $db_conn->prepare($qstr);
			$pdostm->bindParam(':id', $id, PDO::PARAM_INT);
			$pdostm->bindParam(':newpass', $newpass, PDO::PARAM_STR, 40);

		} else {	# Teacher update
			$qstr = 'UPDATE Teachers SET passwd = :newpass WHERE '
						. 'teacherId = :id';
			$pdostm = $db_conn->prepare($qstr);
			$pdostm->bindParam(':id', $id, PDO::PARAM_INT);
			$pdostm->bindParam(':newpass', $newpass, PDO::PARAM_STR, 40);
		}

		$success = $pdostm->execute();
		if ($success && $pdostm->rowCount() == 1) {
			return true;	
		} else {
			return false;
		}
	}

	/* Verify that the student number provided matches a student in database */
	function verify_student($stuNum) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr_st = "SELECT stuNum FROM Students WHERE stuNum = lower(:stuNum)";
		$pdostm_st = $db_conn->prepare($qstr_st);
		$pdostm_st->bindParam(':stuNum', $stuNum, PDO::PARAM_STR);

		$success = $pdostm_st->execute();
		if (!$success) {
			return 0;
		} else {
			$ret = $pdostm_st->rowCount();
			return $ret;
		}
	}

	/* Create a new user account for a parent */
	function create_account($email, $passwd, $stunum, $fname, $lname) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr = "INSERT INTO Accounts " .
				"(email, passwd, stuNum, fname, lname)" .
				"VALUES (:email, :passwd, :stuNum, :fname, :lname)";

		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':email', $email, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':passwd', $passwd, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':stuNum', $stunum, PDO::PARAM_STR, 40);
		$pdostm->bindParam(':fname', $fname, PDO::PARAM_STR, 40);
		$pdostm->bindParam('lname', $lname, PDO::PARAM_STR, 40);

		$result = $pdostm->execute();
		return $result;
	}

	/* Retrieve user name given accountId */
	function get_user($accountId) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr = "SELECT * FROM Accounts WHERE accountId = lower(:accountId)";
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':accountId', $accountId, PDO::PARAM_STR);

		$result = $pdostm->execute();	
		
		$arr = $pdostm->fetch(PDO::FETCH_ASSOC);
		if (empty($arr)) {
			return NULL;
		} else {
			return $arr;
		}
	}

	/* Retrieve a student's courses given a student number */
	function get_courses($stuNum) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }
		
		$qstr = "SELECT * FROM Students WHERE stuNum = lower(:stuNum)";
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':stuNum', $stuNum, PDO::PARAM_STR);
		
		$result = $pdostm->execute();
		$arr = $pdostm->fetch(PDO::FETCH_ASSOC);

		if (empty($arr)) { return NULL; }
		else {
			return $arr;
		}
	}

	function get_teacherId($courseCode) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr = "SELECT teacherId FROM Courses WHERE courseCode = lower(:courseCode)";
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
		$result = $pdostm->execute();
		$arr = $pdostm->fetch(PDO::FETCH_ASSOC);
		if (empty($arr)) { return NULL; }
		else { return $arr; }

	}

	/* Retrieve bookings for a parent */
	function find_booked($tid, $time) {
		global $db_conn; 
		if (is_null($db_conn)) { connect(); }
	
        //SELECT * FROM ((SELECT * FROM Interviews WHERE teacherId = 1) as t1, (SELECT * FROM Accounts) as t2) where t1.accountId = t2.accountId;
		$qstr = "SELECT * FROM ((SELECT * FROM Interviews WHERE time = :time and teacherId = :tid) as t1, " .
				"(SELECT * FROM Accounts) as t2) where t1.accountId = t2.accountId"; 
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':time', $time, PDO::PARAM_INT);
		$pdostm->bindParam(':tid', $tid, PDO::PARAM_INT);
		
		$result = $pdostm->execute();
		$arr = $pdostm->fetch(PDO::FETCH_ASSOC);
		if (empty($arr)) { return NULL; }
		else {
			return $arr;
		}
	}

    function cancel_block($tid, $time){
        unblock_time($tid, $time);
        block_time($tid, $time);
    }

	/* Retrieve bookings for a parent */
	function get_parent_bookings($accountId, $stuNum) {
		global $db_conn; 
		if (is_null($db_conn)) { connect(); }
	
		$qstr = "SELECT * FROM (((SELECT * FROM Interviews WHERE accountId = :accountId AND stuNum = :stuNum) AS T1)" .
				"NATURAL JOIN ((SELECT teacherId, roomNum FROM Courses) AS T2))"; 
	    $pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':accountId', $accountId, PDO::PARAM_INT);
		$pdostm->bindParam(':stuNum', $stuNum, PDO::PARAM_STR);
		$result = $pdostm->execute(); 
        $arr = $pdostm->fetchAll(); 
        if (empty($arr)) { return NULL; }
		else { return $arr; }
    }
        
    /* Retrieve bookings for a teacher */ 
    function get_teacher_bookings($teacherId) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }

		$qstr = "SELECT * FROM Interviews WHERE teacherId = lower(:teacherId) AND blocked = 0";
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
		$result = $pdostm->execute();
		
		$arr = $pdostm->fetchAll();
		if (empty($arr)) { return NULL; }
		else { return $arr; }
	}

	/* Retrieve a teacher's blocked times */
	function get_blocked_times($teacherId) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }
		
		$qstr = "SELECT * FROM Interviews WHERE teacherId = lower(:teacherId) AND blocked = 1";
		$pdostm = $db_conn->prepare($qstr);
        $pdostm->bindParam(':teacherId', $teacherId, PDO::PARAM_INT);
		$result = $pdostm->execute();
		
    	$arr = $pdostm->fetchAll();
        if (empty($arr)) { return NULL; }
		else { return $arr; }
	}

	/* Retrieve names of children of a parent user */
	function get_children($email, $stuNum) {
		global $db_conn;
		if (is_null($db_conn)) { connect(); }
		//stuNum passed into function is that of the primary account
		$qstr_pr = "(SELECT fname, lname, stuNum FROM Students WHERE stuNum = lower(:stuNum))" .
					"UNION (SELECT fname, lname, stuNum from Students WHERE stuNum in (SELECT " .
					"stuNum FROM SecondaryAcc WHERE email = lower(:email)))";

		$pdostm = $db_conn->prepare($qstr_pr);
		$pdostm->bindParam(':stuNum', $stuNum, PDO::PARAM_STR);
		$pdostm->bindParam(':email', $email, PDO::PARAM_STR, 40);
		$result = $pdostm->execute();
		$arr = $pdostm->fetchAll();
		if (empty($arr)) { return NULL; }
		return $arr;
	}

	function add_student_secondary($email, $stuNum) {
		global $db_conn;
        if (is_null($db_conn)) { connect(); }
	
		/*
		$qstr_1 = "SELECT * FROM SecondaryAcc WHERE stuNum = lower(:stuNum)";
		$pdostmt = $db_conn->prepare($qstr_1);
		$pdostmt->bindParam(':stuNum', $stuNum, PDO::PARAM_STR);
		$pdostmt->execute();
			
		if ($pdostmt->rowCount() > 0) { return; }
		*/

		$qstr = "INSERT INTO SecondaryAcc " .                     	
        		"(email, stuNum)" .
        		"VALUES (:email, :stuNum)";
                                                                
        $pdostm = $db_conn->prepare($qstr);
        $pdostm->bindParam(':email', $email, PDO::PARAM_INT);
        $pdostm->bindParam(':stuNum', $stuNum, PDO::PARAM_STR);

		$res = $pdostm->execute();
		return $res;	
	
	}

	
	function get_booked_timeslots($courseCode){
		global $db_conn; 
		if (is_null($db_conn)) { connect(); }
		
		$tid = get_teacherId($courseCode);
		$tid = $tid["teacherId"];
		$qstr = "SELECT time FROM Interviews WHERE teacherID = :tid";
		$pdostm = $db_conn->prepare($qstr);
		$pdostm->bindParam(':tid', $tid, PDO::PARAM_INT);
		$result = $pdostm->execute();
		$arr = $pdostm->fetchAll();
		if (empty($arr)) { return NULL; }
		else {
			$newarr = array();
			foreach ($arr as $value){
				$newarr[] = $value["time"];
			}			
			return $newarr;
		}
	}
?>
