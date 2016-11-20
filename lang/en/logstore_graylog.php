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
 * Graylog/GELF log store plugin language definitions
 *
 * @package    logstore_graylog
 * @copyright  2016, Binoj David <dbinoj@gmail.com>
 * @author     Binoj David, https://www.dbinoj.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Graylog Logstore';
$string['pluginname_desc'] = 'A logstore plugin to ship logs to Graylog or any other GELF compatible logstores.';

$string['hostname'] = 'Graylog Input Hostname';
$string['port'] = 'Input Port';
$string['transport'] = 'Input Transport Type';
$string['udp'] = 'GELF UDP';
$string['tcp'] = 'GELF TCP';
$string['mode'] = 'Export mode';
$string['realtime'] = 'Realtime';
$string['background'] = 'Background';

$string['taskexport'] = 'Export to Graylog';

$string['reporttitle'] = 'Graylog Logstore Status';
$string['repstatus'] = 'Moodle->Graylog Status';
$string['nodestatus'] = 'Graylog Node Processing Status';
$string['never'] = 'Never';
$string['lastran'] = 'Last ran';
$string['progress'] = 'Progress';