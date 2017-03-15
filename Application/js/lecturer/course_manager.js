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

                var layout = "<button class='col-md-6 col-sm-12 col-xs-12 col-md-offset-3 btn btn-warning' onclick='management_options(coursename)'>Back</button>"
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

function editLabHelpers()
{
    $.ajax({
        type: 'POST',
        url: "../../php/admin/admin_layout.php",
        dataType: 'json',
        data: {buttonType: "lab-helper-tables", course: coursename},
        async: false,
        cache: false,
        success: function (result) {
            $(".page-header").append("<div id='sub-sub-header' class='col-md-4'>> Students</div>");

            var layout = "<button class='col-md-6 col-sm-12 col-xs-12 col-md-offset-3 btn btn-warning' onclick='management_options(coursename)'>Back</button>"
            layout+=  result.layout;
            $("#main-text-area").html(layout);
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });

    tableClickable("selected-table", "remove-helper-btn");
    tableClickable("lab-helper-table", "add-helper-btn");

}




/********************************************************
 *                                                      *
 *       Start of functions for student manager         *
 *                                                      *
 *******************************************************/

function addStudent(){
    var student = $("#students-table .selected-row div#matric-num").text();

    $.ajax({
        type: 'POST',
        url: "../../php/courses/manage_students.php",
        dataType: 'json',
        data: {type:"insert", course: coursename, student: student},
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

    $.ajax({
        type: 'POST',
        url: "../../php/courses/manage_students.php",
        dataType: 'json',
        data: {type:"remove", course: coursename, student: student},
        cache: false,
        success: function (result) {
            if(result.success)
            {
                var button  = $("#remove-student-btn");
                $("#students-table tr:last").after("<tr>"+ $("#selected-table .selected-row").html() + "</tr>");
                $("#selected-table .selected-row").remove();
                button.addClass("disabled");
                button.prop("disabled", true);
                tableClickable("students-table", "add-student-btn");
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
    $.ajax({
        type: 'POST',
        url: "../../php/students/students_filter.php",
        dataType: 'json',
        data: {course: coursename, filter: filter},
        cache: false,
        success: function (result) {
            $("#students-table-div").remove();
            $("#student-search-input").after(result.layout);
            tableClickable("students-table", "add-student-btn");
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


function removeLabHelper()
{
    var student = $("#selected-table .selected-row div#matric-num").text();

    $.ajax({
        type: 'POST',
        url: "../../php/lab_helper/remove_lab_helper.php",
        dataType: 'json',
        data: {course: coursename, student: student},
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

    $.ajax({
        type: 'POST',
        url: "../../php/lab_helper/add_lab_helper.php",
        dataType: 'json',
        data: {course: coursename, student: student},
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
    $.ajax({
        type: 'POST',
        url: "../../php/lab_helper/filter_lab_helper.php",
        dataType: 'json',
        data: {course: coursename, filter: filter},
        cache: false,
        success: function (result) {
            $("#lab-helper-table-div").remove();
            $("#helper-search-input").after(result.layout);
            tableClickable("lab-helper-table", "add-helper-btn");
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });
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