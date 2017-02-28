/**
 * Created by Lewis on 27/02/2017.
 */

function display_table()
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/display_lab_table.php",
        dataType: 'json',
        data: {display_table: "manage-table"},
        cache: false,
        success: function (result) {
            if (result.success)
                $("#main-text-area").html(result.table);
            else
                $("#main-text-area").html(result.error - message);
        },
        error: function (xhr, status, error) {
            alert("Error Occurred Try To Load Lab Table: " + xhr);    //Displays an alert if error occurred
        }
    });


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
        url: "../../php/labs/delete_lab.php",
        dataType: 'json',
        data: {lab_id: labID},
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