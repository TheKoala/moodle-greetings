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

namespace local_greetings\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use context_system;
use external_api;
use external_description;
use external_function_parameters;
use external_single_structure;
use external_value;
use external_warnings;
use stdClass;

/**
 * Class add_greeting
 *
 * @package    local_greetings
 * @copyright  2024 Felipe Lima
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class add_greeting extends external_api {


    /**
     * Returns description of method parameters
     * @return \external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'Id of the user'),
            'message' => new external_value(PARAM_TEXT, 'Message to be added'),
        ]);
    }

    /**
     * Add message to database.
     * @param int $userid Id of user.
     * @param string $message Greeting message.
     * @return array Result as defined in execute_returns.
     */
    public static function execute($userid, $message) {
        global $DB;

        $warnings = [];
        $context = context_system::instance();

        $params = self::validate_parameters(self::execute_parameters(),
            ['userid' => $userid, 'message' => $message]);
        $message = trim($params['message']);

        if (empty($message)) {
            return [
                'warnings' => [[
                    'warningcode' => 'emptymessage',
                    'message' => get_string('emptymessage', 'local_greetings'),
                ]],
            ];
        }

        if (has_capability('local/greetings:postmessages', $context)) {
            $record = new stdClass;
            $record->message = $message;
            $record->timecreated = time();
            $record->userid = $params['userid'];
            $DB->insert_record('local_greetings_messages', $record);
        }

        return ['warnings' => []];
    }

    /**
     * Returns the description of the webservice
     * @return \external_description
     */
    public static function execute_returns(): external_description {
        return new external_single_structure([
            'warnings' => new external_warnings(),
        ]);
    }

}
