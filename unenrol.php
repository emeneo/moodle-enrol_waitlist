<?php
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

$enrolid = required_param('enrolid', PARAM_INT);
$userid = required_param('userid', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

$enrolment = $DB->get_record('user_enrolments', array('id'=>$enrolid), '*', MUST_EXIST);
$instance = $DB->get_record('enrol', array('id'=>$enrolment->enrolid), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id'=>$instance->courseid), '*', MUST_EXIST);
$user = $DB->get_record('user', array('id'=>$userid), '*', MUST_EXIST);

require_login($course);

$plugin = enrol_get_plugin('waitlist');

// security defined inside following function
/*if (!$plugin->get_unenrolwaitlist_link($instance)) {
    redirect(new moodle_url('/course/view.php', array('id'=>$course->id)));
}*/

$PAGE->set_url('/enrol/waitlist/unenrol.php', array('enrolid'=>$enrolid,'userid'=>$userid));
$PAGE->set_title($plugin->get_instance_name($instance));

if ($confirm and confirm_sesskey()) {
    $plugin->unenrol_user($enrolid);
    //add_to_log($course->id, 'course', 'unenrol', '../enrol/users.php?id='.$course->id, $course->id); //there should be userid somewhere!
    redirect(new moodle_url('/index.php'));
}

echo $OUTPUT->header();
$yesurl = new moodle_url($PAGE->url, array('confirm'=>1, 'sesskey'=>sesskey()));
$nourl = new moodle_url('/course/view.php', array('id'=>$course->id));
$message = get_string('unenrolwaitlistconfirm', 'enrol_waitlist', $user->firstname.' '.$user->lastname.' from course '.format_string($course->fullname));
echo $OUTPUT->confirm($message, $yesurl, $nourl);
echo $OUTPUT->footer();
