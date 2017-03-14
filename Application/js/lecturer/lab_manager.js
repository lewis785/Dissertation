/**
 * Created by Lewis on 27/02/2017.
 */

function display_table()
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/display_lab_table.php",
        dataType: 'json',
        data: {displayTable: "manage-table"},
        cache: false,
        success: function (result) {
            if (result.success)
                $("#main-text-area").html(result.table);
            else
                $("#main-text-area").html(result.error - message);
        },
        error: function (xhr, status, error) {
            alert("Error Occurred Try To Load Lab Table: " + xhr + status + error);    //Displays an alert if error occurred
        }
    });
}

function lab_markable(id,state)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/change_lab_markable.php",
        dataType: 'json',
        data: {labID: id, newState: state},
        cache: false,
        success: function(result) {
            if(result.success)
            {
                if (state === "true")
                    state = "false";
                else
                    state = "true";
                $("#check-"+id).attr("onclick",'lab_markable('+id+',"'+state+'")');
            }
            else
            {
                if (state === "true")
                    $('#myCheckbox').attr('checked', false);
                else
                    $('#myCheckbox').attr('checked', true);
                alert("Failed to update please refresh and try again");
            }
        },
        error: function(xhr, status, error) {
            alert("Error Occurred Trying To Update If Lab Can Be Marked" + xhr);    //Displays an alert if error occurred
        }
    });
}


function exportResults(labID)
{
    $("#hidden-lab-id").val(labID);
    $("#hidden-form").submit();
}



function editLab(lab_name, labID)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/editable_lab.php",
        dataType: 'json',
        data: {labID: labID},
        cache: false,
        success: function(result) {
            $("#main-text-area").html("<legend>"+lab_name+"</legend>");
            $("#main-text-area").append("<button class='col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12 btn btn-warning' onclick='display_table()'>Back</button>");
            $("#main-text-area").append(result.questions);
            $("#main-text-area").append("<button class='col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-12 btn btn-success' onclick='submitEdit()'>Submit</button>");
            $(".remove-btn").remove();
            fillLab(labID);
        },
        error: function(xhr, status, error) {
            alert("Error Occurred Trying To Retrieve Lab" + xhr);    //Displays an alert if error occurred
        }
    });

}


function fillLab(labID)
{
    $(".tile").each(function () {
        alert($(this).find("input[name='type[]']").val());

    })
}

function submitEdit()
{

}















//Creates a popup to confirm the deletion of a lab
function delete_popup(labID)
{
    $.get("../components/popup/lab-delete-confirm.php #delete-lab-popup", function (data) {     //Gets the popup div
        $("#main-text-area").append(data);                                                      //Appends the div to the screen
        $("#confirm-btn").attr("onclick", 'delete_lab('+labID+')');                             //Adds the delete function to the confirm button
    });
}



//Checks if user clicked inside of the popup if not it closes it
$(document).mouseup(function (e)
{
    var container = $("#delete-lab-div");           //Name of the div it checks the user clicked in
    if (!container.is(e.target) && container.has(e.target).length === 0)
    {
        $("#delete-lab-popup").remove();            //Removes the popup dive
    }
});


//Deletes the lab with the provided ID number
function delete_lab(labID)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/lab_manager.php",
        dataType: 'json',
        data: {labID: labID, action:"delete"},
        cache: false,
        success: function(result) {
            $("#delete-lab-popup").remove();                    //Removes the delete popup window
            if(result.success)                                  //Checks if lab was successfully deleted
            {
                $("#lab-"+labID).remove();                      //Removes the lab from the table
            }
            else
            {
                alert("Failed To Delete Lab");                  //Displays an alert stating deletion failed
            }
        },
        error: function(xhr, status, error) {
            alert("Error Occurred Try To Delete Lab" + xhr);    //Displays an alert if error occurred
        }
    });
}