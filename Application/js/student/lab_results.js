/**
 * Created by Lewis on 03/03/2017.
 */


function change_div_size(divID)
{
    var selected = $("#result-row-"+divID);
    var count = 0


    $(".results-lab-row").each(function () {
        if($(this).css("height") != "80px") {
            $(this).animate({height: '80px'}, 500);
                // .replace("glyphicon-triangle-bottom", "glyphicon-triangle-right");
            $("#result-row-arrow-"+count).toggleClass("glyphicon-triangle-right glyphicon-triangle-bottom");
        }
        count ++;
    });


    if(selected.css("height") == "80px") {
        var curHeight = selected.height();
        selected.css("height", "auto");
        var newHeight = selected.height();
        selected.height(curHeight).animate({height: newHeight}, 500);
        $("#result-row-arrow-"+divID).toggleClass("glyphicon-triangle-right glyphicon-triangle-bottom");
    }

}