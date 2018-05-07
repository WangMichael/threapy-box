
define(function () {
    "use strict";

    return {

        insert: function ($) {

            var $items  = $('.task__list-item:not(.task__list-item--plus)');

            $.each($items, function (i, item) {

                var $item = $(item);

                $item.one( "click", function() {

                    var $description    = $item.children("input[type='text']"),
                        $id             = $item.children('input[type="hidden"]'),
                        $status_0       = $item.find("label > input[type='hidden']"),
                        $status_1       = $item.find("label > input[type='checkbox']");

                    $description.attr('name', 'update[' + $description.attr('name') + '][description]');
                    $id.attr('name', 'update[' + $id.attr('name') + '][id]');
                    $status_0.attr('name', 'update[' + $status_0.attr('name') + '][status]');
                    $status_1.attr('name', 'update[' + $status_1.attr('name') + '][status]');
                });

            });
        },


        update: function($){

            var $plus   = $('.task__list-item--plus');

            $plus.click(function(){

                var data_id   = parseInt($plus.attr('data-id')) + 1;
                $plus.attr('data-id', parseInt(data_id));

                var $append     = $plus.clone( true );
                $append.removeAttr('data-id');
                $append.removeClass('task__list-item--plus');


                var $description    = $append.children('input[type="text"]'),
                    $status_0       = $append.find('label > input[type="hidden"]'),
                    $status_1       = $append.find('label > input[type="checkbox"]');



                $description.attr('name', 'insert[' + data_id + '][description]');
                $status_0.attr('name', 'insert[' + data_id + '][status]');
                $status_1.attr('name', 'insert[' + data_id + '][status]');
                $append.off();
                $append.insertBefore($plus);

            });

        },

        setup: function($){

            var obj = this;

            $(function(){

                obj.insert($);
                obj.update($);
            });
        }
    };
});