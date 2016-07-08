<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.012wz.com/ for more details.
 */
defined('IN_IA') or exit('Access Denied');

function tpl_wappage_editor($editorparams = '', $editormodules = array()) {
	global $_GPC;
	$content = '';
	load()->func('file');
	$filetree = file_tree(IA_ROOT . '/addons/fm_photosvote/template/designer/wapeditor');
	if (!empty($filetree)) {
		foreach ($filetree as $file) {
			if (strexists($file, 'widget-')) {
				$fileinfo = pathinfo($file);
				$_GPC['iseditor'] = false;
				$display = template('../../../addons/fm_photosvote/template/designer/wapeditor/'.$fileinfo['filename'], TEMPLATE_FETCH);
				$_GPC['iseditor'] = true;
				$editor = template('../../../addons/fm_photosvote/template/designer/wapeditor/'.$fileinfo['filename'], TEMPLATE_FETCH);
				$content .= "<script type=\"text/ng-template\" id=\"{$fileinfo['filename']}-display.html\">".str_replace(array("\r\n", "\n", "\t"), '', $display)."</script>";
				$content .= "<script type=\"text/ng-template\" id=\"{$fileinfo['filename']}-editor.html\">".str_replace(array("\r\n", "\n", "\t"), '', $editor)."</script>";
			}
		}
	}
	return $content;
}