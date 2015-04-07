<?php
require('../../config.php');
require_once("$CFG->dirroot/enrol/waitlist/library.php");
require_once("$CFG->dirroot/enrol/waitlist/users_forms.php");
require_once("$CFG->dirroot/enrol/renderer.php");

$id		  = required_param('id', PARAM_INT);
$filter  = optional_param('ifilter', 0, PARAM_INT);
$search  = optional_param('search', '', PARAM_RAW);
$role    = optional_param('role', 0, PARAM_INT);
$fgroup  = optional_param('filtergroup', 0, PARAM_INT);
$status  = optional_param('status', -1, PARAM_INT);

if (optional_param('resetbutton', '', PARAM_RAW) !== '') {
    redirect('users.php?id=' . $id);
}

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);
$context = context_course::instance($course->id);

$res = $DB->get_record_sql("select id from ".$CFG->prefix."enrol where courseid=".$id." and enrol='waitlist'");
$instance = $res->id;

if ($course->id == SITEID) {
    redirect(new moodle_url('/'));
}

require_login($course);
//require_capability('moodle/course:enrolreview', $context);
$PAGE->set_pagelayout('admin');

$manager = new course_enrolment_manager($PAGE, $course, $filter, $role, $search, $fgroup, $status);
$table = new course_enrolment_users_table($manager, $PAGE);
$PAGE->set_url('/enrol/waitlist/users.php', $manager->get_url_params()+$table->get_url_params());
navigation_node::override_active_url(new moodle_url('/enrol/waitlist/users.php', array('id' => $id)));

$renderer = $PAGE->get_renderer('core_enrol');
$userdetails = array (
    'picture' => false,
    'firstname' => get_string('firstname'),
    'lastname' => get_string('lastname'),
);
$extrafields = get_extra_user_fields($context);
foreach ($extrafields as $field) {
    $userdetails[$field] = get_user_field_name($field);
}

$fields = array(
    'userdetails' => $userdetails,
);


$filterform = new enrol_users_filter_form('users.php', array('manager' => $manager, 'id' => $id),
        'get', '', array('id' => 'filterform'));
$filterform->set_data(array('search' => $search));

$table->set_fields($fields, $renderer);

//$canassign = has_capability('moodle/role:assign', $manager->get_context());
$users = $manager->get_users_for_display($manager, $table->sort, $table->sortdirection, $table->page, $table->perpage, $instance);
foreach ($users as $userid=>&$user) {
    $user['picture'] = $OUTPUT->render($user['picture']);
    $user['email'] = '<a href=mailto:'.$user['email'].'>'.$user['email'].'</a>';
}
$table->set_total_users($manager->get_total_users());
$table->set_users($users);

$PAGE->set_title($PAGE->course->fullname);
$PAGE->set_heading($PAGE->title);

echo $OUTPUT->header();
echo '<style>.mform{background-color:#f2f2f2;} .mform fieldset div {margin: 0;margin-top: 0;display: inline;clear: none;padding: 1px;}.mform .fitem .felement {border-width: 0;width: 80%;margin-left:0px;}.mform .fitem .fitemtitle {width: 5%;text-align: right;float: left;}</style>';
echo $OUTPUT->heading(get_string('users_on_waitlist', 'enrol_waitlist'));
echo $renderer->render_course_enrolment_users_table($table, $filterform);
echo $OUTPUT->footer();