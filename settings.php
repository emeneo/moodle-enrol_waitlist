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
defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    //--- general settings -----------------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_waitlist_settings', '', get_string('pluginname_desc', 'enrol_waitlist')));

    $settings->add(new admin_setting_configcheckbox('enrol_waitlist/requirepassword',
        get_string('requirepassword', 'enrol_waitlist'), get_string('requirepassword_desc', 'enrol_waitlist'), 0));

    $settings->add(new admin_setting_configcheckbox('enrol_waitlist/usepasswordpolicy',
        get_string('usepasswordpolicy', 'enrol_waitlist'), get_string('usepasswordpolicy_desc', 'enrol_waitlist'), 0));

    $settings->add(new admin_setting_configcheckbox('enrol_waitlist/showhint',
        get_string('showhint', 'enrol_waitlist'), get_string('showhint_desc', 'enrol_waitlist'), 0));

    //--- enrol instance defaults ----------------------------------------------------------------------------
    $settings->add(new admin_setting_heading('enrol_waitlist_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $settings->add(new admin_setting_configcheckbox('enrol_waitlist/defaultenrol',
        get_string('defaultenrol', 'enrol'), get_string('defaultenrol_desc', 'enrol'), 1));

    $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                     ENROL_INSTANCE_DISABLED => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_waitlist/status',
        get_string('status', 'enrol_waitlist'), get_string('status_desc', 'enrol_waitlist'), ENROL_INSTANCE_DISABLED, $options));

    $options = array(1  => get_string('yes'),
                     0 => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_waitlist/groupkey',
        get_string('groupkey', 'enrol_waitlist'), get_string('groupkey_desc', 'enrol_waitlist'), 0, $options));

    if (!during_initial_install()) {
        //$options = get_default_enrol_roles(get_context_instance(CONTEXT_SYSTEM));
		$options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_waitlist/roleid',
            get_string('defaultrole', 'enrol_waitlist'), get_string('defaultrole_desc', 'enrol_waitlist'), $student->id, $options));
    }

    $settings->add(new admin_setting_configtext('enrol_waitlist/enrolperiod',
        get_string('enrolperiod', 'enrol_waitlist'), get_string('enrolperiod_desc', 'enrol_waitlist'), 0, PARAM_INT));
    
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
    $settings->add(new admin_setting_configselect('enrol_waitlist/longtimenosee',
        get_string('longtimenosee', 'enrol_waitlist'), get_string('longtimenosee_help', 'enrol_waitlist'), 0, $options));

    $settings->add(new admin_setting_configtext('enrol_waitlist/maxenrolled',
        get_string('maxenrolled', 'enrol_waitlist'), get_string('maxenrolled_help', 'enrol_waitlist'), 0, PARAM_INT));

    $settings->add(new admin_setting_configcheckbox('enrol_waitlist/sendcoursewelcomemessage',
        get_string('sendcoursewelcomemessage', 'enrol_waitlist'), get_string('sendcoursewelcomemessage_help', 'enrol_waitlist'), 1));

    //$settings->add(new admin_externalpage('local_course_fields','Waitlist enrolment custom fields',new moodle_url('/enrol/waitlist/profile/index.php')));
}

$ADMIN->add('enrolments', new admin_externalpage('enrol_waitlist', 'Waitlist enrolment custom fields', $CFG->wwwroot.'/enrol/waitlist/profile/index.php'));