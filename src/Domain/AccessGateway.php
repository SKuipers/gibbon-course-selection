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

namespace Modules\CourseSelection\Domain;

/**
 * Course Selection: courseSelectionAccess Table Gateway
 *
 * @version v14
 * @since   13th April 2017
 * @author  Sandra Kuipers
 */
class AccessGateway
{
    protected $pdo;

    public function __construct(\Gibbon\sqlConnection $pdo)
    {
        $this->pdo = $pdo;
    }

    public function selectAll()
    {
        $data = array();
        $sql = "SELECT courseSelectionAccess.*, gibbonSchoolYear.name as gibbonSchoolYearName FROM courseSelectionAccess JOIN gibbonSchoolYear ON (courseSelectionAccess.gibbonSchoolYearID=gibbonSchoolYear.gibbonSchoolYearID) ORDER BY dateStart, dateEnd";
        $result = $this->pdo->executeQuery($data, $sql);

        return $result;
    }

    public function selectOne($courseSelectionAccessID)
    {
        $data = array('courseSelectionAccessID' => $courseSelectionAccessID);
        $sql = "SELECT courseSelectionAccess.*, gibbonSchoolYear.name as gibbonSchoolYearName FROM courseSelectionAccess JOIN gibbonSchoolYear ON (courseSelectionAccess.gibbonSchoolYearID=gibbonSchoolYear.gibbonSchoolYearID) WHERE courseSelectionAccessID=:courseSelectionAccessID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $result;
    }

    public function insert(array $data)
    {
        $sql = "INSERT INTO courseSelectionAccess SET gibbonSchoolYearID=:gibbonSchoolYearID, gibbonRollGroupIDList=:gibbonRollGroupIDList, dateStart=:dateStart, dateEnd=:dateEnd, accessType=:accessType";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getConnection()->lastInsertID();
    }

    public function update(array $data)
    {
        $sql = "UPDATE courseSelectionAccess SET gibbonSchoolYearID=:gibbonSchoolYearID, gibbonRollGroupIDList=:gibbonRollGroupIDList, dateStart=:dateStart, dateEnd=:dateEnd, accessType=:accessType WHERE courseSelectionAccessID=:courseSelectionAccessID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getQuerySuccess();
    }

    public function delete($courseSelectionAccessID)
    {
        $data = array('courseSelectionAccessID' => $courseSelectionAccessID);
        $sql = "DELETE FROM courseSelectionAccess WHERE courseSelectionAccessID=:courseSelectionAccessID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getQuerySuccess();
    }
}