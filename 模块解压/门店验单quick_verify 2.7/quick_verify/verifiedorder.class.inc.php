<?php

class VerifiedOrder
{
	private static $t_shop = 'quick_verify_shop';
	private static $t_order = 'quick_verify_order';
	private static $t_ordergoods = 'quick_shop_order_goods';
	private static $t_goods = 'quick_shop_goods';
	private static $t_clerk = 'quick_verify_clerk';
	public static $ORDER_SCANNED = 1;
	public static $ORDER_SETTLED = 2;

	public function create($data)
	{
		$id = -1;
		$ret = pdo_insert(self::$t_order, $data);
		if (false !== $ret) {
			$id = pdo_insertid();
		}
		return $id;
	}

	public function update($weid, $id, $data)
	{
		$ret = pdo_update(self::$t_order, $data, array('weid' => $weid, 'id' => $id));
		return $ret;
	}

	public function get($id)
	{
		$order = pdo_fetch('SELECT * FROM ' . tablename(self::$t_order) . ' WHERE id=:id', array(':id' => $id));
		return $order;
	}

	public function getDetailed($weid, $orderid)
	{
		$sql = 'SELECT  a.id, a.orderid, a.ordersn, a.price, a.mobile, a.title, a.createtime,
            b.weid, b.clerk_openid, b.clerk_realname, b.clerk_mobile, b.shopname
      FROM ' . tablename(self::$t_order) . ' a INNER JOIN ' . tablename(self::$t_clerk) . ' b ON a.clerk_openid = b.clerk_openid AND a.weid=b.weid ' . ' WHERE a.orderid=:orderid AND a.weid=:weid';
		WeUtility::logging('get detailed', array($orderid, $weid, $sql));
		$order = pdo_fetch($sql, array(':orderid' => $orderid, ':weid' => $weid));
		return $order;
	}

	public function batchGet($weid, $conds = array(), $pindex = 1, $psize = 100000, $key = null)
	{
		$condition = '';
		if (isset($conds['ordersn'])) {
			$condition .= " AND ordersn = '" . $conds['ordersn'] . "'";
		}
		if (isset($conds['mobile'])) {
			$condition .= " AND mobile = '" . $conds['mobile'] . "'";
		}
		if (isset($conds['settlestatus'])) {
			$condition .= " AND settlestatus = " . intval($conds['settlestatus']);
		}
		if (isset($conds['shopid'])) {
			$condition .= " AND shopid = " . intval($conds['shopid']);
		}
		if (isset($conds['clerk_openid'])) {
			$condition .= " AND clerk_openid = '" . $conds['clerk_openid'] . "'";
		}
		$list = pdo_fetchall("SELECT * FROM " . tablename(self::$t_order) . " WHERE weid = $weid  $condition ORDER BY createtime DESC" . " LIMIT " . ($pindex - 1) * $psize . ',' . $psize, array(), $key);
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename(self::$t_order) . " WHERE weid = $weid  $condition ", array(), $key);
		return array($list, $total);
	}

	public function remove($weid, $id)
	{
		return pdo_query("DELETE FROM " . tablename(self::$t_order) . " WHERE id=:id AND weid=:weid", array(':weid' => $weid, ':id' => $id));
	}

	public function getShopStat($weid, $conds = array())
	{
		$condition = '';
		if (isset($conds['settlestatus'])) {
			$condition .= " AND b.settlestatus = " . $conds['settlestatus'];
		}
		$list = pdo_fetchall("SELECT shopname, SUM(price) price FROM " . tablename(self::$t_shop) . " a LEFT JOIN " . tablename(self::$t_order) . " b " . " ON a.id = b.shopid $condition " . " WHERE a.weid = $weid GROUP BY a.id");
		return $list;
	}

	public function getGoodsStat($weid, $conds = array())
	{
		$condition = '';
		if (isset($conds['settlestatus'])) {
			$condition .= " AND b.settlestatus = " . $conds['settlestatus'];
		}
		$list = pdo_fetchall("SELECT shopname, d.title, SUM(b.price) price FROM " . tablename(self::$t_shop) . " a " . " LEFT JOIN " . tablename(self::$t_order) . " b  ON a.id = b.shopid $condition" . " LEFT JOIN " . tablename(self::$t_ordergoods) . " c ON b.orderid = c.orderid " . " LEFT JOIN " . tablename(self::$t_goods) . " d ON c.goodsid = d.id " . " WHERE a.weid = $weid  GROUP BY a.id, c.goodsid");
		return $list;
	}
}