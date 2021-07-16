<?php
 
/* 
 * This script backs up the MYSQL DB
 * It keeps only 31 backups of past 31 days, and backups of each 1st day of past months.
 */
 
define('DB_HOST', 'ec2-54-85-92-29.compute-1.amazonaws.com');
define('DB_NAME', 'wrd1j72622l3g');
define('DB_USER', 'root');
define('DB_PASSWORD', 'xU6athug');
define('BACKUP_SAVE_TO', 'ec2-54-85-92-29.compute-1.amazonaws.com/admin/cron/backups'); // without trailing slash
 
$time = time();
$day = date('j', $time);
if ($day == 1) {
    $date = date('Y-m-d', $time);
} else {
    $date = $day;
}
 
 
 
$backupFile = BACKUP_SAVE_TO . '/' . DB_NAME . '_' . $date . '.gz';

//echo $backupFile;

if (file_exists($backupFile)) {
    unlink($backupFile);
}
$command = 'mysqldump --opt -h ' . DB_HOST . ' -u ' . DB_USER . ' -p\'' . DB_PASSWORD . '\' ' . DB_NAME . ' | gzip > ' . $backupFile;
echo $command;
system($command);
 
?>