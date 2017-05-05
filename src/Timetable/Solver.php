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
use Gibbon\Modules\CourseSelection\DecisionTree\NodeValidator;
use Gibbon\Modules\CourseSelection\DecisionTree\NodeEvaluator;

/**
 * Problem solver for the Timetabling Engine: impemented as a decision tree
 *
 * @version v14
 * @since   4th May 2017
 */
class Solver
{
    protected $decisionTree;

    public function __construct(NodeValidator $validator, NodeEvaluator $evaulator)
    {
        $this->decisionTree = new DecisionTree($validator, $evaulator);
    }

    public function makeDecisions(&$data) : array
    {
        return $this->decisionTree->buildTree($data);
    }
}
