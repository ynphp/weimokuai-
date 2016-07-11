<?php


if (!defined('IN_IA')) {
    exit('Access Denied');
}
class Ewei_DShop_Shop
{
    public function getCategory()
    {
        global $_W;
        $shopset     = m('common')->getSysset('shop');
        $allcategory = array();
        $category    = pdo_fetchall("SELECT * FROM " . tablename('ewei_shop_category') . " WHERE uniacid=:uniacid and enabled=1 ORDER BY parentid ASC, displayorder DESC", array(
            ':uniacid' => $_W['uniacid']
        ));
        $category    = set_medias($category, array(
            'thumb',
            'advimg'
        ));
        foreach ($category as $c) {
            if (empty($c['parentid'])) {
                $children = array();
                foreach ($category as $c1) {
                    if ($c1['parentid'] == $c['id']) {
                        if (intval($shopset['catlevel']) == 3) {
                            $children2 = array();
                            foreach ($category as $c2) {
                                if ($c2['parentid'] == $c1['id']) {
                                    $children2[] = $c2;
                                }
                            }
                            $c1['children'] = $children2;
                        }
                        $children[] = $c1;
                    }
                }
                $c['children'] = $children;
                $allcategory[] = $c;
            }
        }
        return $allcategory;
    }
}