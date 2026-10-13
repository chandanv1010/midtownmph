(function($) {
	"use strict";
    var HT = {};

    var time = 100;
	/* MAIN VARIABLE */

    var $window            		= $(window),
		$document           	= $(document),
        $cart_button            = $('.add-cart'),
        $cart_modal            = $('.add_to_cart');

	// Check if element exists
    $.fn.elExists = function() {
        return this.length > 0;
    };

    HT.add_cart = () => {
        if($cart_button.elExists){
            $cart_button.on('click', function(){
                let _this = $(this);
                let sku = _this.attr('data-sku');
                // let qty = $('#qtym').val();
                let qty = 1;
                let ajaxUrl = 'ajax/frontend/cart/insert';
                if(qty > 0){
                    $.post(ajaxUrl, {
                        sku: sku, qty: qty},
                        function(data){
                            let json = JSON.parse(data);
                            if(json.response.code == 10){
                                $('.bag .number').html(json.response.totalItems);
                                toastr.success('Thêm sản phẩm vào giỏ hàng thành công!');
                                location.href = BASE_URL+'gio-hang.html';
                            }else{
                                toastr.error('Có lỗi xảy ra, vui lòng thử lại, mã lỗi: !' + json.response.code);
                            }
                        });
                }else{
                    toastr.error('Có lỗi xảy ra, bạn phải mua ít nhất 1 sản phẩm');
                }

            });
        }
    }

    HT.add_cart_modal = () => {
        if($cart_modal.elExists){
            $cart_modal.on('click', function(){
                let _this = $(this);
                let sku = _this.attr('data-sku');
                // let qty = $('#qtym_modal').val();
                let qty = 1;
                let ajaxUrl = 'ajax/frontend/cart/insert';
                if(qty > 0){
                    $.post(ajaxUrl, {
                        sku: sku, qty: qty},
                        function(data){
                            let json = JSON.parse(data);
                            if(json.response.code == 10){
                                $('.bag .number').html(json.response.totalItems);
                                toastr.success('Thêm sản phẩm vào giỏ hàng thành công!');
                            }else{
                                toastr.error('Có lỗi xảy ra, vui lòng thử lại, mã lỗi: !' + json.response.code);
                            }
                        });
                }else{
                    toastr.error('Có lỗi xảy ra, bạn phải mua ít nhất 1 sản phẩm');
                }

            });
        }
    }

    HT.render_cart = (sum) => {
        $('.new_price').each(function(){
            let abc = $(this).html();
            abc = parseFloat(abc.replaceAll('.',''))
            sum = sum +abc;
        })
        let discount = parseFloat($('#total_discount').attr('data-price')) / 100;
        let shipping = parseFloat($('#total_shipping').attr('data-price')) ;
        let new_sum= 0;
        new_sum = (sum * discount) + shipping;
        sum = sum.toString()
        new_sum = new_sum.toString()
        $('#subtotal').html(format_curency(sum) + 'đ');
        $('.pay_total').val(sum);
        $('#total').html(format_curency(new_sum) + 'đ');
        $('#total_primary').html(format_curency(new_sum) + 'đ');
    }
    HT.update = (__this, type) => {
        let _this = __this;
        let price = _this.parents('li').find('.price_view').html();
        let code = _this.parents('li').find('.productid_hidden').val();
        let quantity;
        if(type == 'button'){
             quantity = _this.siblings('.input-quantity').val()
        }else{
             quantity = _this.val()
        }
        let new_price = 0;
        let sum = 0;
        price = parseFloat(price.replaceAll('.',''))
        new_price = price*quantity;
        let form_URL = 'ajax/frontend/cart/change_quantity';
        $.post(form_URL, {
            quantity: quantity,code:code
        },
        function(data){
            new_price = new_price.toString()
            _this.parents('li').find('.new_price').html(format_curency(new_price) + 'đ');
            HT.render_cart(sum);
        });
    }

    HT.cart_update = () => {
        $('.input-quantity').on('change', function(){
            HT.update($(this));
    	})
        $('.button_quantity_cart').on('click', function(){
    		HT.update($(this), 'button');
    	})
    }

    HT.cart_remove = () => {
        $('.fc-cart-remove').on('click', function(){
    		let _this = $(this)
    		let code = _this.parents('li').find('.productid_hidden').val();
    		let form_URL = 'ajax/frontend/cart/remove';
    		let sum = 0;

    		$.post(form_URL, {
    			code: code
    		},
    		function(data){
    			_this.parents('li').remove()
    			let count = $('.list-product-cart li').length;
    			$('.panel-head .count').html(count+' Sản phẩm');
    			if(count == 0){
    				$('.list-product-cart').remove();
    				$('.panel-body ').append('<p class="info-cart text-danger">Chưa có đơn hàng nào được chọn</p>');
    				$('#subtotal').html(0);
    				$('#total').html(0);
    			}else{
                    HT.render_cart(sum);
    			}
    		});
    	})
    }





  // Document ready functions
    $document.on('ready', function() {
        HT.add_cart();
        HT.cart_update();
        HT.cart_remove();
        HT.add_cart_modal();
    });

})(jQuery);


function format_curency(data) {
    let format = data.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
    return format;
    }
