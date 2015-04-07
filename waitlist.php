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
class waitlist{
	public function add_wait_list($instanceid, $userid, $roleid, $timestart, $timeend){
		global $DB;

		$waitlist = new stdClass();
		$waitlist->userid = $userid;
		$waitlist->instanceid = $instanceid;
		$waitlist->roleid = $roleid;
		$waitlist->timestart = $timestart;
		$waitlist->timeend = $timeend;
		$waitlist->timecreated = time();

		return $DB->insert_record('user_enrol_waitlist',$waitlist);
	}

	public function vaildate_wait_list($instanceid, $userid){
		global $DB;
		global $CFG;

		$res = $DB->get_records_sql("select * from ".$CFG->prefix."user_enrol_waitlist where instanceid=".$instanceid." and userid=".$userid);
		if(count($res)){
			return false;
		}else{
			return true;
		}
	}

	public function get_wait_list(){
		global $DB;
		global $CFG;
		
		return $DB->get_records_sql("select * from ".$CFG->prefix."user_enrol_waitlist");
	}
}