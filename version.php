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

$plugin->version  = 2018062600;   // The current module version (Date: YYYYMMDDXX)
$plugin->requires = 2011033005;   // Requires at least this Moodle version
$plugin->maturity = MATURITY_STABLE;
$plugin->release = 'Course Enrol Waitlist Plugin Version 3.5-a';
$plugin->component = 'enrol_waitlist'; // Full name of the plugin (used for diagnostics)
$plugin->cron = 180;
