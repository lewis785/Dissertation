/**
 * Created by Lewis on 08/03/2017.
 */


function getLayout(layout) {
    $.ajax({
        type: 'POST',
        url: "../../php/admin/admin_layout.php",
        dataType: 'json',
        data: {buttonType: layout},
        async: false,
        cache: false,
        success: function (result) {
            $("#admin-panel").html(result.layout);
            // $("#sub-menu").remove();
            // // $(".page-header").html("Admin Control Panel");
        },
        error: function (xhr, status, error) {
            alert(xhr);
        }
    });

}


function mainPanel() {
    getLayout("main-panel");

    $("#sub-menu").remove();
}

function manageUsersButton() {
    getLayout("user-manager");

    $("#sub-menu").remove();
    $("#sub-sub-menu").remove();
    $(".page-header").append("<div class='col-md-3' id='sub-menu'>> User Manager");
}

function manageStudentButtons() {
    getLayout("manage-students");
    $(".page-header").append("<div class='col-md-4' id='sub-sub-menu'>> Student's");
}


/*Start of functions for loading forms*/

function addUserForm() {
    getLayout("create-user");

    $(".page-header").append("<div class='col-md-3' id='sub-sub-menu'>> Add Users");
}


function removeUserForm() {
    getLayout("remove-user");

    $(".page-header").append("<div class='col-md-3' id='sub-sub-menu'>> Remove Users");
}

function updateUserForm() {
    getLayout("update-user");
    $(".page-header").append("<div class='col-md-3' id='sub-sub-menu'>> Update Users");
}


function manageLabHelpers() {
    getLayout("manage-lab-helper")
    $(".page-header").append("<div class='col-md-4' id='sub-sub-menu'>> Lab Helper's");

    var table = $("#lab-helper-table").find("tr");

    table.bind('click', function () {
        var matric = $(this).find("div#matric-num").text();

        if(!$("#selected-table #"+matric).length)
            $("#selected-table tr:last").after('<tr id="'+ matric +'">'+$(this).html()+'</tr>');
    });


}

function manageLecturers() {
    getLayout("manage-lecturer")
    $(".page-header").append("<div class='col-md-4' id='sub-sub-menu'>> Lecturer's ");
}


function manageStudentsForm() {
    getLayout("manage-students");

    // $(".page-header").append("<div class='col-md-3' id='sub-sub-menu'>> Manage Students");
}
