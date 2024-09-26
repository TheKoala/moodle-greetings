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
 * TODO describe file allmessages
 *
 * @package    local_greetings
 * @copyright  2024 Felipe Lima
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');


$PAGE->set_url(new moodle_url('/local/greetings/allmessages.php', []));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('report');

$PAGE->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('allmessages', 'local_greetings'));

require_login();
if (isguestuser()) {
    throw new moodle_exception('noguest');
}

require_capability('local/greetings:viewmessages', context_system::instance());

$homenode = $PAGE->navigation->add(
    get_string('pluginname', 'local_greetings'),
    new moodle_url('/local/greetings')
);
$allmessagesnode = $homenode->add(
    get_string('allmessages', 'local_greetings'),
    $PAGE->url
);

$output = $PAGE->get_renderer('local_greetings');

echo $output->header();

$userfields = \core_user\fields::for_name()->with_identity($context);
$userfieldssql = $userfields->get_sql('u');

$table = new local_greetings\messagelist($USER->id);

$table->set_sql("m.id, m.message, m.timecreated, m.userid, u.firstname, u.lastname",
    "{local_greetings_messages} m LEFT JOIN {user} u ON u.id = m.userid",
    true);

$table->sortable(true, 'timecreated', SORT_ASC);
$table->define_baseurl("$CFG->wwwroot/local/greetings/allmessages.php");
$table->out( 10, true);

echo $output->footer();
