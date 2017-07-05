<?php
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