<?php

global $_GPC, $_W;

$op = $_GPC['op'] ? $_GPC['op'] : 'list';

if($op == 'list'){
    include $this->template('web/synopsis');
}