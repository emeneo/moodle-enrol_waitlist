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

 * *************************************************************************

 * *                  Waitlist Enrol                                      **

 * *************************************************************************

 * @copyright   emeneo.com                                                **

 * @link        emeneo.com                                                **

 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **

 * *************************************************************************

 * ************************************************************************

 */

defined('MOODLE_INTERNAL') || die();



require_once($CFG->libdir.'/formslib.php');



class enrol_waitlist_edit_form extends moodleform {



    function definition() {

        $mform = $this->_form;

        list($instance, $plugin, $context) = $this->_customdata;

        $mform->addElement('header', 'header', get_string('pluginname', 'enrol_waitlist'));

        $mform->addElement('text', 'name', get_string('custominstancename', 'enrol'));

        $mform->setType('name', PARAM_TEXT);

        $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),

                         ENROL_INSTANCE_DISABLED => get_string('no'));

        $mform->addElement('select', 'status', get_string('status', 'enrol_waitlist'), $options);

        $mform->addHelpButton('status', 'status', 'enrol_waitlist');

        $mform->setDefault('status', $plugin->get_config('status'));

        $mform->addElement('passwordunmask', 'password', get_string('password', 'enrol_waitlist'));

        $mform->addHelpButton('password', 'password', 'enrol_waitlist');

        if (empty($instance->id) and $plugin->get_config('requirepassword')) {

            $mform->addRule('password', get_string('required'), 'required', null, 'client');

        }

        $options = array(1 => get_string('yes'),

                         0 => get_string('no'));

        $mform->addElement('select', 'customint1', get_string('groupkey', 'enrol_waitlist'), $options);

        $mform->addHelpButton('customint1', 'groupkey', 'enrol_waitlist');

        $mform->setDefault('customint1', $plugin->get_config('groupkey'));

        if ($instance->id) {

            $roles = get_default_enrol_roles($context, $instance->roleid);

        } else {

            $roles = get_default_enrol_roles($context, $plugin->get_config('roleid'));

        }

        $mform->addElement('select', 'roleid', get_string('role', 'enrol_waitlist'), $roles);

        $mform->setDefault('roleid', $plugin->get_config('roleid'));

        $mform->addElement('duration', 'enrolperiod', get_string('enrolperiod', 'enrol_waitlist'), array('optional' => true, 'defaultunit' => 86400));

        $mform->setDefault('enrolperiod', $plugin->get_config('enrolperiod'));

        $mform->addHelpButton('enrolperiod', 'enrolperiod', 'enrol_waitlist');

        $mform->addElement('date_selector', 'enrolstartdate', get_string('enrolstartdate', 'enrol_waitlist'), array('optional' => true));

        $mform->setDefault('enrolstartdate', 0);

        $mform->addHelpButton('enrolstartdate', 'enrolstartdate', 'enrol_waitlist');

        $mform->addElement('date_selector', 'enrolenddate', get_string('enrolenddate', 'enrol_waitlist'), array('optional' => true));

        $mform->setDefault('enrolenddate', 0);

        $mform->addHelpButton('enrolenddate', 'enrolenddate', 'enrol_waitlist');

        (@$instance->customchar1) ? $customchar1_val = @$instance->customchar1 : $customchar1_val = 0;

        (@$instance->customchar2) ? $customchar2_val = @$instance->customchar2 : $customchar2_val = 0;

        $options = array('inf' => 'INF','ac' => 'AC','esb' => 'ESB','tec' => 'TEC','td' => 'TD',0 => get_string('all'));

        $faculty_html = '<div id="fitem_id_customchar1" class="fitem fitem_fselect "><div class="fitemtitle"><label for="id_customchar1">'.get_string('faculty', 'enrol_waitlist').' </label></div><div class="felement fselect">';

        $faculty_html .= '<select id="id_customchar1">';

        foreach ($options as $key => $value) {

            if($key === $customchar1_val){

                $faculty_html .= '<option value="'.$key.'" selected>'.$value.'</option>';

            }else{

                $faculty_html .= '<option value="'.$key.'">'.$value.'</option>';

            }

        }

        $faculty_html .= '</select>&nbsp;';

        $faculty_html .= '<select id="id_customchar2"><option value=0>'.get_string('all').'</option>';

        $faculty_html .= '</select></div></div>';

        $faculty_html .= '<input type="hidden" id="customchar2_val" value="'.$customchar2_val.'">';

        $faculty_html .= '<script type="text/javascript" src="static/jquery.js"></script>';

        $faculty_html .= '<script type="text/javascript" src="static/faculty.js"></script>';

        $mform->addElement('html',$faculty_html);

        $mform->addElement('hidden', 'customchar1', $customchar1_val);

        $mform->setType('customchar1', PARAM_TEXT);

        $mform->addElement('hidden', 'customchar2', $customchar2_val);

        $mform->setType('customchar2', PARAM_TEXT);

        $options = array(0 => get_string('never'),

                 1800 * 3600 * 24 => get_string('numdays', '', 1800),

                 1000 * 3600 * 24 => get_string('numdays', '', 1000),

                 365 * 3600 * 24 => get_string('numdays', '', 365),

                 180 * 3600 * 24 => get_string('numdays', '', 180),

                 150 * 3600 * 24 => get_string('numdays', '', 150),

                 120 * 3600 * 24 => get_string('numdays', '', 120),

                 90 * 3600 * 24 => get_string('numdays', '', 90),

                 60 * 3600 * 24 => get_string('numdays', '', 60),

                 30 * 3600 * 24 => get_string('numdays', '', 30),

                 21 * 3600 * 24 => get_string('numdays', '', 21),

                 14 * 3600 * 24 => get_string('numdays', '', 14),

                 7 * 3600 * 24 => get_string('numdays', '', 7));

        $mform->addElement('select', 'customint2', get_string('longtimenosee', 'enrol_waitlist'), $options);

        $mform->setDefault('customint2', $plugin->get_config('longtimenosee'));

        $mform->addHelpButton('customint2', 'longtimenosee', 'enrol_waitlist');

        $mform->addElement('text', 'customint3', get_string('maxenrolled', 'enrol_waitlist'));

        $mform->setDefault('customint3', $plugin->get_config('maxenrolled'));

        $mform->addHelpButton('customint3', 'maxenrolled', 'enrol_waitlist');

        $mform->setType('customint3', PARAM_INT);

        $mform->addElement('advcheckbox', 'customint4', get_string('sendcoursewelcomemessage', 'enrol_waitlist'));

        $mform->setDefault('customint4', $plugin->get_config('sendcoursewelcomemessage'));

        $mform->addHelpButton('customint4', 'sendcoursewelcomemessage', 'enrol_waitlist');

        $mform->addElement('textarea', 'customtext1', get_string('customwelcomemessage', 'enrol_waitlist'), array('cols' => '60', 'rows' => '8'));

        // custom fields
        global $DB, $CFG;

        $fields = $DB->get_records('waitlist_info_field');
        // echo "<pre>";print_r($fields);exit;
        if(count($fields) > 0){
            $usedFields = $DB->get_records('waitlist_info_data', array('course_id' => $instance->courseid));
            $custom_data = array();
            foreach ($usedFields as $usedField) {
                $custom_data[$usedField->fieldid] = $usedField->data;
            }

            // echo "<pre>";print_r($usedFields);exit;
            $mform->addElement('header', 'header', 'Custom Fields');

            foreach($fields as $field){
                if($field->datatype == 'checkbox'){
                    $mform->addElement('checkbox', 'custom_field_'.$field->shortname, $field->name);

                    $val = 0;
                    if(isset($custom_data[$field->id])){
                        $val = $custom_data[$field->id];
                    }
                    $mform->setDefault('custom_field_'.$field->shortname, $val);
                }

                if($field->datatype == 'text'){
                    $mform->addElement('text', 'custom_field_'.$field->shortname, $field->name);
                    $mform->setType('custom_field_'.$field->shortname, PARAM_TEXT);

                    $val = '';
                    if(isset($custom_data[$field->id])){
                        $val = $custom_data[$field->id];
                    }
                    $mform->setDefault('custom_field_'.$field->shortname, $val);
                }

                if($field->datatype == 'textarea'){
                    $mform->addElement('textarea', 'custom_field_'.$field->shortname, $field->name, array('cols' => '60', 'rows' => '4'));

                    $val = '';
                    if(isset($custom_data[$field->id])){
                        $val = $custom_data[$field->id];
                    }
                    $mform->setDefault('custom_field_'.$field->shortname, $val);
                }

            }
        }

        $mform->addElement('hidden', 'id');

        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'courseid');

        $mform->setType('courseid', PARAM_INT);

        $this->add_action_buttons(true, ($instance->id ? null : get_string('addinstance', 'enrol')));

        $this->set_data($instance);

    }



    function validation($data, $files) {

        global $DB, $CFG;

        $errors = parent::validation($data, $files);

        list($instance, $plugin, $context) = $this->_customdata;

        $checkpassword = false;

        if ($instance->id) {

            if ($data['status'] == ENROL_INSTANCE_ENABLED) {

                if ($instance->password !== $data['password']) {

                    $checkpassword = true;

                }

            }

        } else {

            if ($data['status'] == ENROL_INSTANCE_ENABLED) {

                $checkpassword = true;

            }

        }

        if ($checkpassword) {

            $require = $plugin->get_config('requirepassword');

            $policy  = $plugin->get_config('usepasswordpolicy');

            if ($require and trim($data['password']) === '') {

                $errors['password'] = get_string('required');

            } else if ($policy) {

                $errmsg = '';// prevent eclipse warning

                if (!check_password_policy($data['password'], $errmsg)) {

                    $errors['password'] = $errmsg;

                }

            }

        }

        if ($data['status'] == ENROL_INSTANCE_ENABLED) {

            if (!empty($data['enrolenddate']) and $data['enrolenddate'] < $data['enrolstartdate']) {

                $errors['enrolenddate'] = get_string('enrolenddaterror', 'enrol_waitlist');

            }

        }

        return $errors;

    }

}