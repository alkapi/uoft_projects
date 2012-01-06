
    <?php
        include_once('model.php');
        session_start();
        $bookings = get_parent_bookings($_SESSION['userinfo']['accountId'], $_POST['stuNum']);
        if (is_null($bookings)){
            echo "You do not have any interviews.";
        } else {
        ?>
        <table id="scheduleTable">
            <thead><tr><td>Course</td><td>Time</td><td>Room</td></tr></thead>
            <tbody>
        <?php
            foreach ($bookings as $value){
                ?><tr><td><?php echo $value['courseCode'];?></td>
                      <td><?php echo $value['time'];?></td>
                      <td><?php echo $value['roomNum'];?></td>
                      <td class= "parentCancel" name="<?php echo $value['time']; ?>">Cancel?</td></tr><?php
            }
        ?>
            </tbody>
        </table>
        <?php
        } ?>
