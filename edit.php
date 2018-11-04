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

require('../../config.php');

require_once('edit_form.php');



$courseid   = required_param('courseid', PARAM_INT);

$instanceid = optional_param('id', 0, PARAM_INT); // instanceid



$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

// $context = get_context_instance(CONTEXT_COURSE, $course->id, MUST_EXIST);

$context = context_course::instance($course->id);



require_login($course);

require_capability('enrol/waitlist:config', $context);



$PAGE->set_url('/enrol/waitlist/edit.php', array('courseid' => $course->id, 'id' => $instanceid));

$PAGE->set_pagelayout('admin');



$return = new moodle_url('/enrol/instances.php', array('id' => $course->id));

if (!enrol_is_enabled('waitlist')) {

    redirect($return);

}



$plugin = enrol_get_plugin('waitlist');



if ($instanceid) {

    $instance = $DB->get_record('enrol', array('courseid' => $course->id, 'enrol' => 'waitlist', 'id' => $instanceid), '*', MUST_EXIST);

} else {

    require_capability('moodle/course:enrolconfig', $context);

    // no instance yet, we have to add new instance

    navigation_node::override_active_url(new moodle_url('/enrol/instances.php', array('id' => $course->id)));

    $instance = new stdClass();

    $instance->id       = null;

    $instance->courseid = $course->id;

}



$mform = new enrol_waitlist_edit_form(null, array($instance, $plugin, $context));



if ($mform->is_cancelled()) {

    redirect($return);



} else if ($data = $mform->get_data()) {

    if ($instance->id) {

        $instance->status         = $data->status;

        $instance->name           = $data->name;

        $instance->password       = $data->password;

        $instance->customint1     = $data->customint1;

        $instance->customint2     = $data->customint2;

        $instance->customint3     = $data->customint3;

        $instance->customint4     = $data->customint4;

        $instance->customchar1     = $data->customchar1;

        $instance->customchar2     = $data->customchar2;

        $instance->customtext1    = $data->customtext1;

        $instance->roleid         = $data->roleid;

        $instance->enrolperiod    = $data->enrolperiod;

        $instance->enrolstartdate = $data->enrolstartdate;

        $instance->enrolenddate   = $data->enrolenddate;

        $instance->timemodified   = time();

        $DB->update_record('enrol', $instance);

    } else {

        $fields = array('status' => $data->status, 'name' => $data->name, 'password' => $data->password, 'customint1' => $data->customint1, 'customint2' => $data->customint2,

                        'customint3' => $data->customint3, 'customint4' => $data->customint4, 'customtext1' => $data->customtext1,

                        'roleid' => $data->roleid, 'enrolperiod' => $data->enrolperiod, 'enrolstartdate' => $data->enrolstartdate, 'enrolenddate' => $data->enrolenddate,'customchar1' => $data->customchar1,'customchar2' => $data->customchar2);

        $plugin->add_instance($course, $fields);

    }

    // cumstom fields process
    $data = data_submitted();
    $data = (array)$data;
    
    $fields = array();
    foreach($data as $key => $val){
        if(substr($key,0,13) == 'custom_field_'){
            $shortname = str_replace(substr($key,0,13),'',$key);
            $res = $DB->get_record("waitlist_info_field", array("shortname" => $shortname));

            if($res){
                $fields[$res->id] = $val;
            }
        }
    }

    if(count($fields)){
        $DB->delete_records('waitlist_info_data', array('course_id' => $courseid));

        foreach($fields as $k => $v){
            $fieldData = new stdClass();
            $fieldData->course_id = $courseid;
            $fieldData->fieldid = $k;
            $fieldData->data = $v;
            $DB->insert_record('waitlist_info_data', $fieldData);
        }
    }else{
        $DB->delete_records('waitlist_info_data', array('course_id' => $courseid));
    }

    redirect($return);

}



$PAGE->set_heading($course->fullname);

$PAGE->set_title(get_string('pluginname', 'enrol_waitlist'));



echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('pluginname', 'enrol_waitlist'));

$mform->display();

echo $OUTPUT->footer();

