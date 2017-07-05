<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

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