<?php

namespace enrol_waitlist\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\context;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\approved_userlist;

class provider implements \core_privacy\local\metadata\provider, \core_privacy\local\request\plugin\provider, \core_privacy\local\request\core_userlist_provider {
    
    public static function get_metadata(collection $collection) : collection {
        
        $collection->add_database_table(
            'user_enrol_waitlist',
            [
                'userid' => 'privacy:metadata:user_enrol_waitlist:userid'
            ],
            'privacy:metadata:user_enrol_waitlist'
            );
        
        return $collection;
    }
    
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $sql = "SELECT c.id
                  FROM {enrol_waitlist} ew
                  JOIN {context} c ON c.contextlevel = ? AND c.instanceid = ew.id
                 WHERE ew.userid = ?";
        $params = [CONTEXT_COURSE, $userid];
        
        $contextlist = new contextlist();
        $contextlist->set_component('enrol_waitlists');
        return $contextlist->add_from_sql($sql, $params);
    }
    
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();
        
        if (!$context instanceof \context_course) {
            return;
        }
        
        // Values of ep.receiver_email and ep.business are already normalised to lowercase characters by PayPal,
        // therefore there is no need to use LOWER() on them in the following query.
        $sql = "SELECT u.id
                  FROM {enrol_waitlist} ew
                  JOIN {enrol} e ON ew.instanceid = e.id
                  JOIN {user} u ON ew.userid = u.id
                 WHERE e.courseid = :courseid";
        $params = ['courseid' => $context->instanceid];
        
        $userlist->add_from_sql('id', $sql, $params);
    }
    
    public static function export_user_data(approved_contextlist $contextlist) {
        
        global $DB;
        
        if (empty($contextlist->count())) {
            return;
        }
        
        $user = $contextlist->get_user();
        
        list($contextsql, $contextparams) = $DB->get_in_or_equal($contextlist->get_contextids(), SQL_PARAMS_NAMED);
        
        $sql = "SELECT ew.*
                  FROM {enrol_waitlist} ew
                  JOIN {enrol} e ON ew.instanceid = e.id
                  JOIN {context} ctx ON e.courseid = ctx.instanceid AND ctx.contextlevel = :contextcourse
                  JOIN {user} u ON u.id = ep.userid
                 WHERE ctx.id {$contextsql} AND u.id = :userid
              ORDER BY e.courseid";
        
        $params = [
            'contextcourse' => CONTEXT_COURSE,
            'userid'        => $user->id,
            'emailuserid'   => $user->id,
        ];
        $params += $contextparams;
    }
    
    public static function delete_data_for_all_users_in_context(\context $context) {
        
        global $DB;
        
        if (!$context instanceof \context_course) {
            return;
        }
        
        $DB->delete_records('enrol_waitlist', array('instanceid' => $context->instanceid));
        
    }
    
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        
        global $DB;
        
        if (empty($contextlist->count())) {
            return;
        }
        
        $user = $contextlist->get_user();
        
        $contexts = $contextlist->get_contexts();
        $courseids = [];
        
        foreach ($contexts as $context) {
            if ($context instanceof \context_course) {
                $courseids[] = $context->instanceid;
            }
        }
        
        list($insql, $inparams) = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        
        $select = "userid = :userid AND instanceid $insql";
        $params = $inparams + ['userid' => $user->id];
        $DB->delete_records_select('enrol_waitlist', $select, $params);
        
    }
    
    public static function delete_data_for_users(approved_userlist $userlist) {
        
        global $DB;
        
        $context = $userlist->get_context();
        
        if ($context->contextlevel != CONTEXT_COURSE) {
            return;
        }
        
        $userids = $userlist->get_userids();
        
        list($usersql, $userparams) = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED);
        
        $params = ['instanceid' => $context->instanceid] + $userparams;
        
        $select = "instanceid = :instanceid AND userid $usersql";
        $DB->delete_records_select('enrol_waitlist', $select, $params);
        
    }
}