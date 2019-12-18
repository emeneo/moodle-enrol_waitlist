<?php

defined('MOODLE_INTERNAL') || die();


$tasks = [
    [
        'classname' => 'enrol_waitlist\task\update_enrolments',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '*/3',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ],
];