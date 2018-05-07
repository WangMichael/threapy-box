(function () {
    "use strict";

    require(["jquery", "applications/login/javascript/login"], function ($, login) {
        login.setup($);
    });

    require(["jquery", "applications/sport/javascript/sport"], function ($, sport) {
        sport.setup($);
    });

    require(["jquery", "applications/clothes/javascript/clothes"], function ($, clothes) {
        clothes.setup($);
    });

    require(["jquery", "applications/task/javascript/task"], function ($, task) {
        task.setup($);
    });

}());