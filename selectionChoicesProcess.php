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

use Gibbon\Modules\CourseSelection\Domain\AccessGateway;
use Gibbon\Modules\CourseSelection\Domain\SelectionsGateway;

// Autoloader & Module includes
$loader->addNameSpace('Gibbon\Modules\CourseSelection\\', 'modules/Course Selection/src/');

$courseSelectionOfferingID = $_POST['courseSelectionOfferingID'] ?? '';
$gibbonPersonIDStudent = $_POST['gibbonPersonIDStudent'] ?? '';

$URL = $_SESSION[$guid]['absoluteURL']."/index.php?q=/modules/Course Selection/selectionChoices.php&sidebar=false&gibbonPersonIDStudent={$gibbonPersonIDStudent}&courseSelectionOfferingID={$courseSelectionOfferingID}";

if (isActionAccessible($guid, $connection2, '/modules/Course Selection/selection.php') == false) {
    $URL .= '&return=error0';
    header("Location: {$URL}");
    exit;
} else {
    //Proceed!
    $accessGateway = new AccessGateway($pdo);
    $accessRequest = $accessGateway->getAccessByOfferingAndPerson($courseSelectionOfferingID, $gibbonPersonIDStudent);

    if (!$accessRequest || $accessRequest->rowCount() == 0) {
        $URL .= '&return=error0';
        header("Location: {$URL}");
        exit;
    } else {
        $access = $accessRequest->fetch();

        $data = array();
        $data['gibbonSchoolYearID'] = $_POST['gibbonSchoolYearID'] ?? '';
        $data['gibbonPersonIDStudent'] = $gibbonPersonIDStudent;
        $data['gibbonPersonIDSelected'] = $_POST['gibbonPersonIDSelected'] ?? '';
        $data['timestampSelected'] = date('Y-m-d H:i:s');
        $data['gibbonPersonIDStatusChange'] = $_POST['gibbonPersonIDSelected'] ?? '';
        $data['timestampStatusChange'] = date('Y-m-d H:i:s');
        $data['notes'] = '';

        if (empty($courseSelectionOfferingID) || empty($data['gibbonSchoolYearID']) || empty($data['gibbonPersonIDStudent']) || empty($data['gibbonPersonIDSelected'])) {
            $URL .= '&return=error1';
            header("Location: {$URL}");
            exit;
        } else {
            $partialFail = false;
            $gateway = new SelectionsGateway($pdo);

            $courseSelections = $_POST['courseSelection'] ?? array();

            if (!empty($courseSelections) && is_array($courseSelections)) {
                foreach ($courseSelections as $courseSelection) {
                    if (empty($courseSelection)) continue;

                    $data['gibbonCourseID'] = $courseSelection;
                    $data['status'] = ($access['accessType'] == 'Select')? 'Approved' : 'Requested';

                    $insertID = $gateway->insertChoice($data);
                    $partialFail &= empty($insertID);
                }
            }

            $courseSelectionsList = implode(',', $courseSelections);
            $gateway->updateUnselectedChoicesBySchoolYearAndPerson($data['gibbonSchoolYearID'], $gibbonPersonIDStudent, $courseSelectionsList);

            $data = array();
            $data['gibbonSchoolYearID'] = $_POST['gibbonSchoolYearID'] ?? '';
            $data['gibbonPersonIDStudent'] = $gibbonPersonIDStudent;
            $data['courseSelectionOfferingID'] = $courseSelectionOfferingID ?? '';

            $insertID = $gateway->insertChoiceOffering($data);
            $partialFail &= empty($insertID);

            if ($partialFail == true) {
                $URL .= '&return=error2';
                header("Location: {$URL}");
                exit;
            } else {
                $URL .= "&return=success0";
                header("Location: {$URL}");
                exit;
            }
        }
    }
}