/**
 * Created by Lewis on 04/03/2017.
 */



function valid_lab() {
    var valid = true;
    var errorMessage = "";

    $(".input-error").removeClass("input-error"); //Removes all errors from inputs


    //Check course has been selected
    if($("#course-selector").val() == "no-selection")
    {
        $("#course-selector").addClass("input-error");
        valid = false;
        errorMessage+="\u2022 No Course Selected \n";
    }

    //Check lab name has been entered
    if($("#labname-input").val() == "")
    {
        $("#labname-input").addClass("input-error");
        valid = false
        errorMessage+="\u2022 No Lab Name Given \n";
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