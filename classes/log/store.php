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
 * Graylog/GELF log store plugin
 *
 * @package    logstore_graylog
 * @copyright  2016, Binoj David <dbinoj@gmail.com>
 * @author     Binoj David, https://www.dbinoj.com
 * @thanks     2016, Skylar Kelty <S.Kelty@kent.ac.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace logstore_graylog\log;

defined('MOODLE_INTERNAL') || die();

class store implements \tool_log\log\writer {
    use \tool_log\helper\store,
        \tool_log\helper\buffered_writer;

    /**
     * Constructor.
     * @param \tool_log\log\manager $manager
     * @throws \coding_exception
     */
    public function __construct(\tool_log\log\manager $manager) {
        $this->helper_setup($manager);
    }
    /**
     * Should the event be ignored (== not logged)?
     * @param \core\event\base $event
     * @return bool
     */

    protected function is_event_ignored(\core\event\base $event) {
        if ((!CLI_SCRIPT || PHPUNIT_TEST)) {
            // Always log inside CLI scripts because we do not login there.
            if (!isloggedin() || isguestuser()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Finally send the events to Graylog.
     *
     * @param array $evententries raw event data
     */
    protected function insert_event_entries($evententries) {
        $mode = get_config('logstore_graylog', 'mode');
        if ($mode !== 'realtime') {
            return;
        }
        foreach ($evententries as $event) {
            \logstore_graylog\graylog::log_standardentry($event);
        }
    }
}