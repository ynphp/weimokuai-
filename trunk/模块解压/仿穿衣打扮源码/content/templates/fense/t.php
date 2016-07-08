<?php 
/**
 * 微语部分
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<!--左侧开始-->
<div id="content" class="content">

    <?php if(ROLE == 'admin' || ROLE == 'writer'): ?>
    <div style="margin-right:20px;float:right;font-size:16px"><a href="<?php echo BLOG_URL . 'admin/twitter.php' ?>">写微语</a></div>
    <?php endif; ?>
    <ul>
    <?php 
    foreach($tws as $val):
    $author = $user_cache[$val['author']]['name'];
    $avatar = empty($user_cache[$val['author']]['avatar']) ? 
                BLOG_URL . 'admin/views/images/avatar.jpg' : 
                BLOG_URL . $user_cache[$val['author']]['avatar'];
    $tid = (int)$val['id'];
    $img = empty($val['img']) ? "" : '<a title="查看图片" href="'.BLOG_URL.str_replace('thum-', '', $val['img']).'" target="_blank"><img style="border: 1px solid #EFEFEF;" src="'.BLOG_URL.$val['img'].'"/></a>';
    ?> 
    <li style="list-style:none;list-style-type:none;margin:10px;padding:10px 0;border-bottom:1px solid #FCF">
    <div style="float:left;width:32px;height:32px;margin-right:5px"><img src="<?php echo $avatar; ?>" width="32px" height="32px" /></div>
    <div style="float:left"><span style="font-size:16px"><?php echo $author; ?></span><br /><span style="font-size:14px"><?php echo $val['t'].'<br/>'.$img;?></span></div>
    <div class="clear"></div>
    <div style="padding-top:5px">
       
        <p class="time"><?php echo $val['date'];?> </p>
    </div>
	<div class="clear"></div>

    </li>
 <?php endforeach;?></ul>
<div class="pages"><?php echo $page_url;?></div>
</div>
<?php
 include View::getView('side');
 include View::getView('footer');
?>