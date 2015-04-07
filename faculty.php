<?php
$data['ESB'] = array('AUS', 'AW', 'DEV', 'DV3', 'DV4', 'EMS', 'ESA', 'ESE', 'ESF', 'ESG', 'ESI', 'ESM', 'ESN', 'ESP', 'ESS', 'IBU', 'ILM', 'IM', 'IMP', 'LOG', 'MIM', 'OM', 'PM', 'PRM', 'PRO');
$data['INF'] = array('HUC', 'MTI', 'MUK', 'SC', 'WI', 'WIN');
$data['TD'] = array('DES', 'IFR', 'TB', 'TE', 'TID', 'TM', 'TT');

$query = strtoupper($_REQUEST['q']);
$res = $data[$query];

echo json_encode($res);



















