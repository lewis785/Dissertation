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
            $(".page-header").html("Admin Control Panel");
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
            $(".page-header").html("Admin Control Panel > User Manager");
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
            $(".page-header").html("Admin Control Panel > User Manager > Add Users");
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
            $(".page-header").html("Admin Control Panel > User Manager > Remove Users");
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}
