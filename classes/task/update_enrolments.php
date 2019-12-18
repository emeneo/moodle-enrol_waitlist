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
 * A scheduled task to send custom messages with waitlist.
 *
 * @package   enrol_waitlist
 * @author    Barbara Elias (barbara.elias@edaktik.at)
 * @copyright eDaktik GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace enrol_waitlist\task;

use coding_exception;

defined('MOODLE_INTERNAL') || die();

/**
 * A scheduled task to update waitlist enrolments.
 *
 * @package   enrol_waitlist
 * @author    Barbara Elias (barbara.elias@edaktik.at)
 * @copyright eDaktik GmbH
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class update_enrolments extends \core\task\scheduled_task {

    /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     * @throws coding_exception
     */
    public function get_name() {
        return get_string('task:update_enrolments', 'enrol_waitlist');
    }

    /**
     * Execute the task.
     *
     * @return bool true if everything is fine
     */
    public function execute() {
        $plugin = enrol_get_plugin('waitlist');

        if ($plugin === null){
            mtrace("plugin not active returning");
            return true;
        }

        $plugin->cron();

        return true;
    }
}