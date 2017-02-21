/**
 * Created by Lewis on 20/02/2017.
 */


//Function displays all the labs for a given course
function display_labs_for(course)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/get_labs.php",
        dataType: 'json',
        data: {course: course},
        cache: false,
        success: function(result){
            $("#question-area").html(result.buttons);
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}

//Displays all the students in a given lab
function display_students_for(lab)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/get_students.php",
        dataType: 'json',
        data: {lab:lab},
        cache: false,
        success: function(result){
            $("#question-area").html(result.buttons);
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}

//Displays the labs schema for a lab
function display_schema_for(student)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/display_lab.php",
        dataType: 'json',
        data: {student:student},
        cache: false,
        success: function(result){
            $("#question-area").html(result.html);
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}


function back_to_courses()
{

}

function back_to_labs()
{

}

function back_to_students()
{

}