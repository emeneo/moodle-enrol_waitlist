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

require('../../config.php');
require_once("$CFG->dirroot/enrol/waitlist/library.php");
require_once("$CFG->dirroot/enrol/waitlist/users_forms.php");
require_once("$CFG->dirroot/enrol/renderer.php");


class waitlist_renderer extends core_enrol_renderer {

    public function render_course_enrolment_users_table(course_enrolment_users_table $table,
            moodleform $mform) {

        $table->initialise_javascript();

        // Added for the Bootstrap theme. Make this table responsive.
        $table->attributes['class'] .= ' table table-responsive';

        $buttons = $table->get_manual_enrol_buttons();
        $buttonhtml = '';
        if (count((array)$buttons) > 0) {
            $buttonhtml .= html_writer::start_tag('div', array('class' => 'enrol_user_buttons'));
            foreach ($buttons as $button) {
                $buttonhtml .= $this->render($button);
            }
            $buttonhtml .= html_writer::end_tag('div');
        }

        $content = '';
        if (!empty($buttonhtml)) {
            $content .= $buttonhtml;
        }
        $content .= $mform->render();

        $content .= $this->output->render($table->get_paging_bar());

        // Check if the table has any bulk operations. If it does we want to wrap the table in a
        // form so that we can capture and perform any required bulk operations.
        if ($table->has_bulk_user_enrolment_operations()) {
            $content .= html_writer::start_tag('form', array('action' => new moodle_url('/enrol/bulkchange.php'),
                                               'method' => 'post'));
            foreach ($table->get_combined_url_params() as $key => $value) {
                if ($key == 'action') {
                    continue;
                }
                $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => $key, 'value' => $value));
            }
            $content .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'action', 'value' => 'bulkchange'));
            $content .= html_writer::table($table);
            $content .= html_writer::start_tag('div', array('class' => 'singleselect bulkuserop'));
            $content .= html_writer::start_tag('select', array('name' => 'bulkuserop'));
            $content .= html_writer::tag('option', get_string('withselectedusers', 'enrol'), array('value' => ''));
            foreach ($table->get_bulk_user_enrolment_operations() as $operation) {
                $content .= html_writer::tag('option', $operation->get_title(), array('value' => $operation->get_identifier()));
            }
            $content .= html_writer::end_tag('select');
            $content .= html_writer::empty_tag('input', array('type' => 'submit', 'value' => get_string('go')));
            $content .= html_writer::end_tag('div');

            $content .= html_writer::end_tag('form');
        } else {
            // Added for the Bootstrap theme, a no-overflow wrapper.
            $content .= html_writer::start_tag('div', array('class' => 'no-overflow'));
            $content .= html_writer::table($table);
            $content .= html_writer::end_tag('div');
        }
        $content .= $this->output->render($table->get_paging_bar());
        if (!empty($buttonhtml)) {
            $content .= $buttonhtml;
        }
        return $content;
    }
}

$id       = required_param('id', PARAM_INT);
$filter  = optional_param('ifilter', 0, PARAM_INT);
$search  = optional_param('search', '', PARAM_RAW);
$role    = optional_param('role', 0, PARAM_INT);
$fgroup  = optional_param('filtergroup', 0, PARAM_INT);
$status  = optional_param('status', -1, PARAM_INT);

if (optional_param('resetbutton', '', PARAM_RAW) !== '') {
    redirect('users.php?id=' . $id);
}

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
$context = context_course::instance($course->id);

$res = $DB->get_record_sql("select id from ".$CFG->prefix."enrol where courseid=".$id." and enrol='waitlist'");
$instance = $res->id;

if ($course->id == SITEID) {
    redirect(new moodle_url('/'));
}

require_login($course);
// require_capability('moodle/course:enrolreview', $context);
$PAGE->set_pagelayout('admin');

$manager = new course_enrolment_manager($PAGE, $course, $filter, $role, $search, $fgroup, $status);
$table = new course_enrolment_users_table($manager, $PAGE);
$PAGE->set_url('/enrol/waitlist/users.php', $manager->get_url_params() + $table->get_url_params());
navigation_node::override_active_url(new moodle_url('/enrol/waitlist/users.php', array('id' => $id)));

$renderer = new waitlist_renderer($PAGE, null);
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

// $canassign = has_capability('moodle/role:assign', $manager->get_context());
$users = $manager->get_users_for_display($manager, $table->sort, $table->sortdirection, $table->page, $table->perpage, $instance);
foreach ($users as $userid => &$user) {
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