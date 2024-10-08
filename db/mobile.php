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
 * Mobile app areas for Greetings
 *
 * Documentation: {@link https://moodledev.io/general/app/development/plugins-development-guide}
 *
 * @package    local_greetings
 * @copyright  2024 Felipe Lima
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$addons = [
    'local_greetings' => [
        'handlers' => [
            'hello' => [
                'delegate' => 'CoreMainMenuDelegate',
                'method' => 'view_hello',
                'displaydata' => [
                    'title' => 'pluginname',
                    'icon' => 'earth',
                ],
            ],
            'greetingslist' => [
                'delegate' => 'CoreMainMenuHomeDelegate',
                'method' => 'mobile_view_greetings_list',
                'displaydata' => [
                    'title' => 'pluginname',
                ],
            ],
        ],
        'lang' => [
            ['hello', 'local_greetings'],
            ['pluginname', 'local_greetings'],
            ['postedby', 'local_greetings'],
            ['nomessages', 'local_greetings'],
            ['yourmessage', 'local_greetings'],
            ['yourmessagehint', 'local_greetings'],
            ['submit', 'local_greetings'],
            ['cannotaddgreeting', 'local_greetings'],
        ],
    ],
  ];
