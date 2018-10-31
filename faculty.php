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

$data['ESB'] = array('AUS', 'AW', 'DEV', 'DV3', 'DV4', 'EMS', 'ESA', 'ESE', 'ESF', 'ESG', 'ESI', 'ESM', 'ESN', 'ESP', 'ESS', 'IBU', 'ILM', 'IM', 'IMP', 'LOG', 'MIM', 'OM', 'PM', 'PRM', 'PRO');
$data['INF'] = array('HUC', 'MTI', 'MUK', 'SC', 'WI', 'WIN');
$data['TD'] = array('DES', 'IFR', 'TB', 'TE', 'TID', 'TM', 'TT');

$param = optional_param('q', 0, PARAM_TEXT);
$query = strtoupper($param);
$res = $data[$query];

echo json_encode($res);



















