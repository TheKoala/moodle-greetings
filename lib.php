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
 * Callback implementations for Greetings
 *
 * @package    local_greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Retorna a string de acordo com a localização do usuário
 * @return string
 */
function local_greetings_get_greeting($user) {
    if ($user == null) {
        return get_string('greetinguser', 'local_greetings');
    }

    $country = $user->country;
    switch ($country) {
        case 'AU':
            $langstr = 'greetinguserau';
            break;
        case 'ES':
            $langstr = 'greetinguseres';
            break;
        case 'FJ':
            $langstr = 'greetinguserfj';
            break;
        case 'NZ':
            $langstr = 'greetingusernz';
            break;
        case 'BR':
            $langstr = 'greetinguserptbr';
            break;
        default:
            $langstr = 'greetingloggedinuser';
            break;
    }

    return get_string($langstr, 'local_greetings', fullname($user));
}

/**
 * Insere um link para na Página Inicial no menu de Navegação
 * @param navigation_node $frontpage Node que representa a página inicial na arvore de navegação
 */
function local_greetings_extend_navigation_frontpage(navigation_node $frontpage) {
    $frontpage->add(
        get_string('pluginname', 'local_greetings'),
        new moodle_url('/local/greetings'),
        navigation_node::TYPE_CUSTOM,
    );
}

/**
 * Insere um link para navegação na páginal inicial para themas nao baseados no boost
 * @param global_navigation $root representa o nó raiz
 */
function local_greetings_extend_navigation(global_navigation $root) {
    $node = navigation_node::create(
        get_string('pluginname', 'local_greetings'),
        new moodle_url('/local/greetings/index.php'),
        navigation_node::TYPE_CUSTOM,
        null,
        null,
        new pix_icon('t/message', '')
    );
    $node->showinflatnavigation = true;
    $root->add_node($node);
}
