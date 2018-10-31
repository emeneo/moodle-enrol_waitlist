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

class field_form extends moodleform {

    var $field;

    /// Define the form
    function definition () {
        global $CFG;

        $mform =& $this->_form;

        /// Everything else is dependant on the data type
        $datatype = $this->_customdata;
        require_once($CFG->dirroot.'/enrol/waitlist/profile/field/'.$datatype.'/define.class.php');
        $newfield = 'waitlist_fields_profile_define_'.$datatype;
        $this->field = new $newfield();

        $strrequired = get_string('required');

        /// Add some extra hidden fields
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'action', 'editfield');
        $mform->setType('action', PARAM_ACTION);
        $mform->addElement('hidden', 'datatype', $datatype);
        $mform->setType('datatype', PARAM_ALPHA);

        $this->field->define_form($mform);

        $this->add_action_buttons(true);
    }


    /// alter definition based on existing or submitted data
    function definition_after_data () {
        $mform =& $this->_form;
        $this->field->define_after_data($mform);
    }


    /// perform some moodle validation
    function validation($data, $files) {
        return $this->field->define_validate($data, $files);
    }

    function editors() {
        return $this->field->define_editors();
    }
}


