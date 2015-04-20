jQuery(function ($) {
	//parent.$('#basic-modal-content').modal();
	$('#basic-modal .basic').click(function (e) {
		parent.$('#basic-modal-content').modal();
		return false;
	});
});