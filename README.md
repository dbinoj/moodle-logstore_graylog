# Graylog Logstore for Moodle

Logstore plugin for moodle to ship logs to graylog or other log backends which supports the GELF format.
This plugin requires that you have a working Graylog node. 
Graylog can be downloaded for free from [graylog2.org](https://www.graylog.org/).
Transport protocols TCP and UDP are supported.

The array in `other` column in standard logstore is sent as a JSON string as of now. Users can use the "JSON Extractor" feature of Graylog in the `other` field to store them as separate fields in Graylog.

Please use [Github Issues](https://github.com/dbinoj/moodle-logstore_graylog/issues) for bug reports, feature requests and send code contibutions as [Github Pull Requests](https://github.com/dbinoj/moodle-logstore_graylog/pulls).

Installation
--
 1. Create a new Input of type GELF UDP or GELF TCP in Graylog from the Graylog web interface at system/inputs.
 2. Unzip this plugin to directory `admin/tool/log/store/` in your moodle installation.
 3. Navigate to "System Administration -> Notifications" to install the plugin & configure Graylog input details.

This plugin can operate in two modes, just like the splunk logstore plugin.
 * Realtime -> Logs are sent to graylog as soon as they happen.
 * Background -> Logs are stored in standard logstore and new log entries are sent to graylog once every minute during cron run.

Heavily inspired by the splunk logstore at https://github.com/unikent/moodle-logstore_splunk. Thanks [Skylar Kelty](mailto:S.Kelty@kent.ac.uk)!

TODO
--
 * Implement other transports which GELF supports, such as, Kafka / AMQP, HTTP.
 * Use LearningLocker/Moodle-Log-Expander or something similar to expand IDs. 
   Better if logstore evolves automatically whenever the Moodle-Log-Expander project has new log types implemented.
