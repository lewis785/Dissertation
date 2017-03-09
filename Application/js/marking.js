/**
 * Created by Lewis on 20/02/2017.
 */


/*--------------------------------------------*/
/*Beginning of functions for selecting marking*/
/*--------------------------------------------*/

function display_courses()
{
    $.ajax({
        type: 'POST',
        url: "../../php/courses/course_buttons.php",
        dataType: 'json',
        data: {type:"marking"},
        cache: false,
        success: function(result){
            $("#question-area").html(result.buttons);

        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });

}



//Function displays all the labs for a given course
function display_labs_for(course)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/get_labs.php",
        dataType: 'json',
        data: {course: course, type:"next"},
        cache: false,
        success: function(result){
            $("#question-area").html(result.buttons);

            $(document.body).append('<footer class="panel-footer fix-bottom" id="marking-submit-bar">'+
                '<button class="btn btn-warning col-md-2 col-md-offset-2 col-xs-5 col-xs-offset-1" id="back-btn" onclick="back_to_courses()">Back</button>');
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
        data: {lab:lab, type:"next"},
        cache: false,
        success: function(result){
            $("#question-area").html(result.buttons);
            $("#back-btn").attr("onclick","back_to_labs()");
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
            $("#back-btn").attr("onclick","back_to_students()");
            $("#marking-submit-bar").append('<button onclick="submit_mark()"'+
                'class="btn btn-success col-md-2 col-md-offset-1 col-xs-5" id="lab-submit-btn">Submit</button></footer>');
            get_student_marks();
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}
/*--------------------------------------*/
/*End of functions for selecting marking*/
/*--------------------------------------*/




/*------------------------------------------*/
/*Beginning of functions for go back button */
/*------------------------------------------*/

function back_to_courses()
{
    display_courses();
    $("#marking-submit-bar").remove();
}

function back_to_labs()
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/get_labs.php",
        dataType: 'json',
        data: {type: "back"},
        cache: false,
        success: function(result){
            $("#question-area").html(result.buttons);
            $("#back-btn").attr("onclick","back_to_courses()");
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });

}

function back_to_students()
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/get_students.php",
        dataType: 'json',
        data: {type: "back"},
        cache: false,
        success: function(result){
            $("#question-area").html(result.buttons);
            $("#back-btn").attr("onclick","back_to_labs()");
            $("#lab-submit-btn").remove();
        },
        error: function(xhr, status, error) {
            alert(xhr);
            alert("error going back students");
        }
    });

}

/*------------------------------------------*/
/*  End of functions for go back button     */
/*------------------------------------------*/



/*--------------------------------------------*/
/*Beginning of functions for submitting marks */
/*--------------------------------------------*/

function submit_mark(){
    $.post("../../php/marking/submit_mark.php", $("#form-area").serialize(), function (data) {
        back_to_students();
    }).error(function () {
        alert("failure");
    });
    // $("#form-area").submit();

}

/*--------------------------------------*/
/*End of functions for submitting marks */
/*--------------------------------------*/




/*--------------------------------------------*/
/*Beginning of functions for getting student marks */
/*--------------------------------------------*/

function get_student_marks(){

    $.ajax({
        type: 'POST',
        url: "../../php/marking/get_student_answer.php",
        dataType: 'json',
        data: {},
        cache: false,
        success: function(result){

            if (result.marked === true) {

                var types = [];

                $(".question-type").each(function () {
                    types.push($(this).val());
                });

                var count = 0;
                $(".lab-input").each(function () {

                    switch (types[count]) {
                        case "scale":
                            $(this).val(result.answers[count][0]);
                            break;
                        case "boolean":
                            var value = result.answers[count][1];
                            $("#boolean-button-" + (count + 1) + "-hidden").val(value);
                            if (value === "true")
                                swap_value("boolean-button-" + (count + 1));
                            break;
                        default:
                            break;
                    }
                    count++;
                })
            }
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });

}

/*--------------------------------------*/
/*End of functions for submitting marks */
/*--------------------------------------*/