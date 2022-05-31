'use strict';


function showProductFromLocalStorage() {
    console.log('showProductFromLocalStorage', localStorage.length);

    for(let i = 0; i < localStorage.length; i++) {
        let key = localStorage.key(i) || '';
        let splitKey = key.split('_');
        
        if(splitKey[0] === 'product') {
            let productId = splitKey[1];

            console.log(localStorage.getItem(key) + ' ' + productId);

            let _token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: "/basket/get-data",
                type: "POST",
                data: {
                    productId: productId,
                    lang: $('.app_lang').val(),
                    _token: _token
                },
                success: function(response) {
                    console.log(response);
                    
                    if(response) {
                        $('.basket__product_template').first().clone().appendTo('.basket__products_list');

                        $('.basket__product_template').last().find('.basket__product_id').val(productId);
                        $('.basket__product_template').last().find('.basket__product_amount').val(localStorage.getItem(key));

                        $('.basket__product_template').last().find('.basket__product_title').html(response['title']);
                        $('.basket__product_template').last().find('.basket__product_price').html(response['price']);
                        $('.basket__product_template').last().find('.basket__product_price_sum').html(parseFloat(response['price'] * localStorage.getItem(key)).toFixed(2));
                        
                        $('.basket__product_template').last().find('.basket__product_image').attr('src', '/storage/images/modules/products/step_1/' + productId + '.jpg');
                    }

                    if(i == localStorage.length - 1) {
                        $('.basket__product_template').first().remove();

                        setBasketSum();

                        $('.basket__product_amount').inputFilter(function(value) {
                            return /^\d*$/.test(value); // Allow digits only, using a RegExp
                        }, 'Only digits allowed');

                        $('.basket__product_amount').keyup(function() {
                            setProductSum();
                        });


                        $('.basket__product_delete_button').on('click', function() {
                            removeProductFromBasket($(this));
                        });
                    }
                },
            });
        }
    }
}


function removeProductFromBasket(element) {
    let id = element.parents('.basket__product_template').find('.basket__product_id').val();

    element.parents('.basket__product_template').remove();

    localStorage.removeItem('product_' + id);

    setBasketSum();

    checkBasketEmptiness();
}


function setBasketSum() {
    let sum = 0;

    $('.basket__product_price_sum').each(function() {
        sum += parseFloat($(this).text());
    });
    
    $('.basket__sum').text(parseFloat(sum).toFixed(2));
}


function checkBasketEmptiness() {
    let productsInBasket = 0;

    for(let i = 0; i < localStorage.length; i++) {
        let key = localStorage.key(i) || '';
        let splitKey = key.split('_');
        
        if(splitKey[0] === 'product') {
            productsInBasket++;
        }
    }

    // alert(productsInBasket);

    if(productsInBasket > 0) {
        $('.empty_basket').addClass('d-none');
        $('.basket').removeClass('d-none');

        // alert(1);
    } else {
        $('.empty_basket').removeClass('d-none');
        $('.basket').addClass('d-none');

        // alert(0);
    }
}


function setProductSum() {
    $('.basket__product_price_sum').each(function() {
        let price = parseFloat($(this).parents('.basket__product_template').find('.basket__product_price').text()).toFixed(2);
        let amount = parseInt($(this).parents('.basket__product_template').find('.basket__product_amount').val());

        if(isNaN(amount)) {
            amount = 0;
        }
        
        console.log(price, amount);

        $(this).text(parseFloat(price * amount).toFixed(2));
    });

    setBasketSum();
}


function deleteProductFromLocalStorage() {
    let localStorageLenght = localStorage.length;
    let localStorageData = localStorage;
    let products = {};

    for(let i = 0; i < localStorageLenght; i++) {
        let key = localStorageData.key(i) || '';
        let splitKey = key.split('_');
        
        if(splitKey[0] === 'product') {
            products[key] = localStorage.getItem(key);
        }
    }
    
    let productsLength = Object.keys(products).length;
    
    for(let i = 0; i < productsLength; i++) { 
        localStorage.removeItem(Object.keys(products)[i]);
    }
}


$(document).ready(function() {
    showProductFromLocalStorage();

    checkBasketEmptiness();

    $('.single_product__add_to_basket_button').on('click', function() {
        localStorage.setItem('product_' + $('#active_product_step1_id').val(), $('#tentacles').val());

		Swal.fire({
			title: $('.js_product_added_in_basket').val(),
			icon: 'success'
		})
    });
});


// Restricts input for the set of matched elements to the given inputFilter function.
    (function($) {
        $.fn.inputFilter = function(callback, errMsg) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
            if (callback(this.value)) {
            // Accepted value
            if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
                $(this).removeClass("input-error");
                this.setCustomValidity("");
            }
            this.oldValue = this.value;
            this.oldSelectionStart = this.selectionStart;
            this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
            // Rejected value - restore the previous one
            $(this).addClass("input-error");
            this.setCustomValidity(errMsg);
            this.reportValidity();
            this.value = this.oldValue;
            this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
            // Rejected value - nothing to restore
            this.value = "";
            }
        });
        };
    }(jQuery));
// 