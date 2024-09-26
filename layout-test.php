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
 * TODO describe file layout-test
 *
 * @package    local_greetings
 * @copyright  2024 Felipe Lima
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/greetings/layout-test.php'));

$PAGE->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));
$PAGE->set_pagelayout('standard');

$output = $PAGE->get_renderer('local_greetings');

echo $output->header();

$sometext = 'Here is some content but it can be anything else, too.';
$renderable = new \local_greetings\output\layout_test_page($sometext);
echo $output->render($renderable);

echo $output->footer();
