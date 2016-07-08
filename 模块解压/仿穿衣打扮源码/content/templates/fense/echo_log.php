<?php 
/**
 * 阅读文章页面
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<div id="content" class="content">
<div id="innerContent">
<div class="textbox">
<div class="textbox-title">
<h1><span id="starid8350"><img src="<?php echo TEMPLATE_URL; ?>images/unstarred.gif" /></span><?php echo $log_title; ?></h1>
<div class="textbox-label">[ <?php echo gmdate('Y-n-j G:i ', $date); ?> | by <?php blog_author($author); ?> ]</div>
</div>
<div class="textbox-content">
<?php echo $log_content; ?>
</div>
<div class="textbox-more">
<div class="cydblink">
</div> 
</div>
<div class="textbox-bottom">
分类：<?php blog_sort($logid); ?> | <a href="<?php echo $value['log_url']; ?>#comments">评论(<?php echo $comnum; ?>)</a> | <a href="<?php echo $value['log_url']; ?>">阅读(<?php echo $views; ?>)</a>
</div>
</div>

<div class="article-top"><?php neighbor_log($neighborLog); ?></div>
<?php blog_comments($comments); ?>
<?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>
<div style="clear:both;"></div>
</div></div>
<?php
 include View::getView('side');
 include View::getView('footer');
?>