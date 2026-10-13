$(document).ready(function(){
	$(".js_default").on('change', function (e) {
		let _this = $(this);
		let id = _this.attr('data-id');

		// console.log(id);
		// return false;

		let formURL = 'ajax/currency/default_currency';
			$.post(formURL, {
				id: id},
				function(data){
					// window.location.reload();
				}
			);

		return false;
	});
});