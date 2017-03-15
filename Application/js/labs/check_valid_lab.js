/**
 * Created by Lewis on 04/03/2017.
 */


function valid_lab() {
    var valid = true;
    var errorMessage = "";

    $(".input-error").removeClass("input-error"); //Removes all errors from inputs

    if($("#course-selector").length > 0) {
        var courseVal = $("#course-selector").val();
        var labVal = $("#labname-input").val();

        //Check course has been selected
        if (courseVal == "no-selection") {
            $("#course-selector").addClass("input-error");
            valid = false;
            errorMessage += "\u2022 No Course Selected \n";
        }

        //Check lab name has been entered
        if (labVal == "") {
            $("#labname-input").addClass("input-error");
            valid = false;
            errorMessage += "\u2022 No Lab Name Given \n";
        }

        //Checks if form is currently valid
        if (valid) {
            //Checks if lab name already exists
            $.ajax({
                type: 'POST',
                url: "../../php/lecturer/check_lab_name.php",
                dataType: 'json',
                data: {course: courseVal, lab: labVal},
                async: false,
                cache: false,
                success: function (result) {
                    if (result.exists === true) {
                        valid = false;
                        $("#labname-input").addClass("input-error");
                        errorMessage += "\u2022 Lab Name Already Exists \n";
                    }
                },
                error: function (xhr, status, error) {
                    alert("Error Occurred Try To Check Lab Name: " + xhr + status + error);    //Displays an alert if error occurred
                    valid = false;
                }
            });

        }
    }

    //Check questions have been added to the lab
    if($(".question-input").length > 0) {
        var error = "";

        //Check all question inputs have text
        $(".question-input").each(function () {
            if ($(this).val() == "") {
                $(this).addClass("input-error");
                valid = false;
                if (error === "")
                    error = "\u2022 No Question Text Given \n";
            }
        });

        errorMessage+=error;
        error = "";

        //Check all input drop menus have been set
        $(".dropdown-input").each(function () {
            if ($(this).val() == "no-selection") {
                $(this).addClass("input-error");
                valid = false;
                if (error === "")
                    error = "\u2022 No Value For Question Set \n";
            }
        });
        errorMessage+=error;
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