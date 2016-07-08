<?php
global $_W, $_GPC;

$operation = !empty($_GPC["op"]) ? $_GPC["op"] : "display";
load()->func("tpl");
if ($operation == "display") {
    if (!empty($_GPC["displayorder"])) {
        ca("article.page.edit");
        foreach ($_GPC["displayorder"] as $id => $displayorder) {
            pdo_update("ewei_shop_article", array(
                "displayorder" => $displayorder
            ), array(
                "id" => $id
            ));
        }
        plog("article.page.edit", "批量修改文章的排序");
        message("分类排序更新成功！", $this->createPluginWebUrl("article", array(
            "op" => "display"
        )), "success");
    }
    $select_category = empty($_GPC["category"]) ? '' : " and a.article_category=" . intval($_GPC["category"]) . " ";
    $select_title    = empty($_GPC["keyword"]) ? '' : " and a.article_title LIKE '%" . $_GPC["keyword"] . "%' ";
    $page            = empty($_GPC["page"]) ? "" : $_GPC["page"];
    $pindex          = max(1, intval($page));
    $psize           = 15;
    $articles        = pdo_fetchall("SELECT a.id,a.displayorder, a.article_title,a.article_category,a.article_keyword,a.article_date,a.article_readnum,a.article_likenum,a.article_state,c.category_name FROM " . tablename("ewei_shop_article") . " a left join " . tablename("ewei_shop_article_category") . " c on c.id=a.article_category  WHERE a.uniacid= :uniacid " . $select_title . $select_category . " order by displayorder desc,article_date desc LIMIT " . ($pindex - 1) * $psize . "," . $psize, array(
        ":uniacid" => $_W["uniacid"]
    ));
    $total           = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article") . " a left join " . tablename("ewei_shop_article_category") . " c on c.id=a.article_category  WHERE a.uniacid= :uniacid " . $select_title . $select_category, array(
        ":uniacid" => $_W["uniacid"]
    ));
    $pager           = pagination($total, $pindex, $psize);
    $articlenum      = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article") . " WHERE uniacid= :uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
    $categorys       = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_article_category") . " WHERE uniacid=:uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
} elseif ($operation == "post") {
    $categorys = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_article_category") . " WHERE uniacid=:uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
    $aid       = intval($_GPC["aid"]);
    if (!empty($aid)) {
        $article                                = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_article") . " WHERE id=:aid and uniacid=:uniacid limit 1 ", array(
            ":aid" => $aid,
            ":uniacid" => $_W["uniacid"]
        ));
        $article["product_advs"]                = htmlspecialchars_decode($article["product_advs"]);
        $advs                                   = json_decode($article["product_advs"], true);
        $article["article_rule_creditlast"]     = 0;
        $article["article_rule_moneylast"]      = 0;
        $article["article_rule_creditreallast"] = 0;
        $article["article_rule_moneyreallast"]  = 0;
        if ($article["article_rule_credittotal"] > 0 || $article["article_rule_moneytotal"] > 0) {
            $firstreads = pdo_fetchcolumn("select count(distinct click_user) from " . tablename("ewei_shop_article_share") . " where aid=:aid and uniacid=:uniacid limit 1", array(
                ":aid" => $aid,
                ":uniacid" => $_W["uniacid"]
            ));
            $allreads   = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_article_share") . " where aid=:aid and uniacid=:uniacid limit 1", array(
                ":aid" => $aid,
                ":uniacid" => $_W["uniacid"]
            ));
            $secreads   = $allreads - $firstreads;
            if ($article["article_rule_credittotal"] > 0) {
                $article["article_rule_creditlast"] = $article["article_rule_credittotal"] - ($firstreads + $article["article_readnum_v"]) * $article["article_rule_creditm"] - $secreads * $article["article_rule_creditm2"];
                $creditout                          = pdo_fetchcolumn("select sum(add_credit) from " . tablename("ewei_shop_article_share") . " where aid=:aid and uniacid=:uniacid limit 1", array(
                    ":aid" => $aid,
                    ":uniacid" => $_W["uniacid"]
                ));
                $article["article_rule_creditreallast"] = $article["article_rule_credittotal"] - $creditout;
                $article["article_rule_creditlast"] <= 0 && $article["article_rule_creditlast"] = 0;
                $article["article_rule_creditreallast"] <= 0 && $article["article_rule_creditreallast"] = 0;
            }
            if ($article["article_rule_moneytotal"] > 0) {
                $article["article_rule_moneylast"] = $article["article_rule_moneytotal"] - ($firstreads + $article["article_readnum_v"]) * $article["article_rule_moneym"] - $secreads * $article["article_rule_moneym2"];
                $moneyout                          = pdo_fetchcolumn("select sum(add_money) from " . tablename("ewei_shop_article_share") . " where aid=:aid and uniacid=:uniacid limit 1", array(
                    ":aid" => $aid,
                    ":uniacid" => $_W["uniacid"]
                ));
                $article["article_rule_moneyreallast"] = $article["article_rule_moneytotal"] - $moneyout;
                $article["article_rule_moneylast"] <= 0 && $article["article_rule_moneylast"] = 0;
                $article["article_rule_moneyreallast"] <= 0 && $article["article_rule_moneyreallast"] = 0;
            }
        }
    }
    $mp          = pdo_fetch("SELECT acid,uniacid,name FROM " . tablename("account_wechats") . " WHERE uniacid=:uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
    $goodcates   = pdo_fetchall("SELECT id,name,parentid FROM " . tablename("ewei_shop_category") . " WHERE enabled=:enabled and uniacid= :uniacid  ", array(
        ":uniacid" => $_W["uniacid"],
        ":enabled" => "1"
    ));
    $articles    = pdo_fetchall("SELECT id,article_title,article_category FROM " . tablename("ewei_shop_article") . " WHERE uniacid=:uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
    $article_sys = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_article_sys") . " WHERE uniacid=:uniacid limit 1 ", array(
        ":uniacid" => $_W["uniacid"]
    ));
    $designer    = p("designer");
    if ($designer) {
        $diypages = pdo_fetchall("SELECT id,pagetype,setdefault,pagename FROM " . tablename("ewei_shop_designer") . " WHERE uniacid=:uniacid order by setdefault desc  ", array(
            ":uniacid" => $_W["uniacid"]
        ));
    }
} elseif ($operation == "category") {
    $kw          = $_GPC["keyword"];
    $page        = empty($_GPC["page"]) ? "" : $_GPC["page"];
    $pindex      = max(1, intval($page));
    $psize       = 15;
    $list        = pdo_fetchall("SELECT * FROM " . tablename("ewei_shop_article_category") . " WHERE category_name LIKE :name and uniacid=:uniacid order by id desc LIMIT " . ($pindex - 1) * $psize . "," . $psize, array(
        ":name" => "%{$kw}%",
        ":uniacid" => $_W["uniacid"]
    ));
    $total       = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article_category") . " WHERE category_name LIKE :name and uniacid=:uniacid ", array(
        ":name" => "%{$kw}%",
        ":uniacid" => $_W["uniacid"]
    ));
    $pager       = pagination($total, $pindex, $psize);
    $categorynum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article_category") . " WHERE uniacid= :uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
} elseif ($operation == "other") {
    ca("article.page.otherset");
    $article_sys = pdo_fetch("SELECT * FROM " . tablename("ewei_shop_article_sys") . " WHERE uniacid=:uniacid limit 1 ", array(
        ":uniacid" => $_W["uniacid"]
    ));
} elseif ($operation == "list_read" || $operation == "list_share") {
    ca("article.page.showdata");
    $aid = intval($_GPC["aid"]);
    if (!empty($aid)) {
        $page        = empty($_GPC["page"]) ? "" : $_GPC["page"];
        $pindex      = max(1, intval($page));
        $psize       = 20;
        $article     = pdo_fetch("SELECT a.*,c.category_name FROM " . tablename("ewei_shop_article") . " a left join " . tablename("ewei_shop_article_category") . " c on c.id=a.article_category  WHERE a.id=:aid and a.uniacid= :uniacid LIMIT 1 ", array(
            ":aid" => $aid,
            ":uniacid" => $_W["uniacid"]
        ));
        $firstreads  = pdo_fetchcolumn("select count(distinct click_user) from " . tablename("ewei_shop_article_share") . " where aid=:aid and uniacid=:uniacid limit 1", array(
            ":aid" => $aid,
            ":uniacid" => $_W["uniacid"]
        ));
        $allreads    = pdo_fetchcolumn("select count(*) from " . tablename("ewei_shop_article_share") . " where aid=:aid and uniacid=:uniacid limit 1", array(
            ":aid" => $aid,
            ":uniacid" => $_W["uniacid"]
        ));
        $secreads    = $allreads - $firstreads;
        $add_credit1 = 0;
        $add_money1  = 0;
        if ($article["article_rule_credittotal"] > 0) {
            $add_credit1 = intval($firstreads * $article["article_rule_creditm"] + ($secreads + $article["article_readnum_v"]) * $article["article_rule_creditm2"]);
        }
        if ($article["article_rule_moneytotal"] > 0) {
            $add_money1 = $firstreads * $article["article_rule_moneym"] + ($secreads + $article["article_readnum_v"]) * $article["article_rule_moneym2"];
        }
        $add_credit = pdo_fetchcolumn("SELECT sum(add_credit) FROM " . tablename("ewei_shop_article_share") . " WHERE aid=:aid and uniacid=:uniacid ", array(
            ":aid" => $aid,
            ":uniacid" => $_W["uniacid"]
        ));
        $add_money  = pdo_fetchcolumn("SELECT sum(add_money) FROM " . tablename("ewei_shop_article_share") . " WHERE aid=:aid and uniacid=:uniacid ", array(
            ":aid" => $aid,
            ":uniacid" => $_W["uniacid"]
        ));
        if ($operation == "list_read") {
            $list_reads = pdo_fetchall("SELECT l.id,l.aid,l.read,l.like,u.nickname,u.uid FROM " . tablename("ewei_shop_article_log") . " l left join " . tablename("ewei_shop_member") . " u on u.openid=l.openid WHERE l.aid=:aid and l.uniacid=:uniacid order by l.id desc limit " . ($pindex - 1) * $psize . "," . $psize, array(
                ":aid" => $aid,
                ":uniacid" => $_W["uniacid"]
            ));
            $total      = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article_log") . " WHERE aid=:aid and uniacid=:uniacid ", array(
                ":aid" => $aid,
                ":uniacid" => $_W["uniacid"]
            ));
            $pager      = pagination($total, $pindex, $psize);
        } else {
            $list_shares = pdo_fetchall("SELECT s.id,s.click_date,s.add_credit,s.add_money, u.nickname as share_user,c.nickname as click_user FROM " . tablename("ewei_shop_article_share") . " s left join " . tablename("ewei_shop_member") . " u on u.id=s.share_user left join " . tablename("ewei_shop_member") . " c on c.id=s.click_user  WHERE s.aid=:aid and s.uniacid=:uniacid order by s.id desc limit " . ($pindex - 1) * $psize . "," . $psize, array(
                ":aid" => $aid,
                ":uniacid" => $_W["uniacid"]
            ));
            $total       = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article_share") . " WHERE aid=:aid and uniacid=:uniacid ", array(
                ":aid" => $aid,
                ":uniacid" => $_W["uniacid"]
            ));
            $pager       = pagination($total, $pindex, $psize);
        }
    }
} elseif ($operation == "report") {
    ca("article.page.report");
    $categorys = array(
        0 => "全部分类",
        1 => "欺诈",
        2 => "色情",
        3 => "政治谣言",
        4 => "常识性谣言",
        5 => "诱导分享",
        6 => "恶意营销",
        7 => "隐私信息收集",
        8 => "其他侵权类"
    );
    $articles  = pdo_fetchall("SELECT id,article_title FROM " . tablename("ewei_shop_article") . " WHERE uniacid=:uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
    $kw        = $_GPC["keyword"];
    $aid       = intval($_GPC["aid"]);
    $cid       = $_GPC["cid"];
    $where     = '';
    if (!empty($aid)) {
        $where .= " and aid=" . $aid;
    }
    if (!empty($cid)) {
        $where .= " and cate='" . $categorys[$cid] . "'";
    }
    if (!empty($kw)) {
        $where .= " and cons like '%" . $kw . "%'";
    }
    $page      = empty($_GPC["page"]) ? "" : $_GPC["page"];
    $pindex    = max(1, intval($page));
    $psize     = 15;
    $datas     = pdo_fetchall("SELECT r.id,r.mid,r.openid,r.aid,r.cate,r.cons,u.nickname,a.article_title FROM " . tablename("ewei_shop_article_report") . " r left join " . tablename("ewei_shop_member") . " u on u.id=r.mid left join " . tablename("ewei_shop_article") . " a on a.id=r.aid where r.uniacid=:uniacid " . $where . " order by id desc limit " . ($pindex - 1) * $psize . "," . $psize, array(
        ":uniacid" => $_W["uniacid"]
    ));
    $total     = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article_report") . " where uniacid=:uniacid " . $where, array(
        ":uniacid" => $_W["uniacid"]
    ));
    $pager     = pagination($total, $pindex, $psize);
    $reportnum = pdo_fetchcolumn("SELECT COUNT(*) FROM " . tablename("ewei_shop_article_report") . " WHERE uniacid= :uniacid ", array(
        ":uniacid" => $_W["uniacid"]
    ));
}
load()->func("tpl");
include $this->template("index");
?>