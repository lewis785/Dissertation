/**
 * Created by Lewis on 20/02/2017.
 */


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