
define(function () {
    "use strict";

    return {

        changeName: function ($) {

            var $label  = $('.file__upload-control'),
                $input  = $label.children('input[type="file"]'),
                $name   = $label.children('.file__upload-name');

            $input.change(
                function () {
                    if($input.val()){

                        var imageName = $input.val().replace(/^.*[\\\/]/, '');
                        if(imageName.length > 15){
                            imageName = imageName.slice(-15);
                        }
                        $name.text(imageName);
                    }
                }
            );
        },

        setup: function($){

            var obj = this;

            $(function(){

                obj.changeName($);
            });
        }
    };
});