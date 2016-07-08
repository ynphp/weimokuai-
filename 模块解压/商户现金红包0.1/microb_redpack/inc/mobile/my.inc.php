<?php
global $_W, $_GPC;

$user = $this->auth();

require_once MB_ROOT . '/source/Shared.class.php';
require_once MB_ROOT . '/source/Fans.class.php';
$f = new Fans();
$s = new Shared();

$filters = array();
$filters['from'] = $user['uid'];
$ds = $s->getAllHelps($filters);
if(!empty($ds)) {
    foreach($ds as &$r) {
        $r['user'] = $f->getOne($r['to']);
    }
}
include $this->template('my');
