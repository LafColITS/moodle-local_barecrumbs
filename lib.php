<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Lib functions for Local Barecrumbs plugin
 *
 * @package    local_barecrumbs
 * @copyright  2018 onwards Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Hook into the navigation extension API to alter the breadcrumbs.
 *
 * @param navigation_node_collection $navigation - Moodle passes us an object describing the menu
 * @return void
 */
function local_barecrumbs_extend_navigation($navigation) {
    global $COURSE, $DB;

    // If the plugin is turned off, do nothing.
    if (!get_config('local_barecrumbs', 'onoff')) {
        return;
    }

    // Find all category nodes.
    $catnodes = $navigation->find_all_of_type(navigation_node::TYPE_CATEGORY);
    $catnodes = array_merge($catnodes, $navigation->find_all_of_type(navigation_node::TYPE_MY_CATEGORY));

    // And iterate over those category nodes.
    foreach ($catnodes as $node) {
        // If the node is active...
        if (gettype($node) == "object" && $node->contains_active_node()) {
            $category = $DB->get_record('course_categories', array('id' => $node->key));
            $context = context_coursecat::instance($category->id);
            // ...and the course is hidden, and the user can't see hidden courses...
            if ($category->visible == 0 && !has_capability('moodle/category:viewhiddencategories', $context)) {
                // Then set the 'action' property (link) to null.
                $node->action = null;
            }
        }
    }
}
