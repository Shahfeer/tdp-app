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

if($argc == 2) {
	$logfile = $argv[1];
} else {
	print("Usage ".$argv[0]." <filename>\n");
	print("Where filename is the path to the Asterisk csv file to import (Master.csv)\n");
	print("This script is safe to run multiple times on a growing log file as it only imports records that are newer than the database\n");
	exit(0);
}

//** 1) Find records in the asterisk log file. **
$rows = 0;
$handle = fopen($logfile, "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
print($data[0]);
	// NOTE: the fields in Master.csv can vary. This should work by default on all installations but you may have to edit the next line to match your configuration
    list($accountcode, $src, $dst, $dcontext, $clid, $channel, $dstchannel, $lastapp, 
         $lastdata, $start, $answer, $end, $duration, $billsec, $disposition, $amaflags, $uniqueid, $userfield, 
	 $sequence, $tonumber, $dtmfpressed, $campaign ) = $data;
	/** 2) Test to see if the entry is unique **/

	$sql = "SELECT calldate, src, duration".
	" FROM cdrs_tpl".
	" WHERE calldate='$start'".
	" AND accountcode='$accountcode'".
	" AND src='$src'".
	" AND duration='$duration'".
	" LIMIT 1"; 

	/* $sql = "SELECT tpl.calldate, tpl.src, tpl.duration, cls.mobile, cls.campaign campaign_name ".
	" FROM cdrs_tpl tpl".
	" left join calls cls on tpl.accountcode = cls.accountcode".
	" WHERE tpl.calldate='$start'".
	" AND tpl.accountcode='$accountcode'".
	" AND tpl.src='$src'".
	" AND tpl.duration='$duration'".
	" LIMIT 1"; */
	print($sql);

	if(!($result = $mysqli->query($sql))) {
		print("Invalid query: " . mysqli_error()."\n");
		print("SQL: $sql\n");
		die();
	}
	if($result->num_rows === 0) { // we found a new record so add it to the DB
		// 3) insert each row in the database
        if ($answer === '') $answer = '0000-00-00 00:00:00';  // replace empty date with default value
print("DST<<".$dst.">>");
 	if($dst != '') {
		$dcontext = $campaign;
	}
	$sql_mobile_campaign = $mysqli->query("SELECT mobile, campaign campaign_name ".
						" FROM calls".
						" WHERE accountcode='$accountcode'".
						" AND context='$dcontext'".
						" LIMIT 1");
	print("SELECT mobile, campaign campaign_name ".
		" FROM calls".
		" WHERE accountcode='$accountcode'".
		" AND context='$dcontext'".
		" LIMIT 1");
	$mobile = ""; $campaign_name = "";

				print_r($sql_mobile_campaign);
				print($sql_mobile_campaign->num_rows);
	if ($sql_mobile_campaign->num_rows > 0) {
		// echo "!!";
		while($row_mobile_campaign = $sql_mobile_campaign->fetch_assoc()) { 
			// echo "@@";
			$mobile = $row_mobile_campaign['mobile'];
			$campaign_name = $row_mobile_campaign['campaign_name'];
		}
	}

        $sql = "INSERT INTO cdrs_tpl (calldate,
                                 answerdate,
                                 hangupdate,
                                 clid,
                                 src,
                                 dst,
                                 dcontext,
                                 channel,
                                 dstchannel,
                                 lastapp,
                                 lastdata,
                                 duration,
                                 billsec,
                                 disposition,
                                 amaflags,
                                 accountcode,
                                 uniqueid,
                                 userfield,
				 sequence,
				 tonumber, 
				 dtmfpressed,
				 campaign) 
            VALUES('$start',
                '$answer',
                '$end',
                '".$mysqli->escape_string($clid)."',
                '$src',
                '$mobile',
                '$dcontext',
                '$channel',
                '$dstchannel',
                '$lastapp',
                '$lastdata',
                '$duration',
                '$billsec',
                '$disposition',
                '$amaflags',
                '$accountcode',
                '$uniqueid', 
                '$userfield',
		'$sequence',
		'$tonumber',
		'$dtmfpressed',
		'$campaign_name')";

		print($sql);
		if(!($result2 = $mysqli->query($sql))) {
			print("Invalid query: " . $mysqli->error."\n");
			print("SQL: $sql\n");
			continue; // skip invalid record or you can die() here
		}
		print("Inserted: $end $src $duration\n");
		$rows++;

		// Remove the current line in CSV file
		
	} else {
		print("Not unique: $end $src $duration\n");
	}
}

$result->free();
//$result2->free();
$mysqli->close();

fclose($handle);
print("$rows imported\n");
?>

