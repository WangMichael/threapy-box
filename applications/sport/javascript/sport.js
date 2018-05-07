
define(function () {
    "use strict";

    return {

        display: function ($) {

            var $input  = $('.sport__input'),
                display = 'sport__list--display',
                $list   = $('.sport__list');


            // show search results as the user types
            $input.keyup(function () {

                // get inputted value
                var value = $input.val().toLowerCase();

                // search faqs
                $.each($list, function (i, item) {

                    // hide all items when nothing entered
                    if (!value.length) {
                        item.classList.remove(display);
                    }else{
                        var teamName = item.getAttribute('data-name');
                        if(teamName.toLowerCase() === value){
                            item.classList.add(display);
                        }else{
                            item.classList.remove(display);
                        }

                    }
                });
            });
        },

        setup: function($){

            var obj = this;

            $(function(){

                obj.display($);
            });
        }
    };
});