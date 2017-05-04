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

use Gibbon\Modules\CourseSelection\DecisionTree\NodeEvaluator;

/**
 * Implementation of the NodeEvaluator interface for the Timetabling Engine
 *
 * @version v14
 * @since   4th May 2017
 */
class Evaluator implements NodeEvaluator
{
    protected $environment;

    public function __construct($environment = array())
    {
        $this->environment = $environment;
    }

    /**
     * Evaluate a node based on a set of conditions (timetable related in this case)
     * and return a weighting to represent the node's viability as a solution to the problem
     *
     * @param   object  &$node
     * @return  float
     */
    public function evaluateNode(&$node) : float
    {
        return 0.0;
    }

    /**
     * Evaluate a tree of nodes to determine if the goal condition has been reached.
     *
     * @param   array  &$nodes
     * @return  bool
     */
    public function evaluateTree(&$nodes) : bool
    {
        return false;
    }
}
