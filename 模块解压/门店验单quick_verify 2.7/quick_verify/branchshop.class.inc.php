<?php

class BranchShop
{
	private static $t_shop = 'quick_verify_shop';

	public static function create($data)
	{
		$id = -1;
		$ret = pdo_insert(self::$t_shop, $data);
		if (false !== $ret) {
			$id = pdo_insertid();
		}
		return $id;
	}

	public static function update($weid, $id, $data)
	{
		$ret = pdo_update(self::$t_shop, $data, array('weid' => $weid, 'id' => $id));
		return $ret;
	}

	public static function get($id)
	{
		$shop = pdo_fetch('SELECT * FROM ' . tablename(self::$t_shop) . ' WHERE id=:id', array(':id' => $id));
		return $shop;
	}

	public static function getShopname($id)
	{
		$shop = pdo_fetchcolumn('SELECT shopname FROM ' . tablename(self::$t_shop) . ' WHERE id=:id', array(':id' => $id));
		return $shop;
	}

	public static function find($weid, $shopname)
	{
		$shop = pdo_fetch('SELECT * FROM ' . tablename(self::$t_shop) . ' WHERE weid=:weid AND shopname=:shopname LIMIT 1', array(':weid' => $weid, ':shopname' => $shopname));
		return $shop;
	}

	public static function batchGet($weid, $conds = array(), $key = null)
	{
		$condition = '';
		if (isset($conds['enabled'])) {
			$condition .= " AND enabled = " . intval($conds['enabled']);
		}
		$shop = pdo_fetchall("SELECT * FROM " . tablename(self::$t_shop) . " WHERE weid = $weid  $condition ORDER BY shopname", array(), $key);
		return $shop;
	}

	public static function remove($weid, $id)
	{
		return pdo_query("DELETE FROM " . tablename(self::$t_shop) . " WHERE id=:id AND weid=:weid", array(':weid' => $weid, ':id' => $id));
	}
}