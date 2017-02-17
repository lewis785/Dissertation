/**
 * Created by Lewis on 16/02/2017.
 */


function make_lab_layout(inputarea){

    var new_sidebar = "";
    var questionTypes = ["scale","boolean", "value", "text"];
    for(type in questionTypes) {
        t = questionTypes[type];
        new_sidebar += '<div class="form-group row">' +
            '<div class="col-md-10 col-md-offset-1">' +
            '<input class="form-control" type="button" onclick="add_'+ t +'_question()" value="'+t.charAt(0).toUpperCase() + t.slice(1)+' Question">' +
            '</div>' +
            '</div>';
    }



    $(document).ready(function() {
        $("#" + inputarea).load("../components/sidebar.php #sidebar_code", function () {
            $(".side_nav_area").html(new_sidebar);
        });
    });
}