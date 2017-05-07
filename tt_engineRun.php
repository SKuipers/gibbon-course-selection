<?php
/*
Gibbon: Course Selection & Timetabling Engine
Copyright (C) 2017, Sandra Kuipers
*/

include '../../functions.php';
include '../../config.php';

use CourseSelection\Domain\TimetableGateway;
use CourseSelection\BackgroundProcess;

// Cancel out now if we're not running via CLI
if (php_sapi_name() != 'cli') {
    die( __($guid, 'This script cannot be run from a browser, only via CLI.') );
}

// Autoloader & Module includes
$loader->addNameSpace('CourseSelection\\', 'modules/Course Selection/src/');

// Setup default settings
getSystemSettings($guid, $connection2);
setCurrentSchoolYear($guid, $connection2);

$gibbonSchoolYearID = (isset($argv[1]))? $argv[1] : null ;

$processor = new BackgroundProcess($_SESSION[$guid]['absolutePath'].'/uploads/engine');



sleep(20);

$processor->stopProcess('engine');


$report = 'Complete!';

// End the process and output the result to terminal (output file)
die( $report."\n" );