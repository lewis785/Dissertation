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


/********************************************************
 *                                                      *
 *       Start of functions for student manager         *
 *                                                      *
 *******************************************************/

function courseStudents(choice) {
    var value = $(choice).val();
    if (value !== "no-selection") {

        if (value !== "no-selection") {
            $.ajax({
                type: 'POST',
                url: "../../php/admin/admin_layout.php",
                dataType: 'json',
                data: {buttonType: "student-courses-table", course: value},
                async: false,
                cache: false,
                success: function (result) {
                    $("#tables-area").html(result.layout);
                },
                error: function (xhr, status, error) {
                    alert(xhr);
                }
            });

        }
        tableClickable("selected-table", "remove-student-btn");
        tableClickable("students-table", "add-student-btn");
    }
    else
        $("#tables-area").empty();

}


function addStudent(){
    var student = $("#students-table .selected-row div#matric-num").text();
    var course = $("select").val();

    $.ajax({
        type: 'POST',
        url: "../../php/courses/manage_students.php",
        dataType: 'json',
        data: {type:"insert", course: course, student: student},
        cache: false,
        success: function (result) {
            if(result.success) {
                var button  = $("#add-student-btn");
                $("#selected-table tr:last").after("<tr>"+ $("#students-table .selected-row").html() + "</tr>");
                $("#students-table .selected-row").remove();
                button.addClass("disabled");
                button.prop("disabled", true);
                tableClickable("selected-table", "remove-student-btn");
            }
            else
                alert("Failed to add");
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });
}

function removeStudent()
{
    var student = $("#selected-table .selected-row div#matric-num").text();
    var course = $("select").val();

    $.ajax({
        type: 'POST',
        url: "../../php/courses/manage_students.php",
        dataType: 'json',
        data: {type:"remove", course: course, student: student},
        cache: false,
        success: function (result) {
            if(result.success)
            {
                var button  = $("#remove-student-btn");
                $("#students-table tr:last").after("<tr>"+ $("#selected-table .selected-row").html() + "</tr>");
                $("#selected-table .selected-row").remove();
                button.addClass("disabled");
                button.prop("disabled", true);
                tableClickable("lab-helper-table", "add-student-btn");
            }
            else
                alert("Failed to remove");
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });
}

function filterStudents(filter)
{
    var course = $("select").val();
    $.ajax({
        type: 'POST',
        url: "../../php/students/students_filter.php",
        dataType: 'json',
        data: {course: course, filter: filter},
        cache: false,
        success: function (result) {
            $("#students-table-div").remove();
            $("#student-search-input").after(result.layout);
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });
}




/********************************************************
 *                                                      *
 *     Start of functions for lab helper manager        *
 *                                                      *
 *******************************************************/

function courseLabHelpers(choice)
{

    var value = $(choice).val();

    if(value !== "no-selection") {
        $.ajax({
            type: 'POST',
            url: "../../php/admin/admin_layout.php",
            dataType: 'json',
            data: {buttonType: "lab-helper-tables", course: value},
            async: false,
            cache: false,
            success: function (result) {
                $("#tables-area").html(result.layout);
            },
            error: function (xhr, status, error) {
                alert(xhr);
            }
        });




        tableClickable("selected-table", "remove-helper-btn");
        tableClickable("lab-helper-table", "add-helper-btn");
    }
    else
        $("#tables-area").empty();
}

function removeLabHelper()
{
    var student = $("#selected-table .selected-row div#matric-num").text();
    var course = $("select").val();

    $.ajax({
        type: 'POST',
        url: "../../php/lab_helper/remove_lab_helper.php",
        dataType: 'json',
        data: {course: course, student: student},
        cache: false,
        success: function (result) {
            if(result.success)
            {
                var button  = $("#remove-helper-btn");
                $("#lab-helper-table tr:last").after("<tr>"+ $("#selected-table .selected-row").html() + "</tr>");
                $("#selected-table .selected-row").remove();
                button.addClass("disabled");
                button.prop("disabled", true);
                tableClickable("lab-helper-table", "add-helper-btn");
            }
            else
                alert("Failed to remove");
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });
}

function addLabHelper(){
    var student = $("#lab-helper-table .selected-row div#matric-num").text();
    var course = $("select").val();

    $.ajax({
        type: 'POST',
        url: "../../php/lab_helper/add_lab_helper.php",
        dataType: 'json',
        data: {course: course, student: student},
        cache: false,
        success: function (result) {
            if(result.success) {
                var button  = $("#add-helper-btn");
                $("#selected-table tr:last").after("<tr>"+ $("#lab-helper-table .selected-row").html() + "</tr>");
                $("#lab-helper-table .selected-row").remove();
                button.addClass("disabled");
                button.prop("disabled", true);
                tableClickable("selected-table", "remove-helper-btn");
            }
            else
                alert("Failed to remove");
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });

}

function filterHelpers(filter)
{
    var course = $("select").val();
    $.ajax({
        type: 'POST',
        url: "../../php/lab_helper/filter_lab_helper.php",
        dataType: 'json',
        data: {course: course, filter: filter},
        cache: false,
        success: function (result) {
            $("#lab-helper-table-div").remove();
            $("#helper-search-input").after(result.layout);
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });
}



/********************************************************
 *                                                      *
 *     Start of functions for lecturer manager          *
 *                                                      *
 *******************************************************/

function courseLecturers(choice)
{

    var value = $(choice).val();

    if(value !== "no-selection") {
        $.ajax({
            type: 'POST',
            url: "../../php/admin/admin_layout.php",
            dataType: 'json',
            data: {buttonType: "lecturer-tables", course: value},
            async: false,
            cache: false,
            success: function (result) {
                $("#tables-area").html(result.layout);
            },
            error: function (xhr, status, error) {
                alert(xhr);
            }
        });


        tableClickable("selected-table", "remove-lecturer-btn");
        tableClickable("lecturer-table", "add-lecturer-btn");
    }
    else
        $("#tables-area").empty();
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