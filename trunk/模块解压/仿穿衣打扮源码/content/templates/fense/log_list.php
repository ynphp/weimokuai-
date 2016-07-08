<?php 
/**
* 首页文章列表部分
*/
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>


<!--左侧开始-->
<div id="content" class="content">
<div id="innerContent">
<?php doAction('index_loglist_top'); ?>
<?php 
if (!empty($logs)):
foreach($logs as $value): 
?>
<!--正文开始-->
<div class="textbox">
<div class="textbox-title">
<h2><span id="starid8498"><img src="<?php echo TEMPLATE_URL; ?>images/unstarred.gif" /></span><a href="<?php echo $value['log_url']; ?>"><?php echo $value['log_title']; ?></a></h2>
<div class="textbox-label">[ <?php echo gmdate('Y-n-j G:i ', $value['date']); ?> | by <?php blog_author($value['author']); ?> ]</div>
</div>
<div class="textbox-content">
<?php echo $value['log_description']; ?>
<div class="readmore"><img src="<?php echo TEMPLATE_URL; ?>images/readmore.gif" /><a href="<?php echo $value['log_url']; ?>" title="点击阅读全文">阅读全文</a></div>
</div>
<div class="textbox-bottom">
分类：<?php blog_sort($value['logid']); ?>  | <a href="<?php echo $value['log_url']; ?>#comments">评论(<?php echo $value['comnum']; ?>)</a> | <a href="<?php echo $value['log_url']; ?>">阅读(<?php echo $value['views']; ?>)</a>
</div>



</div>
<!--正文结束-->
<?php endforeach;else:?>
<h2>未找到</h2>
<p>抱歉，没有符合您查询条件的结果。</p>
<?php endif;?>
<div class="article-bottom" style="display: block">
<!--页码-->
<div class="pages"><?php echo $page_url;?></div>
<!--页码结束-->
</div>
</div>
</div>
<!--左侧结束-->

<?php
include View::getView('side');
include View::getView('footer');
?>