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

require_once("$CFG->libdir/formslib.php");

class enrol_waitlist_enrol_form extends moodleform {
    protected $instance;

    public function definition() {
        $mform = $this->_form;
        $instance = $this->_customdata;
        $this->instance = $instance;
        $plugin = enrol_get_plugin('waitlist');

        if ($instance->password) {
            $heading = $plugin->get_instance_name($instance);
            $mform->addElement('header', 'waitlistheader', $heading);
            $mform->addElement('passwordunmask', 'enrolpassword', get_string('password', 'enrol_waitlist'));
        } else {
            // nothing?
        }
        // echo "<pre>";print_r($instance);die();
        $currentTime = time();
        $isDisabled = false;
        $openTime = $closeTime = 0;
        if($instance->enrolstartdate) { $openTime = $instance->enrolstartdate;
        }
        if($instance->enrolenddate) { $closeTime = $instance->enrolenddate;
        }
        if($openTime&&($currentTime < $openTime)){
               $isDisabled = true;
        }

        if($closeTime&&($currentTime > $closeTime)){
               $isDisabled = true;
        }

        global $USER;
        if($instance->customchar1){
            if($USER->phone2 != strtoupper($instance->customchar1)){
                 $isDisabled = true;
            }

            if($instance->customchar2){
                $v1 = $USER->department;
                $v1 = substr($v1,1,(strlen($v1) - 1));
                $v1 = substr($v1,0,-1);
                if($v1 != strtoupper($instance->customchar2)){
                    $isDisabled = true;
                }
            }
        }
        if($isDisabled){
               $mform->addElement('html', get_string('disable', 'enrol_waitlist'));
               $mform->addElement("html","<br/><br/><p align='center'><input type='button' value='".get_string('continue', 'enrol_waitlist')."' onclick='window.history.go(-1)'></p>");
        }else{
               // $this->add_action_buttons(false, get_string('enrolme', 'enrol_waitlist'));
               global $DB;

               $enroledCount = $DB->count_records('user_enrolments', array('enrolid' => $instance->id));
               $lineCount = $DB->count_records('user_enrol_waitlist', array('instanceid' => $instance->id));
            if($instance->customint3 == 0){
                $mform->addElement('html', get_string('confirmation', 'enrol_waitlist'));
            }else if($enroledCount < $instance->customint3){
                $mform->addElement('html', get_string('confirmation', 'enrol_waitlist'));
            }else{
                  $mform->addElement('html', get_string('confirmationfull', 'enrol_waitlist'));
                  $mform->addElement('html', get_string('lineinfo', 'enrol_waitlist').$lineCount."<br>");
                  $mform->addElement('html', get_string('lineconfirm', 'enrol_waitlist'));
            }
               // $this->add_action_buttons(false, get_string('confirmation_yes', 'enrol_waitlist'));
               $mform->addElement("html","<br/><p align='center'><input type='submit' value='".get_string('confirmation_yes', 'enrol_waitlist')."' onclick='../../'>&nbsp;&nbsp;<input type='button' value='".get_string('confirmation_cancel', 'enrol_waitlist')."' onclick='window.history.go(-1)'></p>");
        }

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $instance->courseid);

        $mform->addElement('hidden', 'instance');
        $mform->setType('instance', PARAM_INT);
        $mform->setDefault('instance', $instance->id);
    }

    public function validation($data, $files) {
        global $DB, $CFG;

        $errors = parent::validation($data, $files);
        $instance = $this->instance;

        if ($instance->password) {
            if ($data['enrolpassword'] !== $instance->password) {
                if ($instance->customint1) {
                    $groups = $DB->get_records('groups', array('courseid' => $instance->courseid), 'id ASC', 'id, enrolmentkey');
                    $found = false;
                    foreach ($groups as $group) {
                        if (empty($group->enrolmentkey)) {
                            continue;
                        }
                        if ($group->enrolmentkey === $data['enrolpassword']) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        // we can not hint because there are probably multiple passwords
                        $errors['enrolpassword'] = get_string('passwordinvalid', 'enrol_waitlist');
                    }

                } else {
                    $plugin = enrol_get_plugin('waitlist');
                    if ($plugin->get_config('showhint')) {
                        $textlib = textlib_get_instance();
                        $hint = $textlib->substr($instance->password, 0, 1);
                        $errors['enrolpassword'] = get_string('passwordinvalidhint', 'enrol_waitlist', $hint);
                    } else {
                        $errors['enrolpassword'] = get_string('passwordinvalid', 'enrol_waitlist');
                    }
                }
            }
        }

        return $errors;
    }
}