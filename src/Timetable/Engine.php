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

namespace Gibbon\Modules\CourseSelection\Timetable;

use Gibbon\Modules\CourseSelection\DecisionTree\DecisionTree;

/**
 * Timetabling Engine
 *
 * Handles batch processing of student timetable generation via decision tree
 *
 * @version v14
 * @since   4th May 2017
 */
class Engine
{
    protected $settings;

    protected $validator;
    protected $evaulator;
    protected $solver;

    public function __construct(EngineSettings $settings = null)
    {
        $this->settings = $settings ?? new EngineSettings();

        $this->validator = new Validator();
        $this->evaulator = new Evaluator();
        $this->solver = new DecisionTree($this->validator, $this->evaulator);
    }

    public function process($data)
    {
        return $this->solver->buildTree($data);
    }
}
