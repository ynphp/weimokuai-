<?php 
/**
 * 自建页面模板
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<?php if ($log_title == "图片墙"): ?>
<div class="picwall">
<div class="innerpicwall">
<?php get_rand_log();?>
<div class="picwall_refresh"><a href="" target="_self" title="点击刷新图片">刷新图片</a></div>
<div class="article-bottom" style="display: none">
<div class="pages">
</div>
</div>
</div>
</div>

<?php else:?>
<div id="content" class="content">
<div id="innerContent">
<div class="textbox">

	<div class="textbox-title">

		<h1><span id="starid8350"><img src="<?php echo TEMPLATE_URL; ?>images/unstarred.gif" /></span><?php echo $log_title; ?></h1>
	</div>

	<div class="textbox-content">
<?php echo $log_content; ?>

	</div>

<div class="textbox-more">
<div class="cydblink">
</div> 
</div>

</div>
	<?php blog_comments($comments); ?>
	<?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>
	<div style="clear:both;"></div>
</div></div>
<?php
 include View::getView('side');
?><?php endif; ?>
<?php
 include View::getView('footer');
?>