<?php
// $who_ami = shell_exec("whoami"); // Verify the user
// print($who_ami);

$unlink_file = unlink("/var/log/asterisk/cdr-custom/Master_1.csv"); // Remove the existing file
$rename_file = rename("/var/log/asterisk/cdr-custom/Master.csv", "/var/log/asterisk/cdr-custom/Master_1.csv"); // Before save to DB, move the file
print($rename_file);
?>
