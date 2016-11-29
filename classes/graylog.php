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

namespace logstore_graylog;

defined('MOODLE_INTERNAL') || die();

/**
 * Graylog interface.
 */
class graylog
{
    private static $instance;

    private $transport;
    private $config;
    private $buffer = array();
    private $ready;

    /**
     * Constructor.
     */
    private function __construct() {
        $this->ready = false;
        try {
            if ($this->setup()) {
                $this->ready = true;
            }
        } catch (\Exception $e) { }
    }

    /**
     * Setup the connection.
     */
    private function setup() {
        require_once(dirname(__FILE__) . '/../vendor/autoload.php');
        $this->config = get_config('logstore_graylog');
        if (!isset($this->config->hostname)) {
            return false;
        }
        if ($this->config->transport == 'udp') {
            $this->transport = new \Gelf\Transport\UdpTransport(
                $this->config->hostname,
                $this->config->port,
                \Gelf\Transport\UdpTransport::CHUNK_SIZE_LAN
            );
        }
        else if ($this->config->transport == 'tcp') {
            $this->transport = new \Gelf\Transport\TcpTransport(
                $this->config->hostname,
                $this->config->port
            );
        }
        return true;
    }

    /**
     * Singleton.
     */
    public static function instance() {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Flush buffers.
     */
    public function dispose()
    {
        if (!empty($this->buffer)) {
            $this->flush();
        }
    }

    /**
     * Destructor.
     */
    public function __destruct() {
        $this->dispose();
    }

    /**
     * Are we ready?
     */
    public function is_ready() {
        return $this->ready;
    }

    /**
     * Is Graylog enabled?
     */
    public static function is_enabled() {
        $enabled = get_config('tool_log', 'enabled_stores');
        $enabled = array_flip(explode(',', $enabled));
        return isset($enabled['logstore_graylog']) && $enabled['logstore_graylog'];
    }

    /**
     * Log an item with Graylog.
     * @param $data JSON
     */
    public static function log($data) {
        $graylog = static::instance();
        $graylog->buffer[] = $data;
        if (count($graylog->buffer) > 100) {
            $graylog->flush();
        }
    }

    /**
     * Store a standard log item with Graylog.
     * @param $data
     */
    public static function log_standardentry($data) {
        $data = (array)$data;
        $newrow = new \stdClass();
        $newrow->timestamp = date(\DateTime::ISO8601, $data['timecreated']);
        foreach ($data as $k => $v) {
            if ($k == 'other') {
                $tmp = unserialize($v);
                if ($tmp !== false) {
                    $v = json_encode($tmp);
                }
            }
            if ($k == 'id') {
                $k = 'log_id';
            }
            $newrow->$k = $v;
        }
        static::log(json_encode($newrow));
    }

    /**
     * End the buffer.
     */
    public function flush() {

        if (empty($this->buffer) || !$this->is_ready()) {
            return;
        }

        $publisher = new \Gelf\Publisher();
        $publisher->addTransport($this->transport);
        foreach ($this->buffer as $log){
            $log = json_decode($log, true);
            $message = new \Gelf\Message();
            $message->setShortMessage($log['eventname']);
            foreach ($log as $k => $v) {
                $message->setAdditional($k, $v);
            }
            $publisher->publish($message);
        }
        $this->buffer = array();
    }
}