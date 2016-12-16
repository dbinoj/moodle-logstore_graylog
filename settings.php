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
 * Graylog/GELF log store plugin settings
 *
 * @package    logstore_graylog
 * @copyright  2016, Binoj David <dbinoj@gmail.com>
 * @author     Binoj David, https://www.dbinoj.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $healthurl = new moodle_url('/admin/tool/log/store/graylog/index.php', array('sesskey' => sesskey()));
    $ADMIN->add('reports', new admin_externalpage(
        'logstoregrayloghealth',
        new lang_string('reporttitle', 'logstore_graylog'),
        $healthurl,
        'moodle/site:config'
    ));

    $settings->add(new admin_setting_configtext(
        'logstore_graylog/hostname',
        new lang_string('hostname', 'logstore_graylog'),
        '', 'localhost', PARAM_HOST
    ));

    $settings->add(new admin_setting_configtext(
        'logstore_graylog/port',
        new lang_string('port', 'logstore_graylog'),
        '', '12201', PARAM_INT
    ));

    $settings->add(new admin_setting_configselect(
        'logstore_graylog/transport',
        new lang_string('transport', 'logstore_graylog'),
        '', 'udp', array(
        'udp' => new lang_string('udp', 'logstore_graylog'),
        'tcp' => new lang_string('tcp', 'logstore_graylog')
    )));

    $settings->add(new admin_setting_configtext(
        'logstore_graylog/tcptimeout',
        new lang_string('tcptimeout', 'logstore_graylog'),
        new lang_string('tcptimeout_desc', 'logstore_graylog'), '30', PARAM_INT
    ));

    $settings->add(new admin_setting_configselect(
        'logstore_graylog/mode',
        new lang_string('mode', 'logstore_graylog'),
        '', 'realtime', array(
        'realtime' => new lang_string('realtime', 'logstore_graylog'),
        'background' => new lang_string('background', 'logstore_graylog')
    )));
}