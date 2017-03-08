/**
 * Created by Lewis on 08/03/2017.
 */


function getAccessOptions()
{
    $.ajax({
        type: 'POST',
        url: "../../php/admin/accessoptions.php",
        dataType: 'json',
        data: {},
        cache: false,
        success: function(result){
            // $("#access-selector").html(result.html);
            var array = result.buttons;
            $.each(result.buttons, function(index, option){
                $(".access-selector").append($("<option></option>").attr("value", option[0]).text(option[1]));
            })
        },
        error: function(xhr, status, error) {
            alert(xhr);
        }
    });
}
