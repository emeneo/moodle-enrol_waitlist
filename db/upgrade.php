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

defined('MOODLE_INTERNAL') || die();

function xmldb_enrol_waitlist_upgrade($oldversion) {
    global $DB;
    $result = true;

    if ($result && $oldversion < 2017060201) {
        $sql = 'CREATE TABLE mdl_waitlist_info_field (id int(10) NOT NULL AUTO_INCREMENT,shortname char(255) NOT NULL,name text NOT NULL,datatype char(255) NOT NULL,description text,descriptionformat int(2) NOT NULL DEFAULT 0,sortorder int(10) NOT NULL DEFAULT 0,required int(2) NOT NULL DEFAULT 0,forceunique int(2) NOT NULL DEFAULT 0,defaultdata text,defaultdataformat int(2) NOT NULL DEFAULT 0,param1 text,param2 text,param3 text,param4 text,param5 text,PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        $DB->execute($sql);

        $sql = 'CREATE TABLE mdl_waitlist_info_data (id int(10) NOT NULL AUTO_INCREMENT,course_id int(10) NOT NULL,fieldid int(10) NOT NULL,data text NOT NULL,dataformat int(2) DEFAULT NULL,PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8';
        $DB->execute($sql);
    }

    return $result;
}