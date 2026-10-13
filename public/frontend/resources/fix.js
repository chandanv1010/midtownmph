var time = 0;

$(document).ready(function(){

    $(document).on('submit', '#contact_footer', function(){
        let _this = $(this);

        var recaptchaResponse = grecaptcha.getResponse();

        if (!recaptchaResponse) {
            alert('Vui lòng xác minh.');
            return false;
        }

        let loader = _this.find('.bg-loader');
        let lang = _this.attr('data-lang');
        
        loader.show();

        

        let postData = _this.serializeArray();
        let fullname =  _this.find('.fullname').val();
        let email =  _this.find('.email').val();
        let phone =  _this.find('.phone').val();
        let message =  _this.find('.message').val();

		let validate = {
            'fullname': _this.find('.fullname').closest('.dt_validate').attr('data-validate'),
            'phone': _this.find('.phone').closest('.dt_validate').attr('data-validate'),
            'email': _this.find('.email').closest('.dt_validate').attr('data-validate'),
            'message': _this.find('.message').closest('.dt_validate').attr('data-validate'),
        };

        var formdata = new FormData(this);
		formdata.append('email', email);
		formdata.append('fullname', fullname);
		formdata.append('phone', phone);
		formdata.append('message', message);
		formdata.append('validate', JSON.stringify(validate));
        formdata.append('g-recaptcha-response', recaptchaResponse); // 👈 dòng quan trọng!
        
		// console.log(formdata);
		// return false;

        let ajaxUrl = 'ajax/contact/contact_footer';

		clearTimeout(time);
		time = setTimeout(function(){
			$.ajax({
				url: ajaxUrl,
				language: lang,
				type: 'post',
				data: formdata,
				dataType: "json",
				processData: false,
				contentType: false,
				cache: false,
				success: function(json){
                    loader.hide();
                    if(json.error.flag == 1){
                        // _this.find('.error').removeClass('hidden');
                        // _this.find('.error .alert').html(json.error.message);
                        alert(json.error.message);
                    }else{
                        // _this.find('.error').addClass('hidden');
                        _this.find('.input-reset').val('');
                        _this.find('.input-select').val(0).trigger('change');

                        toastr.options.closeButton = true;
                        toastr.options.preventDuplicates = true;
                        toastr.options.progressBar = true;
                        toastr.success(json.error.message);
                        // location.reload();
                    }
                    grecaptcha.reset();
                },
                error: function() {
                    loader.hide()
                    grecaptcha.reset();

                }
			});
		}, 300);

        return false;
    });


});
