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
 * Course Selection: courseSelectionBlock Table Gateway
 *
 * @version v14
 * @since   13th April 2017
 * @author  Sandra Kuipers
 */
class BlocksGateway
{
    protected $pdo;

    public function __construct(\Gibbon\sqlConnection $pdo)
    {
        $this->pdo = $pdo;
    }

    // BLOCKS

    public function selectAll()
    {
        $data = array();
        $sql = "SELECT courseSelectionBlock.*, gibbonSchoolYear.name as schoolYearName, gibbonDepartment.name as departmentName, COUNT(gibbonCourseID) as courseCount
                FROM courseSelectionBlock
                JOIN gibbonSchoolYear ON (courseSelectionBlock.gibbonSchoolYearID=gibbonSchoolYear.gibbonSchoolYearID)
                LEFT JOIN gibbonDepartment ON (courseSelectionBlock.gibbonDepartmentID=gibbonDepartment.gibbonDepartmentID)
                LEFT JOIN courseSelectionBlockCourse ON (courseSelectionBlockCourse.courseSelectionBlockID=courseSelectionBlock.courseSelectionBlockID)
                GROUP BY courseSelectionBlock.courseSelectionBlockID
                ORDER BY name";

        return $this->pdo->executeQuery($data, $sql);
    }

    public function selectOne($courseSelectionBlockID)
    {
        $data = array('courseSelectionBlockID' => $courseSelectionBlockID);
        $sql = "SELECT courseSelectionBlock.*, gibbonSchoolYear.name as gibbonSchoolYearName
                FROM courseSelectionBlock
                JOIN gibbonSchoolYear ON (courseSelectionBlock.gibbonSchoolYearID=gibbonSchoolYear.gibbonSchoolYearID)
                WHERE courseSelectionBlockID=:courseSelectionBlockID ";

        return $this->pdo->executeQuery($data, $sql);
    }

    public function insert(array $data)
    {
        $sql = "INSERT INTO courseSelectionBlock SET gibbonSchoolYearID=:gibbonSchoolYearID, gibbonDepartmentID=:gibbonDepartmentID, name=:name, description=:description, minSelect=:minSelect, maxSelect=:maxSelect";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getConnection()->lastInsertID();
    }

    public function update(array $data)
    {
        $sql = "UPDATE courseSelectionBlock SET gibbonSchoolYearID=:gibbonSchoolYearID, gibbonDepartmentID=:gibbonDepartmentID, name=:name, description=:description, minSelect=:minSelect, maxSelect=:maxSelect WHERE courseSelectionBlockID=:courseSelectionBlockID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getQuerySuccess();
    }

    public function delete($courseSelectionBlockID)
    {
        $data = array('courseSelectionBlockID' => $courseSelectionBlockID);

        $sql = "DELETE FROM courseSelectionBlock WHERE courseSelectionBlockID=:courseSelectionBlockID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getQuerySuccess();
    }

    // BLOCK COURSES

    public function selectAllCoursesByBlock($courseSelectionBlockID)
    {
        $data = array('courseSelectionBlockID' => $courseSelectionBlockID);
        $sql = "SELECT courseSelectionBlockCourse.*, gibbonCourse.name as courseName, gibbonCourse.nameShort as courseNameShort
                FROM courseSelectionBlockCourse
                JOIN gibbonCourse ON (courseSelectionBlockCourse.gibbonCourseID=gibbonCourse.gibbonCourseID)
                WHERE courseSelectionBlockID=:courseSelectionBlockID
                ORDER BY gibbonCourse.nameShort";

        return $this->pdo->executeQuery($data, $sql);
    }

    public function insertCourse(array $data)
    {
        $sql = "INSERT INTO courseSelectionBlockCourse SET courseSelectionBlockID=:courseSelectionBlockID, gibbonCourseID=:gibbonCourseID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getConnection()->lastInsertID();
    }

    public function deleteCourse($courseSelectionBlockID, $gibbonCourseID)
    {
        $data = array('courseSelectionBlockID' => $courseSelectionBlockID, 'gibbonCourseID' => $gibbonCourseID);
        $sql = "DELETE FROM courseSelectionBlockCourse WHERE courseSelectionBlockID=:courseSelectionBlockID AND gibbonCourseID=:gibbonCourseID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getQuerySuccess();
    }

    public function deleteAllCoursesByBlock($courseSelectionBlockID)
    {
        $data = array('courseSelectionBlockID' => $courseSelectionBlockID);
        $sql = "DELETE FROM courseSelectionBlockCourse WHERE courseSelectionBlockID=:courseSelectionBlockID";
        $result = $this->pdo->executeQuery($data, $sql);

        return $this->pdo->getQuerySuccess();
    }

    // FORM QUERIES

    public function selectAvailableCoursesByDepartment($courseSelectionBlockID, $gibbonDepartmentID)
    {
        $data = array('courseSelectionBlockID' => $courseSelectionBlockID, 'gibbonDepartmentID' => $gibbonDepartmentID);
        $sql = "SELECT gibbonCourse.gibbonCourseID AS value, CONCAT(gibbonCourse.nameShort, ' - ', gibbonCourse.name) as name
                FROM gibbonCourse
                JOIN courseSelectionBlock ON (gibbonCourse.gibbonSchoolYearID=courseSelectionBlock.gibbonSchoolYearID)
                LEFT JOIN courseSelectionBlockCourse ON (
                    courseSelectionBlockCourse.gibbonCourseID=gibbonCourse.gibbonCourseID
                    AND courseSelectionBlockCourse.courseSelectionBlockID=:courseSelectionBlockID)
                WHERE gibbonCourse.gibbonDepartmentID=:gibbonDepartmentID
                AND courseSelectionBlock.courseSelectionBlockID=:courseSelectionBlockID
                AND courseSelectionBlockCourse.gibbonCourseID IS NULL
                ORDER BY nameShort, name";

        return $this->pdo->executeQuery($data, $sql);
    }
}