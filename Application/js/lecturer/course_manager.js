/**
 * Created by Lewis on 02/03/2017.
 */

var coursename = "";

function display_manageable_courses()
{
    $.ajax({
        type: 'POST',
        url: "../../php/courses/course_buttons.php",
        dataType: 'json',
        data: {type:"manage"},
        cache: false,
        success: function (result) {
            $("#sub-header").remove();
            $("#main-text-area").html(result.buttons);
            coursename = "";
        }
        ,
        error: function (xhr, status, error) {
            alert("Error Occurred Try To Load Lab Table: " + xhr + status + error);    //Displays an alert if error occurred
        }
    });

}



function management_options(course)
{
     coursename = course;

    $.ajax({
        type: 'POST',
        url: "../../php/lecturer/manage_buttons.php",
        dataType: 'json',
        data: {},
        cache: false,
        success: function (result) {
            $("#sub-header").remove();
            $("#sub-sub-header").remove();
            $(".page-header").append("<div id='sub-header' class='col-md-5'>> "+ course+"</div>");
            $("#main-text-area").html(result.buttons);
        }
        ,
        error: function (xhr, status, error) {
            alert("Error Occurred Try To Load Lab Table: " + xhr + status + error);    //Displays an alert if error occurred
        }
    });

}

function back_to_courses()
{
    $.ajax({
        type: 'POST',
        url: "../../php/lecturer/course/manage_buttons.php",
        dataType: 'json',
        data: {},
        cache: false,
        success: function (result) {
            $("#main-text-area").html(result.buttons);
        }
        ,
        error: function (xhr, status, error) {
            alert("Error Occurred Try To Load Lab Table: " + xhr + status + error);    //Displays an alert if error occurred
        }
    });

}


function edit_students()
{
    // alert(coursename);

        $.ajax({
            type: 'POST',
            url: "../../php/admin/admin_layout.php",
            dataType: 'json',
            data: {buttonType: "student-courses-table", course: coursename},
            async: false,
            cache: false,
            success: function (result) {
                $(".page-header").append("<div id='sub-sub-header' class='col-md-4'>> Students</div>");

                var layout = "<button class='col-md-6 col-md-offset-3 btn btn-warning' onclick='management_options(coursename)'>Back</button>"
                layout+=  result.layout;
                $("#main-text-area").html(layout);
            },
            error: function (xhr, status, error) {
                alert(xhr);
            }
        });


        tableClickable("selected-table", "remove-student-btn");
        tableClickable("students-table", "add-student-btn");

}


function tableClickable(table_name, button_name)
{
    var table = $("#"+table_name + " tbody").find("tr");
    var button = $("#"+button_name);

    table.bind('click', function () {

        $("#"+table_name).find(".selected-row").removeClass("selected-row");

        button.removeClass("disabled");
        button.prop("disabled", false);

        $(this).addClass("selected-row");
    });
}