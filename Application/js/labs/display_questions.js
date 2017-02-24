/**
 * Created by Lewis on 19/02/2017.
 */


function load_lab(labname, coursename)
{
    $.ajax({
        type: 'POST',
        url: "../../php/labs/display_lab.php",
        dataType: 'json',
        data: {lab: labname, course: coursename},
        cache: false,
        success: function(result){
            $("#question-area").html(result.html);
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });

}


function swap_value(buttonID)
{
    if($("#"+buttonID).val() === "no")
    {
        $("#" + buttonID).val("yes");
        $("#" + buttonID + "-hidden").val("true");
        $("#" + buttonID ).addClass( "btn-success" );
        $("#" + buttonID ).removeClass( "btn-danger" );

    }
    else
    {
        $("#" + buttonID).val("no");
        $("#" + buttonID + "-hidden").val("false");
        $("#" + buttonID ).addClass( "btn-danger" );
        $("#" + buttonID ).removeClass( "btn-success" );
    }
}