<?php
$data['INF'] = array('EBE', 'ESG', 'AAD', 'FS', 'GD', 'EGD', 'EDG');
$data['AC'] = array('PM', 'PX', 'SSE', 'GDS', 'DGD', 'lIS', 'OPP', 'LOP', 'LLP', 'DY');

$query = strtoupper($_REQUEST['q']);
$res = $data[$query];

echo json_encode($res);