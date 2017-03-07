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
    alert(coursename);
}