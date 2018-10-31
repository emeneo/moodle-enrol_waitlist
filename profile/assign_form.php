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


defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/lib/formslib.php');

class course_fields_assign_form extends moodleform {
    function definition () {
        global $CFG,$DB;

        $mform =& $this->_form;

        $cate = array();
        $categories = $DB->get_records('course_categories', null, 'sortorder ASC');
        foreach($categories as $category){
            $cate[$category->id] = $category->name;
        }

        $mform->addElement('select', 'category', get_string('assign::category', 'local_course_fields'), $cate);
        $mform->disable_form_change_checker();
    }
}