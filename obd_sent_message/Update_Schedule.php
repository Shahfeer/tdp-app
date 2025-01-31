<?php
/*** process asterisk cdr file (Master.csv) insert usage
* values into a mysql database which is created for use
* with the Asterisk_addons cdr_addon_mysql.so
* The script will only insert NEW records so it is safe
* to run on the same log over-and-over.
*
* Author: Syed Abdul Kadar (yeejai.tech@yeejai.com)
* Date: Version 1 Released Dec 4, 2021
*
* Here is what the script does:
*
* Parse each row from the text log and insert it into the database after testing for a
* matching "calldate, src, duration" record in the database. Note that not all fields are
* tested.
*
* If you have a large existing database it is recommended that you add an index to the calldate
* field which will greatly speed up this import.
*
*/

$db_host = 'localhost';
// $db_name = 'asteriskcdrdb'; 
// $db_login = 'asteriskcdr';
// $db_pass = 'Asterisk@123';

$db_name = 'obd_call'; 
$db_login = 'admin';
$db_pass = 'Password@123';

// connect to db
$mysqli = new mysqli($db_host, $db_login, $db_pass, $db_name);
if ($mysqli->connect_errno) {
  die("Could not connect : " . $mysqli->connect_error());
}
$mysqli->query("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

/* Check Every min and Schedule the Calls - Start */
// print(shell_exec("whoami"));
$sql_calls = "SELECT * FROM calls where DATE_FORMAT(schedule_at, 'Y-m-d H:i:s') >= DATE_FORMAT(NOW(), 'Y-m-d H:i:s') and call_status in ('N', 'P') limit 32";
print($sql_calls);
$qur_calls = $mysqli->query($sql_calls);
if ($qur_calls->num_rows > 0) {
	while($row_calls = $qur_calls->fetch_assoc()) {
		$id                             = $row_calls['id'];

		$update_calls = $mysqli->query("UPDATE calls SET call_status = 'Y'
                                                WHERE call_status in ('N', 'P') and id = '".$id."' and
							DATE_FORMAT(schedule_at, 'Y-m-d H:i:s') >= DATE_FORMAT(NOW(), 'Y-m-d H:i:s')");
		print("UPDATE calls SET call_status = 'Y'
                                                WHERE call_status in ('N', 'P') and id = '".$id."' and
                                                        DATE_FORMAT(schedule_at, 'Y-m-d H:i:s') >= DATE_FORMAT(NOW(), 'Y-m-d H:i:s')");
		// $id	                        = $row_calls['id'];
                $context                        = $row_calls['context'];
		$time_interval                  = $row_calls['time_interval'];
		$file_count                     = $row_calls['file_count'];
		$input_file_path                = $row_calls['input_file_path'];
		$output_file_path               = $row_calls['output_file_path'];
	}
} 
/* Check Every min and Schedule the Calls - End */

$mysqli->close();

fclose($handle);
print("Call files moved\n");
?>
