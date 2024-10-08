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

namespace local_greetings\output;
use context_system;

/**
 * Class mobile
 *
 * @package    local_greetings
 * @copyright  2024 Felipe Lima
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {

    /**
     * define plugin view to mobile
     * @return array[]
     */
    public static function view_hello() {
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => '<h1 class="text-center">{{"plugin.local_greetings.hello" | translate }}</h1>',
                ],
            ],
        ];
    }

    /**
     * define plugin view to mobile
     * @return array[]
     */
    public static function mobile_view_greetings_list($args) {

        global $OUTPUT, $DB;

        $args = (object) $args;
        $context = context_system::instance();
        $messages = [];

        if (has_capability('local/greetings:viewmessages', $context)) {
            $userfields = \core_user\fields::for_name()->with_identity($context);
            $userfieldssql = $userfields->get_sql('u');
            $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
                FROM {local_greetings_messages} m
                LEFT JOIN {user} u ON u.id = m.userid
                ORDER BY timecreated DESC";
            $messages = $DB->get_records_sql($sql);
        }

        $data = [
            'messages' => array_values($messages),
            'postmessages' => has_capability('local/greetings:postmessages', $context),
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('local_greetings/mobile_view_greetings_list', $data),
                ],
            ],
            'javascript' => file_get_contents(__DIR__ .'/../../js/mobile/view_greetings_list.js'),
        ];
    }

    /**
     * Show screen form to new postage
     * @param mixed $args
     * @return array[]
     */
    public static function mobile_view_greetings_form($args) {
        global $OUTPUT, $USER;

        $context = context_system::instance();

        $data = [
            'canpost' => has_capability('local/greetings:postmessages', $context),
            'userid' => $USER->id,
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('local_greetings/mobile_add_greeting', $data),
                ],
            ],
            'javascript' => file_get_contents(__DIR__ .'/../../js/mobile/add_greeting.js'),
        ];
    }

}
