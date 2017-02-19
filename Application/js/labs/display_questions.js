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
            $("#main-text-area").html(result.html);
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });

}