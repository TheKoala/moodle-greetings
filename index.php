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
 * Greetings page to user
 *
 * @package    local_greetings
 * @copyright  2024 Felipe Lima felipelima8556@gmail.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot .'/local/greetings/lib.php');

$context = context_system::instance();
$PAGE->set_context($context);

$url = new moodle_url('/local/greetings/index.php');
$PAGE->set_url($url);

$PAGE->set_pagelayout('standard');

$PAGE->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

$messageform = new \local_greetings\form\message_form();

echo $OUTPUT->header();

if (isloggedin()) {
    echo '<h2>' .
        local_greetings_get_greeting($USER)
        . '</h2>';
} else {
    echo '<h2>' .
        get_string('greetinguser', 'local_greetings')
        . '</h2>';
}

$messageform->display();

if ($data = $messageform->get_data()) {
    $message = required_param('message', PARAM_TEXT);
    echo $OUTPUT->heading($message, 4);
}

echo $OUTPUT->footer();
