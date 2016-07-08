<?php
$shop['printers'] = '1,3';
if (!empty($shop['printers'])) {
	$p = trim(str_replace(' ', '', $shop['printers']), ',');
	$devIds = explode(',', $p);
	foreach ($devIds as $devId) {
		$devId = intval($devId);
		if ($devId > 0) {
			echo $devId . '--';
		}
	}
}