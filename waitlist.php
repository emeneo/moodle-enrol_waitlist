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

class waitlist{
    public function add_wait_list($instanceid, $userid, $roleid, $timestart, $timeend) {
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

    public function vaildate_wait_list($instanceid, $userid) {
        global $DB;
        global $CFG;

        $res = $DB->get_records_sql("select * from ".$CFG->prefix."user_enrol_waitlist where instanceid=".$instanceid." and userid=".$userid);
        if(count($res)){
            return false;
        }else{
            return true;
        }
    }

    public function get_wait_list() {
        global $DB;
        global $CFG;

        return $DB->get_records_sql("select * from ".$CFG->prefix."user_enrol_waitlist");
    }
}