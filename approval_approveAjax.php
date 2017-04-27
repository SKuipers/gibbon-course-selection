<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

include '../../functions.php';

use Gibbon\Modules\CourseSelection\Domain\SelectionsGateway;

// Autoloader & Module includes
$loader->addNameSpace('Gibbon\Modules\CourseSelection\\', 'modules/Course Selection/src/');

if (isActionAccessible($guid, $connection2, '/modules/Course Selection/approval_byOffering.php') == false) {
    die(false);
} else {
    //Proceed!
    $courseSelectionOfferingID = $_POST['courseSelectionOfferingID'] ?? '';
    $gibbonPersonIDStudent = $_POST['gibbonPersonIDStudent'] ?? '';
    $courseSelectionChoiceID = $_POST['courseSelectionChoiceID'] ?? '';
    $status = $_POST['status'] ?? '';

    if (empty($courseSelectionOfferingID) || empty($gibbonPersonIDStudent) || empty($courseSelectionChoiceID)) {
        die(false);
    } else {
        $gateway = new SelectionsGateway($pdo);
        if ($status === 'true') {
            $data = array();
            $data['courseSelectionChoiceID'] = $courseSelectionChoiceID;
            $data['gibbonPersonIDApproved'] = $_SESSION[$guid]['gibbonPersonID'];
            $data['timestampApproved'] = date('Y-m-d H:i:s');

            $gateway->insertApproval($data);
        } else {
            $gateway->deleteApproval($courseSelectionChoiceID);
        }
        die(true);
    }
}