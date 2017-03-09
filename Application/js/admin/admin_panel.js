/**
 * Created by Lewis on 08/03/2017.
 */



function mainPanel()
{
    $.ajax({
        type: 'POST',
        url: "../../php/admin/admin_layout.php",
        dataType: 'json',
        data: {buttonType: "main-panel"},
        cache: false,
        success: function(result){
            $("#admin-panel").html(result.buttons);
            $("#sub-menu").remove();
            // $(".page-header").html("Admin Control Panel");
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}



function manageUsersButton()
{
    $.ajax({
        type: 'POST',
        url: "../../php/admin/admin_layout.php",
        dataType: 'json',
        data: {buttonType: "user-manager"},
        cache: false,
        success: function(result){
            $("#admin-panel").html(result.buttons);
            $("#sub-menu").remove();
            $("#sub-sub-menu").remove();
            $(".page-header").append("<div class='col-md-3' id='sub-menu'>> User Manager");
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}






function addUserForm()
{
    $.ajax({
        type: 'POST',
        url: "../../php/admin/admin_layout.php",
        dataType: 'json',
        data: {buttonType: "create-user"},
        cache: false,
        success: function(result){
            $("#admin-panel").html(result.form);
            $(".page-header").append("<div class='col-md-3' id='sub-sub-menu'>> Add Users");
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}


function removeUserForm()
{
    $.ajax({
        type: 'POST',
        url: "../../php/admin/admin_layout.php",
        dataType: 'json',
        data: {buttonType: "remove-user"},
        cache: false,
        success: function(result){
            $("#admin-panel").html(result.form);
            $(".page-header").append("<div class='col-md-3' id='sub-sub-menu'>> Remove Users");
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}
