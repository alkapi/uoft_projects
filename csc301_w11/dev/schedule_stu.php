<?php
	include_once('./model.php');
	
    $par_email = $_SESSION['userinfo']['email'];
	$prim_stunum = $_SESSION['userinfo']['stuNum'];
	$child_arr = get_children($par_email, $prim_stunum);
	$found = false;
	if (!empty($child_arr)) { 
		$found = true;
	}
	//print_r($child_arr);
?>
<div id="students">
<ul id="schedulelist">
<?php
	if ($found) {
		for ($i = 0; $i < sizeof($child_arr); $i++) {
?>
		<li id=<?php echo "stu". "{$i}";?> class="student" name="<?php echo $child_arr[$i]['stuNum']; ?>">
		<a><?php echo $child_arr[$i]['fname'] . " " . $child_arr[$i]['lname'];?></a></li>
<?php } ?>
<?php } ?>
</ul>
</div>
<div id="scheduletable">
    <h2>Please select a student</h2>
</div>
