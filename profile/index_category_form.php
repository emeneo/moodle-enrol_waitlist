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

class category_form extends moodleform {

    // Define the form
    function definition () {
        global $USER, $CFG, $DB;

        $mform =& $this->_form;

        $strrequired = get_string('required');

        /// Add some extra hidden fields
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'action', 'editcategory');
        $mform->setType('action', PARAM_ACTION);

        $mform->addElement('text', 'name', get_string('profilecategoryname', 'admin'), 'maxlength="255" size="30"');
        $mform->setType('name', PARAM_MULTILANG);
        $mform->addRule('name', $strrequired, 'required', null, 'client');

        $mform->addElement('header', '_categorysettings', get_string('profilecategorysettings', 'local_course_fields'));
        $choices = $this->profile_list_categories();
        foreach($choices as $cid => $choice){
               $style = "";
               $categorie = $DB->get_record('course_categories', array('id' => $cid));
               $marginLeft = ($categorie->depth - 1) * 20;
            if($marginLeft){
                $style = ' style="margin-left:'.$marginLeft.'px;"';
            }
               $mform->addElement('html','<div'.$style.'>');
               $mform->addElement('advcheckbox','categoryid[]',$choice,null,null,array(0,$cid));
               $mform->addElement('html','</div>');
        }

        $this->add_action_buttons(true);

    } /// End of function

    /// perform some moodle validation
    function validation($data, $files) {
        global $CFG, $DB;
        $errors = parent::validation($data, $files);

        $data  = (object)$data;

        $duplicate = $DB->record_exists('course_info_category', array('name' => $data->name));

        /// Check the name is unique
        if (!empty($data->id)) { // we are editing an existing record
            $olddata = $DB->get_record('course_info_category', array('id' => $data->id));
            // name has changed, new name in use, new name in use by another record
            $dupfound = (($olddata->name !== $data->name) && $duplicate && ($data->id != $duplicate->id));
        }
        else { // new profile category
            $dupfound = $duplicate;
        }

        if ($dupfound ) {
            $errors['name'] = get_string('profilecategorynamenotunique', 'admin');
        }

        return $errors;
    }

    function profile_list_categories() {
        global $DB;
        if (!$categories = $DB->get_records_menu('course_categories', null, 'sortorder ASC', 'id, name')) {
            $categories = array();
        }
        return $categories;
    }
}


