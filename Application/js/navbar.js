/**
 * Created by Lewis on 28/01/2017.
 */

function include_navbar (section) {

    $(document).ready(function(){
        $("#navbar_area").load("../components/navbar.php #navbar_code", function () {
            $("#" + section +"-nav").addClass("active");
        });
    });
}


function include_bottom_navbar () {

    $(document).ready(function(){
        $("#bottom-nav-area").load("../components/bottom-navbar.php #bottom-bar-code");
    });
}





