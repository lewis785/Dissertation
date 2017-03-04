/**
 * Created by Lewis on 04/03/2017.
 */


function check_lab_name(course,lab)
{
    $.ajax({
        type: 'POST',
        url: "../../php/lecturer/lab/check_lab_name.php",
        dataType: 'json',
        data: {course:course, lab:lab},
        cache: false,
        success: function (result) {
            alert(result.exists);
            return result.exists;
        },
        error: function (xhr, status, error) {
            alert("Error Occurred Try To Check Lab Name: " + xhr + status + error);    //Displays an alert if error occurred
            return false;
        }
    });
}



function valid_lab() {
    var valid = true;
    var errorMessage = "";

    $(".input-error").removeClass("input-error"); //Removes all errors from inputs

    var courseVal = $("#course-selector").val();
        var labVal = $("#labname-input").val();


    //Check course has been selected
    if(courseVal == "no-selection")
    {
        $("#course-selector").addClass("input-error");
        valid = false;
        errorMessage+="\u2022 No Course Selected \n";
    }

    //Check lab name has been entered
    if(labVal == "")
    {
        $("#labname-input").addClass("input-error");
        valid = false;
        errorMessage+="\u2022 No Lab Name Given \n";
    }

    if(valid)
    {
        if(check_lab_name(courseVal, labVal)) {
            valid = false;
            $("#labname-input").addClass("input-error");
            errorMessage+="\u2022 Lab Name Already Exists";
        }
    }



    if($(".question-input").length > 0) {

        //Check all question inputs have text
        $(".question-input").each(function () {
            if ($(this).val() == "") {
                $(this).addClass("input-error");
                valid = false;
            }
        });

        //Check all input drop menus have been set
        $(".dropdown-input").each(function () {
            if ($(this).val() == "no-selection") {
                $(this).addClass("input-error");
                valid = false;
            }
        });
    }
    else
    {
        valid = false;
        errorMessage+="\u2022 No Questions Added";
    }



    if(!valid)
        alert(errorMessage);

    return valid;
}



function submit_new_lab()
{
    if (valid_lab())
        $('#form-area').get(0).submit();
}