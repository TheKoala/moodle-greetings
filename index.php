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

require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

$allowpost = has_capability('local/greetings:postmessages', $context);
$allowview = has_capability('local/greetings:viewmessages', $context);
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);
$deleteownpost = has_capability('local/greetings:deleteownmessage', $context);

$messageform = new \local_greetings\form\message_form();

if ($messageform->is_cancelled()) {
    redirect($PAGE->url);
}


if ($data = $messageform->get_data()) {
    require_capability('local/greetings:postmessages', $context);
    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;

        $DB->insert_record('local_greetings_messages', $record);
        redirect($PAGE->url);
    }
}

$action = optional_param('action', '', PARAM_TEXT);
if ($action == 'del') {
    require_sesskey();
    $id = required_param('id', PARAM_TEXT);
    if ($deleteanypost || $deleteownpost) {
        $params = ['id' => $id];
        if (!$deleteanypost) {
            $params += ['userid' => $USER->id];
        }
        $DB->delete_records('local_greetings_messages', $params);

        redirect($PAGE->url);
    }
}

 // Construção do HTML da página.
$output = $PAGE->get_renderer('local_greetings');

echo $output->header();

// Adding simple navmenu acting as "terciary navigation".
echo $output->render_from_template('local_greetings/navmenu', []);

if (isloggedin()) {
    echo '<h2>' .
        local_greetings_get_greeting($USER)
        . '</h2>';
} else {
    echo '<h2>' .
        get_string('greetinguser', 'local_greetings')
        . '</h2>';
}

if ($allowpost) {
    $messageform->display();
}

if ($allowview) {
    $userfields = \core_user\fields::for_name()->with_identity($context);
    $userfieldssql = $userfields->get_sql('u');

    $sql = "SELECT m.id, m.message, m.timecreated, m.userid, u.firstname, u.lastname
            FROM {local_greetings_messages} m
            LEFT JOIN {user} u ON u.id = m.userid
            ORDER BY timecreated DESC LIMIT 10";

    $messages = $DB->get_records_sql($sql);

    $renderable = new \local_greetings\output\index_page($messages);
    echo $output->render($renderable);
}

echo $output->footer();
