<?php

defined('IN_IA') or exit('Access Denied');

class anhaoModule extends WeModule {

    public $name = 'anhaoModule';
    public $title = '微暗号';
    public $ability = '';
    public $table_reply = 'anhao_reply';
    public $table_item = 'anhao_item';
    public $table_order = 'anhao_order';

    public function fieldsFormDisplay($rid = 0) {
        //要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
        global $_W;
        if (!empty($rid)) {
            $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
            $items = pdo_fetchall("SELECT * FROM " . tablename($this->table_item) . " WHERE rid = :rid ORDER BY `orderid` ASC", array(':rid' => $rid));
        }
        $reply['start_time'] = empty($reply['start_time']) ? strtotime(date('Y-m-d')) : $reply['start_time'];
        $reply['end_time'] = empty($reply['end_time']) ? TIMESTAMP : $reply['end_time'] + 86399;
        load()->func('tpl');
        include $this->template('form');
    }

    public function fieldsFormValidate($rid = 0) {
        //规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
        return '';
    }

    public function fieldsFormSubmit($rid) {
        //规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
        global $_GPC, $_W;
        $id = intval($_GPC['reply_id']);
		$str=$_GPC['description'];
		$str=str_replace('&lt;p&gt;','',$str);
		$str=str_replace('&lt;/p&gt;','',$str);
		
        $insert = array(
            'rid' => $rid,
            'title' => $_GPC['title'],
            'picture' => $_GPC['picture'],
            'headimage' => $_GPC['headimage'],
			'headimage1' => $_GPC['headimage1'],
			'headimage2' => $_GPC['headimage2'],
            'description' => $str,
            'address' => $_GPC['address'],
			'address1' => $_GPC['address1'],
			'address2' => $_GPC['address2'],
            'tel' => $_GPC['tel'],
            'max' => $_GPC['max'],
            'start_time' => strtotime($_GPC['start_time']),
            'end_time' => strtotime($_GPC['end_time']),
            'status' => $_GPC['status']
        );
        if (empty($id)) {
            pdo_insert($this->table_reply, $insert);
        } else {
            if (!empty($_GPC['picture'])) {
//                file_delete($_GPC['picture-old']);
            } else {
                unset($insert['picture']);
            }
            if (!empty($_GPC['headimage'])) {
//                file_delete($_GPC['headimage-old']);
            } else {
                unset($insert['headimage']);
            }
            pdo_update($this->table_reply, $insert, array('id' => $id));
        }
        if (!empty($_GPC['item-fieldname'])) {
            foreach ($_GPC['item-fieldname'] as $index => $title) {
                if (empty($title)) {
                    continue;
                }
                $update = array(
                    'rid' => $rid,
                    'type' => intval($_GPC['item-type'][$index]),
                    'fieldname' => $_GPC['item-fieldname'][$index],
                    'fieldcontent' => $_GPC['item-fieldcontent'][$index],
                    'isdefault' => intval($_GPC['item-isdefault'][$index]),
                    'orderid' => intval($_GPC['item-orderid'][$index])
                );
                pdo_update('anhao_item', $update, array('id' => $index));
            }
        }
        //处理添加
        //print_r($_GPC);
        if (!empty($_GPC['item-fieldname-new'])) {
            foreach ($_GPC['item-fieldname-new'] as $index => $title) {
                if (empty($title)) {
                    continue;
                }
                $insert = array(
                    'rid' => $rid,
                    'type' => intval($_GPC['item-type-new'][$index]),
                    'fieldname' => $_GPC['item-fieldname-new'][$index],
                    'fieldcontent' => $_GPC['item-fieldcontent-new'][$index],
                    'isdefault' => intval($_GPC['item-isdefault-new'][$index]),
                    'orderid' => intval($_GPC['item-orderid-new'][$index])
                );
                pdo_insert('anhao_item', $insert);
            }
        }
    }

}
