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
class enrol_waitlist_plugin extends enrol_plugin {

    /**
     * Returns optional enrolment information icons.
     *
     * This is used in course list for quick overview of enrolment options.
     *
     * We are not using single instance parameter because sometimes
     * we might want to prevent icon repetition when multiple instances
     * of one type exist. One instance may also produce several icons.
     *
     * @param array $instances all enrol instances of this type in one course
     * @return array of pix_icon
     */
    public function get_info_icons(array $instances) {
        $key = false;
        $nokey = false;
        foreach ($instances as $instance) {
            if ($instance->password or $instance->customint1) {
                $key = true;
            } else {
                $nokey = true;
            }
        }
        $icons = array();
        if ($nokey) {
            $icons[] = new pix_icon('withoutkey', get_string('pluginname', 'enrol_waitlist'), 'enrol_waitlist');
        }
        if ($key) {
            $icons[] = new pix_icon('withkey', get_string('pluginname', 'enrol_waitlist'), 'enrol_waitlist');
        }
        return $icons;
    }

    /**
     * Returns localised name of enrol instance
     *
     * @param object $instance (null is accepted too)
     * @return string
     */
    public function get_instance_name($instance) {
        global $DB;

        if (empty($instance->name)) {
            if (!empty($instance->roleid) and $role = $DB->get_record('role', array('id'=>$instance->roleid))) {
               // $role = ' (' . role_get_name($role, get_context_instance(CONTEXT_COURSE, $instance->courseid)) . ')';
				$role = ' (' . role_get_name($role, context_course::instance($instance->courseid)) . ')';
            } else {
                $role = '';
            }
            $enrol = $this->get_name();
            return get_string('pluginname', 'enrol_'.$enrol) . $role;
        } else {
            return format_string($instance->name);
        }
    }

    public function roles_protected() {
        // users may tweak the roles later
        return false;
    }

    public function allow_unenrol(stdClass $instance) {
        // users with unenrol cap may unenrol other users manually manually
        return true;
    }

    public function allow_manage(stdClass $instance) {
        // users with manage cap may tweak period and status
        return true;
    }

    public function show_enrolme_link(stdClass $instance) {
        return ($instance->status == ENROL_INSTANCE_ENABLED);
    }

    /**
     * Sets up navigation entries.
     *
     * @param object $instance
     * @return void
     */
	 /*
    public function add_course_navigation($instancesnode, stdClass $instance) {
        if ($instance->enrol !== 'waitlist') {
             throw new coding_exception('Invalid enrol instance type!');
        }

        $context = get_context_instance(CONTEXT_COURSE, $instance->courseid);
        if (has_capability('enrol/waitlist:config', $context)) {
            $managelink = new moodle_url('/enrol/waitlist/edit.php', array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $instancesnode->add($this->get_instance_name($instance), $managelink, navigation_node::TYPE_SETTING);
        }
    }
	*/
	
	/**
     * Sets up navigation entries.
     *
     * @param stdClass $instancesnode
     * @param stdClass $instance
     * @return void
     */
    public function add_course_navigation($instancesnode, stdClass $instance) {
        if ($instance->enrol !== 'waitlist') {
             throw new coding_exception('Invalid enrol instance type!');
        }

        $context = context_course::instance($instance->courseid);
        if (has_capability('enrol/waitlist:config', $context)) {
            $managelink = new moodle_url('/enrol/waitlist/edit.php', array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $instancesnode->add($this->get_instance_name($instance), $managelink, navigation_node::TYPE_SETTING);
        }
    }
	
	
	/*
	public function unenrol_user($enrolId){
		global $DB;
		global $CFG;

		$enrolment = $DB->get_record('user_enrolments', array('id'=>$enrolId), '*', MUST_EXIST);
		$instance = $DB->get_record('enrol', array('id'=>$enrolment->enrolid), '*', MUST_EXIST);

		require_once("$CFG->dirroot/enrol/waitlist/waitlist.php");
		$waitlist = new waitlist();
		$res = $waitlist->add_wait_list($instance->id, $enrolment->userid, $instance->roleid, $enrolment->timestart, $enrolment->timeend);
		if($res){
			$DB->delete_records('user_enrolments',array('id'=>$enrolId));
		}
	}
	*/

    /**
     * Returns edit icons for the page with list of instances
     * @param stdClass $instance
     * @return array
     */
    public function get_action_icons(stdClass $instance) {
        global $OUTPUT;

        if ($instance->enrol !== 'waitlist') {
            throw new coding_exception('invalid enrol instance!');
        }
        //$context = get_context_instance(CONTEXT_COURSE, $instance->courseid);
		$context = context_course::instance($instance->courseid);

        $icons = array();

		if (has_capability('enrol/waitlist:config', $context)) {
            $managelink = new moodle_url("/enrol/waitlist/enroluser.php", array('enrolid'=>$instance->id));
			$icons[] = $OUTPUT->action_icon($managelink, new pix_icon('t/enrolusers', get_string('enrolusers', 'enrol_waitlist'), 'core', array('class'=>'iconsmall')));
        }

        if (has_capability('enrol/waitlist:config', $context)) {
            $editlink = new moodle_url("/enrol/waitlist/edit.php", array('courseid'=>$instance->courseid, 'id'=>$instance->id));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon('t/edit', get_string('edit'), 'core', array('class'=>'iconsmall')));
        }

		if (has_capability('enrol/waitlist:config', $context)) {
            $editlink = new moodle_url("/enrol/waitlist/users.php", array('id'=>$instance->courseid));
            $icons[] = $OUTPUT->action_icon($editlink, new pix_icon('i/switchrole', get_string('waitlisted_users','enrol_waitlist'), 'core', array('class'=>'iconsmall')));
        }

        return $icons;
    }

    /**
     * Returns link to page which may be used to add new instance of enrolment plugin in course.
     * @param int $courseid
     * @return moodle_url page url
     */
    public function get_newinstance_link($courseid) {
        //$context = get_context_instance(CONTEXT_COURSE, $courseid, MUST_EXIST);
		$context = context_course::instance($courseid);

        if (!has_capability('moodle/course:enrolconfig', $context) or !has_capability('enrol/waitlist:config', $context)) {
            return NULL;
        }
        // multiple instances supported - different roles with different password
        return new moodle_url('/enrol/waitlist/edit.php', array('courseid'=>$courseid));
    }

    /**
     * Creates course enrol form, checks if form submitted
     * and enrols user if necessary. It can also redirect.
     *
     * @param stdClass $instance
     * @return string html text, usually a form in a text box
     */
    public function enrol_page_hook(stdClass $instance) {
        global $CFG, $OUTPUT, $SESSION, $USER, $DB;

        if (isguestuser()) {
            // can not enrol guest!!
            return null;
        }
        if ($DB->record_exists('user_enrolments', array('userid'=>$USER->id, 'enrolid'=>$instance->id))) {
            //TODO: maybe we should tell them they are already enrolled, but can not access the course
            return null;
			
        }

		if($DB->record_exists('user_enrol_waitlist', array('userid'=>$USER->id, 'instanceid'=>$instance->id))){
			return $OUTPUT->notification(get_string('waitlistinfo', 'enrol_waitlist'));
		}

        if ($instance->enrolstartdate != 0 and $instance->enrolstartdate > time()) {
            //TODO: inform that we can not enrol yet
            return null;
        }
		/*
        if ($instance->enrolenddate != 0 and $instance->enrolenddate < time()) {
            //TODO: inform that enrolment is not possible any more
            return null;
        }
		
        if ($instance->customint3 > 0) {
            // max enrol limit specified
            $count = $DB->count_records('user_enrolments', array('enrolid'=>$instance->id));
            if ($count >= $instance->customint3) {
                // bad luck, no more waitlist enrolments here
                return $OUTPUT->notification(get_string('maxenrolledreached', 'enrol_waitlist'));
            }
        }
		*/
        require_once("$CFG->dirroot/enrol/waitlist/locallib.php");
        require_once("$CFG->dirroot/group/lib.php");
		require_once("$CFG->dirroot/enrol/waitlist/waitlist.php");

		$waitlist = new waitlist();
		
		/*
		if(!$waitlist->vaildate_wait_list($instance->id,$USER->id)){
			return $OUTPUT->notification(get_string('waitlistinfo', 'enrol_waitlist'));
		}
		*/

        $form = new enrol_waitlist_enrol_form(NULL, $instance);
        $instanceid = optional_param('instance', 0, PARAM_INT);

        if ($instance->id == $instanceid) {
            if ($data = $form->get_data()) {
                $enrol = enrol_get_plugin('waitlist');
                $timestart = time();
                if ($instance->enrolperiod) {
                    $timeend = $timestart + $instance->enrolperiod;
                } else {
                    $timeend = 0;
                }

                //$this->enrol_user($instance, $USER->id, $instance->roleid, $timestart, $timeend);
				$enroledCount = $DB->count_records('user_enrolments', array('enrolid'=>$instance->id));

				$canEnrol = false;
                if($instance->customint3 == 0){
                    $canEnrol = true;
                }elseif($enroledCount<$instance->customint3){
                    $canEnrol = true;
                    if($instance->enrolenddate){
                        if(time()>$instance->enrolenddate){
                            $canEnrol = false;
                        }
                    }
                }

				if($canEnrol){
					$this->enrol_user($instance, $USER->id, $instance->roleid, $timestart, $timeend);
					if ($instance->customint4) {
						$user =  $DB->get_record_sql("select * from ".$CFG->prefix."user where id=".$USER->id);
						$this->email_welcome_message($instance, $USER);
					}
				}else{
					$waitlist->add_wait_list($instance->id, $USER->id, $instance->roleid, $timestart, $timeend);
				}
                //add_to_log($instance->courseid, 'course', 'enrol', '../enrol/users.php?id='.$instance->courseid, $instance->courseid); //there should be userid somewhere!

                if ($instance->password and $instance->customint1 and $data->enrolpassword !== $instance->password) {
                    // it must be a group enrolment, let's assign group too
                    $groups = $DB->get_records('groups', array('courseid'=>$instance->courseid), 'id', 'id, enrolmentkey');
                    foreach ($groups as $group) {
                        if (empty($group->enrolmentkey)) {
                            continue;
                        }
                        if ($group->enrolmentkey === $data->enrolpassword) {
                            groups_add_member($group->id, $USER->id);
                            break;
                        }
                    }
                }
                // send welcome
                //if ($instance->customint4) {
                    //$this->email_welcome_message($instance, $USER);
                //}
				redirect("$CFG->wwwroot/course/view.php?id=$instance->courseid");
            }
        }

        ob_start();
        $form->display();
        $output = ob_get_clean();
        return $OUTPUT->box($output);
    }

    /**
     * Add new instance of enrol plugin with default settings.
     * @param object $course
     * @return int id of new instance
     */
    public function add_default_instance($course) {
        $fields = array('customint1'  => $this->get_config('groupkey'),
                        'customint2'  => $this->get_config('longtimenosee'),
                        'customint3'  => $this->get_config('maxenrolled'),
                        'customint4'  => $this->get_config('sendcoursewelcomemessage'),
                        'customchar1'  => $this->get_config('faculty'),
                        'enrolperiod' => $this->get_config('enrolperiod', 0),
                        'status'      => $this->get_config('status'),
                        'roleid'      => $this->get_config('roleid', 0));

        if ($this->get_config('requirepassword')) {
            $fields['password'] = generate_password(20);
        }

        return $this->add_instance($course, $fields);
    }

    /**
     * Send welcome email to specified user
     *
     * @param object $instance
     * @param object $user user record
     * @return void
     */
    protected function email_welcome_message($instance, $user) {
        global $CFG, $DB;

        $course = $DB->get_record('course', array('id'=>$instance->courseid), '*', MUST_EXIST);

        $a = new stdClass();
        $a->coursename = format_string($course->fullname);
        $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id&course=$course->id";
		$a->summary = $course->summary;
		$a->startdate = '';
		if($course->startdate != 0){
			$a->startdate = date('Y-m-d',$course->startdate);
		}

        if (trim($instance->customtext1) !== '') {
            $message = $instance->customtext1;
            $message = str_replace('{$a->coursename}', $a->coursename, $message);
            $message = str_replace('{$a->profileurl}', $a->profileurl, $message);
			$message = str_replace('{$a->summary}', $a->summary, $message);
			$message = str_replace('{$a->startdate}', $a->startdate, $message);
        } else {
            $message = get_string('welcometocoursetext', 'enrol_waitlist', $a);
        }

        $subject = get_string('welcometocourse', 'enrol_waitlist', format_string($course->fullname));

        //$context = get_context_instance(CONTEXT_COURSE, $course->id);
		$context = context_course::instance($course->id);
        $rusers = array();
        if (!empty($CFG->coursecontact)) {
            $croles = explode(',', $CFG->coursecontact);
            $rusers = get_role_users($croles[0], $context, true, '', 'r.sortorder ASC, u.lastname ASC');
        }
        if ($rusers) {
            $contact = reset($rusers);
        } else {
            $contact = get_admin();
        }

        //directly emailing welcome message rather than using messaging
        email_to_user($user, $contact, $subject, '',$message);
    }

    /**
     * Enrol waitlist cron support
     * @return void
     */
    public function cron() {
        global $DB;

        if (!enrol_is_enabled('waitlist')) {
            return;
        }

        $plugin = enrol_get_plugin('waitlist');

        $now = time();

        //note: the logic of waitlist enrolment guarantees that user logged in at least once (=== u.lastaccess set)
        //      and that user accessed course at least once too (=== user_lastaccess record exists)

        // first deal with users that did not log in for a really long time
        $sql = "SELECT e.*, ue.userid
                  FROM {user_enrolments} ue
                  JOIN {enrol} e ON (e.id = ue.enrolid AND e.enrol = 'waitlist' AND e.customint2 > 0)
                  JOIN {user} u ON u.id = ue.userid
                 WHERE :now - u.lastaccess > e.customint2";
        $rs = $DB->get_recordset_sql($sql, array('now'=>$now));
        foreach ($rs as $instance) {
            $userid = $instance->userid;
            unset($instance->userid);
            $plugin->unenrol_user($instance, $userid);
            mtrace("unenrolling user $userid from course $instance->courseid as they have did not log in for $instance->customint2 days");
        }
        $rs->close();

        // now unenrol from course user did not visit for a long time
        $sql = "SELECT e.*, ue.userid
                  FROM {user_enrolments} ue
                  JOIN {enrol} e ON (e.id = ue.enrolid AND e.enrol = 'waitlist' AND e.customint2 > 0)
                  JOIN {user_lastaccess} ul ON (ul.userid = ue.userid AND ul.courseid = e.courseid)
                 WHERE :now - ul.timeaccess > e.customint2";
        $rs = $DB->get_recordset_sql($sql, array('now'=>$now));
        foreach ($rs as $instance) {
            $userid = $instance->userid;
            unset($instance->userid);
            $plugin->unenrol_user($instance, $userid);
            mtrace("unenrolling user $userid from course $instance->courseid as they have did not access course for $instance->customint2 days");
        }
        $rs->close();

		//wait list
		$this->process_wait_list();

        flush();
    }

	public function get_user_enrolment_actions(course_enrolment_manager $manager, $ue) {
        $actions = array();
        $context = $manager->get_context();
        $instance = $ue->enrolmentinstance;
        $params = $manager->get_moodlepage()->url->params();
        $params['ue'] = $ue->id;
        if ($this->allow_unenrol($instance) && has_capability("enrol/self:unenrol", $context)) {
            $url = new moodle_url('/enrol/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/delete', ''), get_string('unenrol', 'enrol'), $url, array('class'=>'unenrollink', 'rel'=>$ue->id));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/self:manage", $context)) {
            $url = new moodle_url('/enrol/self/editenrolment.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/edit', ''), get_string('edit'), $url, array('class'=>'editenrollink', 'rel'=>$ue->id));
        }
        return $actions;
    }

/**
 * Is it possible to hide/show enrol instance via standard UI?
 *
 * @param stdClass $instance
 * @return bool
 */
public function can_hide_show_instance($instance) {
    $context = context_course::instance($instance->courseid);
    return has_capability('enrol/waitlist:config', $context);
}

/**
 * Is it possible to delete enrol instance via standard UI?
 *
 * @param stdClass $instance
 * @return bool
 */
public function can_delete_instance($instance) {
    $context = context_course::instance($instance->courseid);
    return has_capability('enrol/waitlist:config', $context);
}
	
	//cron process waitlist - 2012-11-01
	public function process_wait_list(){
		global $DB;
		global $CFG;
		require_once("$CFG->dirroot/enrol/waitlist/waitlist.php");
		$waitlist = new waitlist();

		$rows = $waitlist->get_wait_list();
		foreach($rows as $row){
			$userCount = $DB->count_records('user', array('id'=>$row->userid,'deleted'=>0));
			if(!$userCount){
				$DB->delete_records('user_enrol_waitlist',array('id'=>$row->id));
				continue;
			}

			$instanceCount = $DB->count_records('enrol', array('id'=>$row->instanceid));
			if(!$instanceCount){
				$DB->delete_records('user_enrol_waitlist',array('id'=>$row->id));
				continue;
			}
			$instance = $DB->get_record_sql("select * from ".$CFG->prefix."enrol where id=".$row->instanceid);

			if($instance){
				if(!$instance->id){
					continue;
				}
				$enroledCount = $DB->count_records('user_enrolments', array('enrolid'=>$instance->id));
				if($enroledCount<$instance->customint3){
					if($instance->enrolenddate){
						if(time()>$instance->enrolenddate){
							continue;
						}
					}
					$this->enrol_user($instance, $row->userid, $row->roleid, $row->timestart, $row->timeend);
					if ($instance->customint4) {
						$user =  $DB->get_record_sql("select * from ".$CFG->prefix."user where id=".$row->userid);
						$this->email_welcome_message($instance, $user);
					}
					$DB->delete_records('user_enrol_waitlist',array('id'=>$row->id));
				}
			}
		}
	}
}

/**
 * Indicates API features that the enrol plugin supports.
 *
 * @param string $feature
 * @return mixed True if yes (some features may use other values)
 */
function enrol_waitlist_supports($feature) {
    switch($feature) {
        case ENROL_RESTORE_TYPE: return ENROL_RESTORE_EXACT;

        default: return null;
    }
}

