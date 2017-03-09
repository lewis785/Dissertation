/**
 * Created by Lewis on 09/03/2017.
 */


function addUser() {

    var valid = true;
    var selector = $(".access-selector");
    $("form#add-user-form :input").each(function () {
        $(this).removeClass("input-error");

        if($(this).val() === "")
        {
            if(this.id !== "matric-input") {
                $(this).addClass("input-error");
                valid = false;
            }
        }
        else if(this.id === "matric-input")
        {
            var matricID = new RegExp("[a-zA-Z][0-9]{8}");
            if(!matricID.test($(this).val()))
            {
                $(this).addClass("input-error");
                valid = false;
            }
        }
    });

    if(selector.val() === "no-selection")
    {
        selector.addClass("input-error");
        valid = false;
    }
    else
        selector.removeClass("input-error");

    if(valid) {
        $.post("../../php/admin/adduser.php", $("#add-user-form").serialize(), function () {
            alert("Submitted");
            $('#add-user-form').trigger("reset");
        });
    }
}



