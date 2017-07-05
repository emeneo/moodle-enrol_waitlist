<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page
}

$toprow = array();
$toprow[] = new tabobject('define', new moodle_url('index.php'), get_string('manage::define', 'enrol_waitlist'));
//$toprow[] = new tabobject('assign', new moodle_url('assign.php'), get_string('manage::assign', 'local_course_fields'));
$tabs = array($toprow);

print_tabs($tabs, $currenttab);