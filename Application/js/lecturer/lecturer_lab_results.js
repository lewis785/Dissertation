/**
 * Created by Lewis on 03/03/2017.
 */


$(document).ready(function () {

    $(".results-lab-row").click(function (e) {
        var clicked = (e.target.nodeName);
        if(clicked === "OPTION" || clicked === "SELECT")
            return;
        else
            open_close_div(this);
    });

});



function open_close_div(divID)
{
    var selected = $(divID);
    // var count = 0;
    var open = $(".open-result");

    open.animate({height: '80px'}, 500);
    $(".open-result div.glyphicon-triangle-bottom").removeClass("glyphicon-triangle-bottom").addClass("glyphicon-triangle-right");
    open.removeClass("open-result");


    // $(".results-lab-row").each(function () {
    //     if($(this).css("height") != "80px") {
    //         $(this).animate({height: '80px'}, 500);
    //             // .replace("glyphicon-triangle-bottom", "glyphicon-triangle-right");
    //         $("#result-row-arrow-"+count).toggleClass("glyphicon-triangle-right glyphicon-triangle-bottom");
    //     }
    //     count ++;
    // });


    if(selected.css("height") == "80px") {
        var curHeight = selected.height();
        selected.css("height", "auto");
        var newHeight = selected.height();
        selected.height(curHeight).animate({height: newHeight}, 500);
        selected.addClass("open-result");
        selected.find("div[id^='result-row-arrow-']").toggleClass("glyphicon-triangle-right glyphicon-triangle-bottom");
    }
}


function change_div_size(row)
{
        var curHeight = row.height();
        row.css("height", "auto");
        var newHeight = row.height();
        row.height(curHeight).animate({height: newHeight}, 500);
}

function display_student_result(input, course, student)
{
    var id = "#" + $(input).parent().parent().attr("id");
    var row = $(id);
    var lab = row.find("#lab-name").text();

    var nameText =  row.find("#student-name");
    var statsText = row.find("#student-stats");
    var answersText = row.find("#student-answers");

    if(student === "no-selection")
    {
        nameText.html("No Student Selected")
        statsText.html("");
        answersText.html("");
        change_div_size(row);
    }
    else
    {
        nameText.html(row.find("select :selected").text());

        $.ajax({
            type: 'POST',
            url: "../../php/lecturer/get_student_result.php",
            dataType: 'json',
            data: {course:course, lab:lab, username:student, visible:"true"},
            cache: false,
            success: function(result){
                statsText.html(result.stats);
                answersText.html(result.answers);
                change_div_size(row);
            },
            error: function(xhr, status, error) {
                alert(xhr);
            }
        });

    }
}


$(window).resize(function(){
    var open = $(".open-result");

    var curHeight = open.height();
    open.css("height", "auto");
    var newHeight = open.height();
    if(curHeight != newHeight)
        open.height(newHeight);


});
