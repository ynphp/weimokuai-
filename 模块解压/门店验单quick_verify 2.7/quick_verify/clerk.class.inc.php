<?php

class Clerk
{
	private static $t_clerk = 'quick_verify_clerk';

	public function create($data)
	{
		$id = -1;
		$ret = pdo_insert(self::$t_clerk, $data);
		if (false !== $ret) {
			$id = pdo_insertid();
		}
		return $id;
	}

	public function update($weid, $id, $data)
	{
		$ret = pdo_update(self::$t_clerk, $data, array('weid' => $weid, 'id' => $id));
		return $ret;
	}

	public function get($id)
	{
		$clerk = pdo_fetch('SELECT * FROM ' . tablename(self::$t_clerk) . ' WHERE id=:id', array(':id' => $id));
		return $clerk;
	}

	public function find($weid, $clerk_openid)
	{
		$clerk = pdo_fetch('SELECT * FROM ' . tablename(self::$t_clerk) . ' WHERE weid=:weid AND clerk_openid=:openid LIMIT 1', array(':weid' => $weid, ':openid' => $clerk_openid));
		return $clerk;
	}

	public function batchGet($weid, $conds = array(), $key = null)
	{
		$condition = '';
		if (isset($conds['enabled'])) {
			$condition .= " AND enabled = " . intval($conds['enabled']);
		}
		$clerk = pdo_fetchall("SELECT * FROM " . tablename(self::$t_clerk) . " WHERE weid = $weid  $condition ORDER BY shopname", array(), $key);
		return $clerk;
	}

	public function remove($weid, $id)
	{
		return pdo_query("DELETE FROM " . tablename(self::$t_clerk) . " WHERE id=:id AND weid=:weid", array(':weid' => $weid, ':id' => $id));
	}
}