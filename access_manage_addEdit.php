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

use Gibbon\Forms\Form;
use Modules\CourseSelection\Domain\AccessGateway;

// Autoloader & Module includes
$loader->addNameSpace('Modules\CourseSelection\\', 'modules/Course Selection/src/');
include "./modules/" . $_SESSION[$guid]["module"] . "/moduleFunctions.php" ;

if (isActionAccessible($guid, $connection2, '/modules/Course Selection/access_manage_addEdit.php') == false) {
    //Acess denied
    echo "<div class='error'>" ;
        echo "You do not have access to this action." ;
    echo "</div>" ;
} else {
    $gateway = new AccessGateway($pdo);

    $values = array(
        'courseSelectionAccessID' => '',
        'gibbonSchoolYearID'      => '',
        'dateStart'               => '',
        'dateEnd'                 => '',
        'accessType'              => '',
        'gibbonRollGroupIDList'   => ''
    );

    if (isset($_GET['courseSelectionAccessID'])) {

        $result = $gateway->selectOne($_GET['courseSelectionAccessID']);
        if ($result && $result->rowCount() == 1) {
            $values = $result->fetch();
        }

        $actionName = __('Edit Access');
        $actionURL = $_SESSION[$guid]['absoluteURL'].'/modules/'.$_SESSION[$guid]['module'].'/access_manage_editProcess.php';
    } else {
        $actionName = __('Add Access');
        $actionURL = $_SESSION[$guid]['absoluteURL'].'/modules/'.$_SESSION[$guid]['module'].'/access_manage_addProcess.php';
    }

    echo "<div class='trail'>" ;
    echo "<div class='trailHead'><a href='".$_SESSION[$guid]['absoluteURL']."'>".__($guid, 'Home')."</a> > <a href='".$_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/'.getModuleName($_GET['q']).'/'.getModuleEntry($_GET['q'], $connection2, $guid)."'>".__($guid, getModuleName($_GET['q']))."</a> > <a href='".$_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/'.getModuleName($_GET['q'])."/access_manage.php'>".__($guid, 'Course Selection Access')."</a> > </div><div class='trailEnd'>".$actionName.'</div>';
    echo "</div>" ;

    if (isset($_GET['return'])) {
        $editLink = (isset($_GET['editID']))? $_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/Course Selection/access_manage_addEdit.php&courseSelectionAccessID='.$_GET['editID'] : '';
        returnProcess($guid, $_GET['return'], $editLink, null);
    }

    $form = Form::create('accessRecord', $actionURL);

    $form->addHiddenValue('courseSelectionAccessID', $values['courseSelectionAccessID']);
    $form->addHiddenValue('address', $_SESSION[$guid]['address']);

    $sql = "SELECT gibbonSchoolYearID as value, name FROM gibbonSchoolYear WHERE status='Current' OR status='Upcoming' ORDER BY sequenceNumber";
    $row = $form->addRow();
        $row->addLabel('gibbonSchoolYearID', __('School Year'));
        $row->addSelect('gibbonSchoolYearID')
            ->fromQuery($pdo, $sql)
            ->isRequired()
            ->placeholder(__('Please select...'))
            ->selected($values['gibbonSchoolYearID']);

    $row = $form->addRow();
        $row->addLabel('dateStart', __('Start Date'));
        $row->addDate('dateStart')->isRequired()->setValue(dateConvertBack($guid, $values['dateStart']));

    $row = $form->addRow();
        $row->addLabel('dateEnd', __('End Date'));
        $row->addDate('dateEnd')->isRequired()->setValue(dateConvertBack($guid, $values['dateEnd']));

    $row = $form->addRow();
        $row->addLabel('accessType', __('Access Type'));
        $row->addSelect('accessType')->fromArray(array(
                'View' => __('View'),
                'Request' => __('Request Courses (approval)'),
                'Select' => __('Select Courses (no approval)')
            ))->isRequired()->selected($values['accessType']);

    $sql = "SELECT gibbonRoleID as value, name FROM gibbonRole ORDER BY name";
    $row = $form->addRow();
        $row->addLabel('gibbonRollGroupIDList', __('Available to Roles'));
        $row->addSelect('gibbonRollGroupIDList')
            ->fromQuery($pdo, $sql)
            ->isRequired()
            ->selectMultiple()
            ->selected(explode(',', $values['gibbonRollGroupIDList']));

    $row = $form->addRow();
        $row->addFooter();
        $row->addSubmit();

    echo $form->getOutput();
}