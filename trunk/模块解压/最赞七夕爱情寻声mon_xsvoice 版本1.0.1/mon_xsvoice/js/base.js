$.fn.placeholder = function() {
	var $obj = this;
	var v=$(this).val();
	$obj.focus(function(event) {
		if ($obj.val() == v) {
			$obj.val("");
		}
	});
	$obj.blur(function(event) {
		if($obj.val() == ""){
			$obj.val(v);
		}
	});
}
$.fn.tabs = function() {
	var $obj = this;
	var $tabs = $obj.find(".ts >.t");
	var $cnts = $obj.find(".cs >.c");

	$tabs.click(function(event) {
		var i = $tabs.index(this);
		$cnts.hide();
		$cnts.eq(i).show();

		$tabs.removeClass('on');
		$(this).addClass('on');

		return false;
	});
	$tabs.first().click();
}